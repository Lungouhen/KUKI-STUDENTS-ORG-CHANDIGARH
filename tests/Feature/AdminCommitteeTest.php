<?php

namespace Tests\Feature;

use App\Models\CommitteeMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCommitteeTest extends TestCase
{
    use RefreshDatabase;

    public function test_committee_directory_has_accessible_member_form_and_scoped_assets(): void
    {
        $admin = User::create([
            'name' => 'Committee Admin',
            'email' => 'committee-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.committee.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-committee.css')
            ->assertSee('js/pages/admin-committee.js')
            ->assertSee('scope="col"', false)
            ->assertSee('for="committeeName"', false)
            ->assertSee('No executive council members have been added.');
    }

    public function test_committee_edit_form_shows_current_photo_and_accessible_fields(): void
    {
        $admin = User::create([
            'name' => 'Committee Editor',
            'email' => 'committee-editor@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        $member = CommitteeMember::create([
            'name' => 'Council Student',
            'designation' => 'President',
            'institution' => 'Panjab University',
            'phone' => '+91 90000 12345',
            'photo' => '/storage/uploads/committee/council.jpg',
            'tenure' => '2025 - 2026',
            'display_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.committee.edit', $member->id))
            ->assertOk()
            ->assertSee('css/pages/admin-committee-edit.css')
            ->assertSee('js/pages/admin-committee-edit.js')
            ->assertSee('for="committeeEditName"', false)
            ->assertSee('alt="Current photo of Council Student"', false);
    }
}
