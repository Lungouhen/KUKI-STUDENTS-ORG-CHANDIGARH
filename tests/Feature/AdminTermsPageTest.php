<?php

namespace Tests\Feature;

use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTermsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_terms_page_has_accessible_creation_form_and_scoped_assets(): void
    {
        $admin = User::create([
            'name' => 'Terms Admin',
            'email' => 'terms-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.terms.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-terms.css')
            ->assertSee('js/pages/admin-terms.js')
            ->assertSee('scope="col"', false)
            ->assertSee('for="termName"', false)
            ->assertSee('2026-2027')
            ->assertDontSee('fa-eye');
    }
}
