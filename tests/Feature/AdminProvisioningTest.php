<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_command_creates_new_account_with_prompted_password(): void
    {
        $password = str_repeat('x', 16);

        $this->artisan('kso:admin:create', [
            'email' => 'new-admin@example.org',
            '--name' => 'New Admin',
        ])
            ->expectsQuestion('Admin password (at least 12 characters)', $password)
            ->expectsQuestion('Confirm admin password', $password)
            ->expectsOutputToContain('Admin account created for new-admin@example.org.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'new-admin@example.org',
            'name' => 'New Admin',
            'is_admin' => true,
        ]);
        $this->assertTrue(Hash::check($password, User::where('email', 'new-admin@example.org')->value('password')));
    }

    public function test_admin_command_never_changes_an_existing_account(): void
    {
        $passwordHash = Hash::make('existing account password');
        $existing = User::create([
            'name' => 'Existing Admin',
            'email' => 'existing-admin@example.org',
            'password' => $passwordHash,
            'is_admin' => true,
        ]);

        $this->artisan('kso:admin:create', ['email' => $existing->email])
            ->expectsOutputToContain('Existing accounts are never changed.')
            ->assertExitCode(1);

        $this->assertSame($passwordHash, $existing->fresh()->password);
        $this->assertSame('Existing Admin', $existing->fresh()->name);
    }

    public function test_database_seeding_preserves_existing_admin_credentials(): void
    {
        $passwordHash = Hash::make('existing account password');
        $existing = User::create([
            'name' => 'Existing Admin',
            'email' => 'existing-admin@example.org',
            'password' => $passwordHash,
            'is_admin' => true,
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertSame($passwordHash, $existing->fresh()->password);
    }
}
