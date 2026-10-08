<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminGalleryModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Gallery Admin',
            'email' => 'gallery-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_gallery_register_displays_accessible_cards_and_route_assets(): void
    {
        $asset = MediaAsset::create([
            'path' => '/storage/uploads/media/orientation.jpg',
            'original_name' => 'orientation.jpg',
            'alt_text' => 'Students at orientation',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'uploaded_by' => $this->admin->id,
        ]);
        GalleryItem::create([
            'title' => 'Student orientation',
            'category' => 'Student Life',
            'image_url' => $asset->path,
            'date' => now()->toDateString(),
            'media_asset_id' => $asset->id,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.gallery.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-gallery.css')
            ->assertSee('js/pages/admin-gallery.js')
            ->assertSee('Student orientation')
            ->assertSee('alt="Students at orientation"', false)
            ->assertSee('for="galleryImageFile"', false);
    }

    public function test_invalid_gallery_submission_keeps_text_fields_and_accessible_errors(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.gallery.index'))
            ->post(route('admin.gallery.store'), [
                'title' => 'A photo with no image',
                'category' => 'Student Life',
            ])
            ->assertRedirect(route('admin.gallery.index'))
            ->assertSessionHasErrors(['imageFile', 'image_asset_path']);

        $this->get(route('admin.gallery.index'))
            ->assertOk()
            ->assertSee('A photo with no image')
            ->assertSee('aria-labelledby="galleryErrorsHeading"', false);
    }
}
