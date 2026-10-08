<?php

namespace Tests\Feature;

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
}
