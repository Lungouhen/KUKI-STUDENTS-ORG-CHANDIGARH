<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\GeneralContent;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsUpgradeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'CMS Upgrade Admin',
            'email' => 'cms-upgrade@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_page_revisions_can_be_previewed_compared_and_restored(): void
    {
        $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'First title',
            'content' => '<p>First version</p>',
            'publication_status' => 'draft',
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'first-title')->firstOrFail();
        $firstRevision = $page->revisions()->firstOrFail();

        $this->put(route('admin.pages.update', $page->id), [
            'title' => 'Second title',
            'content' => '<p>Second version</p>',
            'template' => 'wide',
            'publication_status' => 'draft',
        ])->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('page_revisions', ['page_id' => $page->id, 'version' => 2]);
        $this->get(route('admin.pages.revisions.preview', [$page->id, $firstRevision->id]))
            ->assertOk()
            ->assertSee('First title')
            ->assertSee('First version', false);

        $this->get(route('admin.pages.revisions.compare', [$page->id, $firstRevision->id]))
            ->assertOk()
            ->assertSee('First version')
            ->assertSee('Second version');

        $this->post(route('admin.pages.revisions.restore', [$page->id, $firstRevision->id]))
            ->assertRedirect(route('admin.pages.edit', $page->id));

        $this->assertSame('First title', $page->fresh()->title);
        $this->assertSame('<p>First version</p>', $page->fresh()->content);
        $this->assertSame('standard', $page->fresh()->template);
        $this->assertDatabaseHas('page_revisions', ['page_id' => $page->id, 'version' => 3]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'RESTORE_PAGE_REVISION', 'user_id' => $this->admin->id]);
    }

    public function test_structured_sections_render_escaped_text_and_reject_unsafe_links(): void
    {
        $asset = $this->mediaAsset('/storage/uploads/media/section.png', 'Section image');

        $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'Structured guide',
            'content' => '',
            'publication_status' => 'published',
            'sections' => [
                ['type' => 'heading', 'heading' => 'Useful information'],
                ['type' => 'text', 'text' => '<script>alert(1)</script>'],
                ['type' => 'image', 'image' => $asset->path],
            ],
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'structured-guide')->firstOrFail();
        $this->get(route('page.show', $page->slug))
            ->assertOk()
            ->assertSee('Useful information')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('alt="Section image"', false);

        $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'Unsafe button',
            'content' => '',
            'sections' => [
                ['type' => 'button', 'link_label' => 'Click', 'link_url' => 'javascript:alert(1)'],
            ],
        ])->assertSessionHasErrors('sections.0.link_url');

        $this->assertDatabaseMissing('pages', ['slug' => 'unsafe-button']);
    }

    public function test_scheduled_pages_publish_when_the_scheduler_command_runs(): void
    {
        $publishAt = now()->addMinutes(5)->startOfMinute();
        $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'Scheduled page',
            'content' => 'Scheduled content',
            'publication_status' => 'scheduled',
            'scheduled_publish_at' => $publishAt->toDateTimeString(),
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'scheduled-page')->firstOrFail();
        $this->assertSame('scheduled', $page->publication_status);
        $this->assertFalse($page->is_published);
        $this->app['auth']->guard()->logout();
        $this->get(route('page.show', $page->slug))->assertNotFound();

        $this->travelTo($publishAt->copy()->addMinute());
        $this->artisan('pages:publish-scheduled')
            ->expectsOutput('1 scheduled page(s) published.')
            ->assertExitCode(0);

        $this->assertSame('published', $page->fresh()->publication_status);
        $this->assertTrue($page->fresh()->is_published);
        $this->get(route('page.show', $page->slug))->assertOk();
    }

    public function test_content_search_bulk_publish_and_reorder_are_scoped_by_type(): void
    {
        $first = GeneralContent::create([
            'type' => 'notice',
            'title' => 'First notice',
            'content' => 'First text',
            'is_published' => true,
            'display_order' => 1,
        ]);
        $second = GeneralContent::create([
            'type' => 'notice',
            'title' => 'Second notice',
            'content' => 'Second text',
            'is_published' => true,
            'display_order' => 2,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.content.index', ['type' => 'notice', 'q' => 'Second', 'status' => 'published']))
            ->assertOk()
            ->assertSee('Second notice')
            ->assertDontSee('First notice');

        $this->post(route('admin.content.bulk'), [
            'type' => 'notice',
            'ids' => [$first->id, $second->id],
            'action' => 'reorder',
            'display_orders' => [$first->id => 2, $second->id => 1],
        ])->assertRedirect();

        $this->assertSame(2, $first->fresh()->display_order);
        $this->assertSame(1, $second->fresh()->display_order);

        $this->post(route('admin.content.bulk'), [
            'type' => 'notice',
            'ids' => [$first->id, $second->id],
            'action' => 'unpublish',
        ])->assertRedirect();

        $this->assertFalse($first->fresh()->is_published);
        $this->assertFalse($second->fresh()->is_published);
    }

    public function test_page_search_and_bulk_editorial_actions_work(): void
    {
        $draft = Page::create([
            'title' => 'Student travel guide',
            'slug' => 'student-travel-guide',
            'content' => 'Travel information',
            'is_published' => false,
        ]);
        $published = Page::create([
            'title' => 'Campus services',
            'slug' => 'campus-services',
            'content' => 'Campus information',
            'is_published' => true,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.pages.index', ['q' => 'travel', 'status' => 'draft']))
            ->assertOk()
            ->assertSee('Student travel guide')
            ->assertDontSee('Campus services');

        $this->post(route('admin.pages.bulk'), [
            'ids' => [$draft->id, $published->id],
            'action' => 'review',
        ])->assertRedirect();

        $this->assertSame('review', $draft->fresh()->publication_status);
        $this->assertSame('review', $published->fresh()->publication_status);
        $this->assertFalse($published->fresh()->is_published);
    }

    public function test_gallery_can_reuse_assets_and_media_library_only_deletes_orphans(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/media/community.png', 'test image bytes');
        $asset = $this->mediaAsset('/storage/uploads/media/community.png', 'Community service event');

        $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'Community service',
            'category' => 'Outreach',
            'image_asset_path' => $asset->path,
            'caption' => 'Volunteers at work',
        ])->assertRedirect();

        $galleryItem = GalleryItem::firstOrFail();
        $this->assertSame($asset->path, $galleryItem->image_url);
        $this->assertSame('Community service event', $galleryItem->mediaAsset->alt_text);

        $this->delete(route('admin.media.destroy', $asset->id))
            ->assertSessionHasErrors('media');
        Storage::disk('public')->assertExists('uploads/media/community.png');

        $this->actingAs($this->admin)->delete(route('admin.gallery.destroy', $galleryItem->id))
            ->assertRedirect();
        $this->delete(route('admin.media.destroy', $asset->id))->assertRedirect();

        $this->assertDatabaseMissing('media_assets', ['id' => $asset->id]);
        Storage::disk('public')->assertMissing('uploads/media/community.png');
    }

    public function test_media_uploads_require_and_store_alt_text(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'imageFile' => UploadedFile::fake()->image('team.png'),
            'alt_text' => 'KSO Chandigarh volunteers at an event',
        ])->assertRedirect();

        $asset = MediaAsset::firstOrFail();
        $this->assertSame('KSO Chandigarh volunteers at an event', $asset->alt_text);
        $this->assertStringStartsWith('/storage/uploads/media/', $asset->path);
        $this->assertCount(1, Storage::disk('public')->files('uploads/media'));
    }

    public function test_media_search_and_alt_text_updates_are_available_to_admins(): void
    {
        $asset = $this->mediaAsset('/storage/uploads/media/campaign.png', 'Campus health camp');

        $this->actingAs($this->admin)
            ->get(route('admin.media.index', ['q' => 'health', 'usage' => 'unused']))
            ->assertOk()
            ->assertSee('campaign.png')
            ->assertSee('Unused');

        $this->put(route('admin.media.update', $asset->id), ['alt_text' => 'Students at health camp'])
            ->assertRedirect();

        $this->assertSame('Students at health camp', $asset->fresh()->alt_text);
    }

    private function mediaAsset(string $path, string $altText): MediaAsset
    {
        return MediaAsset::create([
            'path' => $path,
            'original_name' => basename($path),
            'alt_text' => $altText,
            'mime_type' => 'image/png',
            'size' => 128,
            'uploaded_by' => $this->admin->id,
        ]);
    }
}
