<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBeneficiariesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_beneficiary_register_has_accessible_search_and_responsive_assets(): void
    {
        $admin = User::create([
            'name' => 'Beneficiaries Admin',
            'email' => 'beneficiaries-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.beneficiaries.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-beneficiaries.css')
            ->assertSee('js/pages/admin-beneficiaries.js')
            ->assertSee('Filter beneficiaries by name, project, type, or support details')
            ->assertSee('scope="col"', false)
            ->assertSee('No beneficiaries recorded yet.');
    }
}
