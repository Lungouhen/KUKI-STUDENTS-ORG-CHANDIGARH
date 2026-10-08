<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminMediaModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Media Admin',
            'email' => 'media-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_media_library_renders_search_usage_filters_and_accessible_assets(): void
    {
        $asset = MediaAsset::create([
            'path' => '/storage/uploads/media/students.jpg',
            'original_name' => 'students.jpg',
            'alt_text' => 'Students at a campus event',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
            'uploaded_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.media.index', ['q' => 'students', 'usage' => 'unused']))
            ->assertOk()
            ->assertSee('css/pages/admin-media.css')
            ->assertSee('js/pages/admin-media.js')
            ->assertSee('students.jpg')
            ->assertSee('alt="Students at a campus event"', false)
            ->assertSee('for="media-alt-'.$asset->id.'"', false);
    }

    public function test_media_upload_validation_errors_are_announced(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.media.index'))
            ->post(route('admin.media.store'), [
                'alt_text' => '',
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHasErrors(['imageFile', 'alt_text']);

        $this->get(route('admin.media.index'))
            ->assertOk()
            ->assertSee('aria-labelledby="mediaErrorsHeading"', false)
            ->assertSee('Review the media details');
    }
}
