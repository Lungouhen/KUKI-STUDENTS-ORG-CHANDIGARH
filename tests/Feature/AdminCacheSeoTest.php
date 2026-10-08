<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCacheSeoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Maintenance Admin',
            'email' => 'maintenance-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_cache_manager_is_admin_only_and_cache_actions_are_allowlisted_and_audited(): void
    {
        $this->get(route('admin.cache.index'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($this->admin)
            ->get(route('admin.cache.index'))
            ->assertOk()
            ->assertSee('Cache Manager')
            ->assertSee('All optimized caches');

        $this->post('/admin/cache/unlisted/clear')->assertNotFound();

        $this->post(route('admin.cache.clear', 'application'))
            ->assertRedirect(route('admin.cache.index'))
            ->assertSessionHas('success', 'Application cache cleared successfully.');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'CLEAR_APPLICATION_CACHE',
            'details' => json_encode(['cache' => 'application', 'command' => 'cache:clear']),
        ]);
    }

    public function test_seo_settings_are_validated_saved_and_audited_without_setting_values(): void
    {
        $image = MediaAsset::create([
            'path' => '/storage/uploads/media/social-card.png',
            'original_name' => 'social-card.png',
            'alt_text' => 'Organization social card',
            'mime_type' => 'image/png',
            'size' => 2048,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.seo.update'), [
                'seoTitle' => 'KSO Chandigarh',
                'seoDescription' => 'Official organization website.',
                'seoSocialImage' => $image->path,
                'seoIndexingEnabled' => '1',
            ])
            ->assertRedirect(route('admin.seo.index'));

        $this->assertSame('KSO Chandigarh', Setting::get('seoTitle'));
        $this->assertSame('Official organization website.', Setting::get('seoDescription'));
        $this->assertSame($image->path, Setting::get('seoSocialImage'));
        $this->assertSame('1', (string) Setting::get('seoIndexingEnabled'));
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'UPDATE_SEO_SETTINGS',
            'details' => json_encode(['updated_keys' => ['seoTitle', 'seoDescription', 'seoSocialImage', 'seoIndexingEnabled']]),
        ]);

        $this->post(route('admin.seo.update'), [
            'seoTitle' => str_repeat('x', 256),
            'seoDescription' => 'Invalid description',
            'seoSocialImage' => '/not-in-media-library.png',
            'seoIndexingEnabled' => 'yes',
        ])->assertSessionHasErrors(['seoTitle', 'seoSocialImage', 'seoIndexingEnabled']);

        $this->assertSame('KSO Chandigarh', Setting::get('seoTitle'));
    }

    public function test_public_metadata_uses_page_overrides_and_drafts_are_not_indexed(): void
    {
        Setting::set('seoSocialImage', '/storage/uploads/media/default-social-card.png');

        $page = Page::create([
            'title' => 'Published guide',
            'slug' => 'published-guide',
            'excerpt' => 'A short fallback excerpt.',
            'meta_title' => 'Guide SEO title',
            'meta_description' => 'Page-specific search description.',
            'content' => 'Public content',
            'is_published' => true,
        ]);

        $this->get(route('page.show', $page->slug))
            ->assertOk()
            ->assertSee('<title>Guide SEO title</title>', false)
            ->assertSee('<meta name="description" content="Page-specific search description.">', false)
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('<meta property="og:image" content="'.asset('/storage/uploads/media/default-social-card.png').'">', false)
            ->assertSee('<link rel="canonical" href="'.rtrim(config('app.url'), '/').'/page/published-guide">', false);

        $draft = Page::create([
            'title' => 'Unpublished guide',
            'slug' => 'unpublished-guide',
            'content' => 'Draft content',
            'is_published' => false,
        ]);

        $this->actingAs($this->admin)
            ->get(route('page.show', $draft->slug))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    }

    public function test_public_pages_use_the_configured_seo_defaults(): void
    {
        Setting::set('seoDescription', 'Organization-wide search description.');

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<title>About Us | KSO Chandigarh</title>', false)
            ->assertSee('<meta name="description" content="Organization-wide search description.">', false)
            ->assertSee('<meta name="robots" content="index,follow">', false);
    }

    public function test_robots_and_sitemap_respect_indexing_and_include_only_public_cms_pages(): void
    {
        $published = Page::create([
            'title' => 'Published sitemap page',
            'slug' => 'published-sitemap-page',
            'content' => 'Public content',
            'is_published' => true,
        ]);
        $draft = Page::create([
            'title' => 'Draft sitemap page',
            'slug' => 'draft-sitemap-page',
            'content' => 'Private draft content',
            'is_published' => false,
        ]);

        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Allow: /')
            ->assertSee('Sitemap: '.rtrim(config('app.url'), '/').'/sitemap.xml');

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(rtrim(config('app.url'), '/').route('page.show', ['slug' => $published->slug], false), false)
            ->assertDontSee(route('page.show', ['slug' => $draft->slug], false), false);

        Setting::set('seoIndexingEnabled', false);

        $this->get(route('page.show', $published->slug))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);

        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Allow: /')
            ->assertDontSee('Disallow: /')
            ->assertDontSee('Sitemap:');

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertDontSee($published->slug);
    }
}
