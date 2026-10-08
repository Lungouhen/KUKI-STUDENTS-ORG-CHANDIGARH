<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminColumnManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_table_pages_load_the_column_manager_assets(): void
    {
        $admin = User::create([
            'name' => 'Column Manager Admin',
            'email' => 'column-manager-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.members.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-column-manager.css')
            ->assertSee('js/pages/admin-column-manager.js')
            ->assertSee('data-admin-user-id="'.$admin->id.'"', false);
    }

    public function test_public_pages_do_not_load_the_admin_column_manager(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('admin-column-manager.css')
            ->assertDontSee('admin-column-manager.js');
    }
}
