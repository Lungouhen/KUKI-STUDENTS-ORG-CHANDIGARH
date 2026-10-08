<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminNewsModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'News Admin',
            'email' => 'news-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_news_register_has_route_scoped_assets_and_external_bulk_selection(): void
    {
        $news = $this->news();

        $response = $this->actingAs($this->admin)->get(route('admin.news.index'));
        $response->assertOk()
            ->assertSee('css/pages/admin-news.css')
            ->assertSee('js/pages/admin-news.js')
            ->assertSee($news->title)
            ->assertSee('form="newsBulkForm"', false)
            ->assertSee(route('admin.news.edit', $news->id));

        $html = $response->getContent();
        $bulkFormEnd = strpos($html, '</form>', strpos($html, 'id="newsBulkForm"'));
        $newsTable = strpos($html, '<table', strpos($html, 'id="newsBulkForm"'));
        $this->assertNotFalse($bulkFormEnd);
        $this->assertNotFalse($newsTable);
        $this->assertLessThan($newsTable, $bulkFormEnd);
    }

    public function test_create_validation_errors_preserve_announcement_fields(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.news.index'))
            ->post(route('admin.news.store'), [
                'title' => 'Announcement to correct',
                'category' => 'Academic',
                'content' => '',
                'author' => 'Student Desk',
                'publication_status' => 'published',
            ])
            ->assertRedirect(route('admin.news.index'))
            ->assertSessionHasErrors('content');

        $this->get(route('admin.news.index'))
            ->assertOk()
            ->assertSee('Announcement to correct')
            ->assertSee('Student Desk')
            ->assertSee('aria-labelledby="newsErrorsHeading"', false);
    }

    public function test_edit_form_renders_existing_announcement_accessibly(): void
    {
        $news = $this->news();

        $this->actingAs($this->admin)
            ->get(route('admin.news.edit', $news->id))
            ->assertOk()
            ->assertSee('css/pages/admin-news.css')
            ->assertSee('js/pages/admin-news.js')
            ->assertSee('for="newsTitle"', false)
            ->assertSee($news->title)
            ->assertSee($news->content);
    }

    private function news(): News
    {
        return News::create([
            'title' => 'Student academic announcement',
            'category' => 'Academic',
            'content' => 'Important information for students.',
            'author' => 'Executive Desk',
            'date' => now()->toDateString(),
            'publication_status' => 'published',
        ]);
    }
}
