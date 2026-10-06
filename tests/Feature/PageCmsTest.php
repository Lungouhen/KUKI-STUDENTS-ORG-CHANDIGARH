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
        ])->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Revised title',
            'slug' => 'stable-public-url',
            'is_published' => true,
        ]);
    }
}
