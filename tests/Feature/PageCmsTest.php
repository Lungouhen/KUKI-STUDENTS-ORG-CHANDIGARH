<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PageCmsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'CMS Admin',
            'email' => 'cms-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_new_pages_are_drafts_and_duplicate_titles_get_unique_slugs(): void
    {
        $admin = $this->admin();
        $payload = ['title' => 'Student Resources', 'content' => '<p>Content</p>'];

        $this->actingAs($admin)->post(route('admin.pages.store'), $payload)->assertRedirect(route('admin.pages.index'));
        $this->actingAs($admin)->post(route('admin.pages.store'), $payload)->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', ['slug' => 'student-resources', 'is_published' => false]);
        $this->assertDatabaseHas('pages', ['slug' => 'student-resources-2', 'is_published' => false]);
    }

    public function test_admin_can_preview_draft_but_public_route_cannot_show_it(): void
    {
        $admin = $this->admin();
        $page = Page::create([
            'title' => 'Draft guide',
            'slug' => 'draft-guide',
            'content' => '<p>Private draft body</p>',
            'is_published' => false,
        ]);

        $this->get(route('page.show', $page->slug))->assertNotFound();

        $this->actingAs($admin)
            ->get(route('admin.pages.preview', $page->id))
            ->assertOk()
            ->assertSee('Preview only — this page is not publicly available until it is published.')
            ->assertSee('Private draft body', false);

        $this->assertSame(0, $page->fresh()->view_count);
    }

    public function test_draft_preview_requires_admin_access(): void
    {
        $page = Page::create([
            'title' => 'Draft guide',
            'slug' => 'draft-guide',
            'content' => 'Private content',
            'is_published' => false,
        ]);

        $this->get(route('admin.pages.preview', $page->id))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_filter_pages_by_publication_status(): void
    {
        $admin = $this->admin();
        Page::create([
            'title' => 'Published resource',
            'slug' => 'published-resource',
            'content' => 'Published content',
            'is_published' => true,
        ]);
        Page::create([
            'title' => 'Unpublished draft',
            'slug' => 'unpublished-draft',
            'content' => 'Draft content',
            'is_published' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pages.index', ['status' => 'draft']))
            ->assertOk()
            ->assertSee('Unpublished draft')
            ->assertDontSee('Published resource');
    }

    public function test_editing_page_title_preserves_existing_public_slug(): void
    {
        $admin = $this->admin();
        $page = Page::create([
            'title' => 'Old title',
            'slug' => 'stable-public-url',
            'content' => 'Content',
            'is_published' => true,
        ]);

        $this->actingAs($admin)->put(route('admin.pages.update', $page->id), [
            'title' => 'Revised title',
            'content' => 'Updated content',
            'is_published' => '1',
            'template' => 'wide',
        ])->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Revised title',
            'slug' => 'stable-public-url',
            'template' => 'wide',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_select_and_render_a_builtin_page_template(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.pages.store'), [
            'title' => 'Registration notice',
            'content' => '<p>Applications are open.</p>',
            'template' => 'notice',
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'registration-notice')->firstOrFail();
        $this->assertSame('notice', $page->template);

        $this->get(route('page.show', $page->slug))
            ->assertOk()
            ->assertSee('NOTICE')
            ->assertSee('Applications are open.', false);
    }

    public function test_unknown_template_is_rejected(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.pages.store'), [
            'title' => 'Invalid template page',
            'content' => 'Content',
            'template' => '../admin/dashboard',
        ])->assertSessionHasErrors('template');

        $this->assertDatabaseMissing('pages', ['slug' => 'invalid-template-page']);
    }

    public function test_omitted_template_defaults_to_standard_and_existing_pages_can_still_be_updated(): void
    {
        $admin = $this->admin();
        $page = Page::create([
            'title' => 'Existing page',
            'slug' => 'existing-page',
            'content' => 'Original content',
        ]);

        $this->assertSame('standard', $page->fresh()->template);
        $this->actingAs($admin)->put(route('admin.pages.update', $page->id), [
            'title' => 'Existing page revised',
            'content' => 'Revised content',
        ])->assertRedirect(route('admin.pages.index'));

        $this->assertSame('standard', $page->fresh()->template);
    }

    public function test_invalid_stored_template_falls_back_to_standard_view(): void
    {
        $page = Page::create([
            'title' => 'Legacy page',
            'slug' => 'legacy-page',
            'content' => 'Legacy content',
        ]);
        $page->forceFill(['template' => '../admin/dashboard']);
        $this->assertSame('pages.templates.standard', $page->templateView());
    }
}
