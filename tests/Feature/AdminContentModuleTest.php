<?php

namespace Tests\Feature;

use App\Models\GeneralContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminContentModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Content Admin',
            'email' => 'content-module-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_content_manager_scopes_rows_and_renders_page_assets(): void
    {
        GeneralContent::create([
            'type' => 'notice',
            'title' => 'Visible notice',
            'content' => 'Notice copy.',
            'publication_status' => 'published',
            'display_order' => 1,
        ]);
        GeneralContent::create([
            'type' => 'campaign',
            'title' => 'Other content type',
            'content' => 'Campaign copy.',
            'publication_status' => 'published',
            'display_order' => 1,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.content.index', ['type' => 'notice']))
            ->assertOk()
            ->assertSee('css/pages/admin-content.css')
            ->assertSee('js/pages/admin-content.js')
            ->assertSee('Visible notice')
            ->assertDontSee('Other content type')
            ->assertSee('form="bulk-content-form"', false);
    }

    public function test_create_validation_reopens_modal_and_preserves_content_fields(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.content.index', ['type' => 'notice']))
            ->post(route('admin.content.store'), [
                'type' => 'notice',
                'title' => '',
                'content' => 'Text to keep after validation.',
            ])
            ->assertRedirect(route('admin.content.index', ['type' => 'notice']))
            ->assertSessionHasErrors('title');

        $this->get(route('admin.content.index', ['type' => 'notice']))
            ->assertOk()
            ->assertSee('data-reopen-on-error="true"', false)
            ->assertSee('Text to keep after validation.');
    }
}
