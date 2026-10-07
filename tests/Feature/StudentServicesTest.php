<?php

namespace Tests\Feature;

use App\Models\Accommodation;
use App\Models\Member;
use App\Models\News;
use App\Models\StudentResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentServicesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Services Admin',
            'email' => 'services-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->member = Member::create([
            'id' => 'KSO-CHD-2026-0200',
            'full_name' => 'Portal Student',
            'gender' => 'Male',
            'dob' => '2004-03-03',
            'phone' => '+91 92222 00000',
            'email' => 'portalstudent@kso.org',
            'blood_group' => 'O+',
            'institution' => 'Panjab University',
            'course' => 'BA',
            'year_of_study' => '1st Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 92222 11111',
            'status' => 'Approved',
        ]);
    }

    private function loginAsMember(): void
    {
        $this->withSession(['member_id' => $this->member->id]);
    }

    // ── Accommodations ──

    public function test_admin_can_create_accommodation_listing(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/accommodations', [
            'name' => 'Tricity Student Co-Living',
            'type' => 'Co-Living PG',
            'location' => 'Sector 15-B, Chandigarh',
            'landmark' => '5 mins to PU',
            'rent_monthly' => 5500,
            'description' => 'Wi-Fi, meals, laundry.',
            'contact_phone' => '+91 98765 43210',
            'photoFile' => UploadedFile::fake()->image('room.jpg'),
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.accommodations.index'));
        $listing = Accommodation::first();
        $this->assertNotNull($listing);
        $this->assertTrue($listing->is_active);
        $this->assertStringStartsWith('/storage/uploads/accommodations/', $listing->photo);
        $this->assertDatabaseHas('audit_logs', ['action' => 'CREATE_ACCOMMODATION']);
    }

    public function test_admin_can_update_and_hide_accommodation(): void
    {
        $listing = Accommodation::create([
            'name' => 'Old Name',
            'type' => 'Hostel',
            'location' => 'Sector 11',
            'rent_monthly' => 4800,
            'contact_phone' => '+91 90000 00001',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/accommodations/{$listing->id}", [
            'name' => 'Renamed Hostel',
            'type' => 'Hostel',
            'location' => 'Sector 11-A, Chandigarh',
            'rent_monthly' => 5000,
            'contact_phone' => '+91 90000 00001',
            'is_active' => 0,
        ]);

        $response->assertRedirect(route('admin.accommodations.index'));
        $listing->refresh();
        $this->assertSame('Renamed Hostel', $listing->name);
        $this->assertFalse($listing->is_active);
    }

    public function test_invalid_accommodation_type_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/accommodations', [
            'name' => 'Bad Type',
            'type' => 'Castle',
            'location' => 'Sector 1',
            'rent_monthly' => 1000,
            'contact_phone' => '123',
        ]);

        $response->assertSessionHasErrors('type');
        $this->assertSame(0, Accommodation::count());
    }

    public function test_guest_cannot_manage_accommodations(): void
    {
        $response = $this->post('/admin/accommodations', [
            'name' => 'X',
            'type' => 'Hostel',
            'location' => 'Y',
            'rent_monthly' => 1,
            'contact_phone' => '1',
        ]);

        $response->assertRedirect('/admin/login');
        $this->assertSame(0, Accommodation::count());
    }

    public function test_portal_dashboard_shows_only_active_listings(): void
    {
        $visible = Accommodation::create([
            'name' => 'Visible PG',
            'type' => 'Girls PG',
            'location' => 'Sector 36',
            'rent_monthly' => 6000,
            'contact_phone' => '+91 90000 00002',
            'is_active' => true,
        ]);
        Accommodation::create([
            'name' => 'Hidden PG',
            'type' => 'Boys PG',
            'location' => 'Sector 40',
            'rent_monthly' => 4000,
            'contact_phone' => '+91 90000 00003',
            'is_active' => false,
        ]);

        $this->loginAsMember();
        $response = $this->get('/members/portal/dashboard');

        $response->assertOk();
        $response->assertSee('Visible PG');
        $response->assertDontSee('Hidden PG');
        $response->assertViewHas('accommodations', fn ($list) => $list->count() === 1 && $list->first()->id === $visible->id);
    }

    // ── Student resources ──

    public function test_admin_can_upload_resource(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/resources', [
            'title' => 'PU Exam Calendar',
            'category' => 'Institutional Guides',
            'resourceFile' => UploadedFile::fake()->create('calendar.pdf', 512, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $resource = StudentResource::first();
        $this->assertNotNull($resource);
        $this->assertSame('PU Exam Calendar', $resource->title);
        $this->assertTrue($resource->is_active);
        $this->assertGreaterThan(0, $resource->file_size);
        Storage::disk('public')->assertExists($resource->file_path);
        $this->assertDatabaseHas('audit_logs', ['action' => 'CREATE_STUDENT_RESOURCE']);
    }

    public function test_resource_upload_rejects_disallowed_extension(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/resources', [
            'title' => 'Malicious',
            'category' => 'Question Banks',
            'resourceFile' => UploadedFile::fake()->create('evil.php', 10, 'text/x-php'),
        ]);

        $response->assertSessionHasErrors('resourceFile');
        $this->assertSame(0, StudentResource::count());
    }

    public function test_member_can_download_active_resource_and_count_increments(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('guide.pdf', 100, 'application/pdf')
            ->store('uploads/resources', 'public');
        $resource = StudentResource::create([
            'title' => 'Scholarship Guide',
            'category' => 'Scholarships & Financial Aid',
            'file_path' => $path,
            'file_size' => 102400,
        ]);

        $this->loginAsMember();
        $response = $this->get("/members/portal/resources/{$resource->id}/download");

        $response->assertOk();
        $response->assertDownload('scholarship-guide.pdf');
        $this->assertSame(1, $resource->fresh()->download_count);
    }

    public function test_guest_cannot_download_resource(): void
    {
        $resource = StudentResource::create([
            'title' => 'Guide',
            'category' => 'Question Banks',
            'file_path' => 'uploads/resources/none.pdf',
        ]);

        $response = $this->get("/members/portal/resources/{$resource->id}/download");

        $response->assertRedirect(route('membership.portal'));
        $this->assertSame(0, $resource->fresh()->download_count);
    }

    public function test_inactive_resource_is_not_downloadable(): void
    {
        $resource = StudentResource::create([
            'title' => 'Hidden Guide',
            'category' => 'Question Banks',
            'file_path' => 'uploads/resources/hidden.pdf',
            'is_active' => false,
        ]);

        $this->loginAsMember();
        $response = $this->get("/members/portal/resources/{$resource->id}/download");

        $response->assertNotFound();
    }

    public function test_admin_can_toggle_and_delete_resource(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('bank.zip', 10, 'application/zip')
            ->store('uploads/resources', 'public');
        $resource = StudentResource::create([
            'title' => 'Question Bank',
            'category' => 'Question Banks',
            'file_path' => $path,
        ]);

        $this->actingAs($this->admin)->post("/admin/resources/{$resource->id}/toggle");
        $this->assertFalse($resource->fresh()->is_active);

        $this->actingAs($this->admin)->delete("/admin/resources/{$resource->id}");
        $this->assertSame(0, StudentResource::count());
        Storage::disk('public')->assertMissing($path);
    }

    // ── Member feed separation ──

    public function test_member_posts_are_flagged_and_hidden_from_public_pages(): void
    {
        News::create([
            'title' => 'Official Notice',
            'category' => 'Notice',
            'date' => now()->toDateString(),
            'content' => 'Official announcement from the executive desk.',
            'author' => 'Executive Desk',
        ]);

        $this->loginAsMember();
        $this->post('/members/portal/post', [
            'content' => 'Selling my old cycle, DM me!',
            'category' => 'General',
        ])->assertSessionHas('success');

        $post = News::where('is_member_post', true)->first();
        $this->assertNotNull($post);
        $this->assertSame('Portal Student', $post->author);

        $home = $this->get('/');
        $home->assertOk();
        $home->assertDontSee('Selling my old cycle');

        $events = $this->get('/events');
        $events->assertOk();
        $events->assertDontSee('Selling my old cycle');
        $events->assertSee('Official Notice');
    }

    public function test_member_posts_still_appear_in_portal_feed(): void
    {
        News::create([
            'title' => 'Update from Portal Student',
            'category' => 'General',
            'date' => now()->toDateString(),
            'content' => 'Hostel tips for freshers.',
            'author' => 'Portal Student',
            'is_member_post' => true,
        ]);

        $this->loginAsMember();
        $response = $this->get('/members/portal/dashboard');

        $response->assertOk();
        $response->assertSee('Hostel tips for freshers.');
    }
}
