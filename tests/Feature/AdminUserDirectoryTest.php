<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_directory_shows_real_admin_access_and_filter_controls(): void
    {
        $admin = User::create([
            'name' => 'Directory Admin',
            'email' => 'directory-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        User::create([
            'name' => 'Standard Account',
            'email' => 'standard-account@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-users.css')
            ->assertSee('js/pages/admin-users.js')
            ->assertSee('Filter users by name, email, or role')
            ->assertSee('Administrator')
            ->assertSee('Enabled')
            ->assertSee('Not assigned')
            ->assertDontSee('New Admin')
            ->assertSee('scope="col"', false);
    }
}
