<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'kso:admin:create {email : Email address for the new admin} {--name= : Name for the new admin}';

    protected $description = 'Create an admin account without changing existing users';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a valid email address.');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('An account with this email already exists. Existing accounts are never changed.');

            return self::FAILURE;
        }

        $name = trim($this->option('name') ?: $this->ask('Admin name'));
        if ($name === '') {
            $this->error('An admin name is required.');

            return self::FAILURE;
        }

        $password = $this->secret('Admin password (at least 12 characters)');
        $confirmation = $this->secret('Confirm admin password');

        if (! is_string($password) || strlen($password) < 12 || $password !== $confirmation) {
            $this->error('The password must be at least 12 characters and both entries must match.');

            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info("Admin account created for {$email}.");

        return self::SUCCESS;
    }
}
