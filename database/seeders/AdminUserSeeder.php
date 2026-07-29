<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@ksochandigarh.org');
        $password = env('ADMIN_PASSWORD');

        // Never ship a hardcoded, publicly documented password. If none is
        // supplied we generate a random one and print it once, so a production
        // seed cannot silently create a well-known admin login.
        $generated = false;
        if (blank($password)) {
            if (app()->environment('production')) {
                $this->command?->error(
                    'ADMIN_PASSWORD is not set. Refusing to seed an admin account in production.'
                );

                return;
            }

            $password = Str::password(16);
            $generated = true;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'KSO Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
                'role' => 'admin',
            ]
        );

        if ($generated) {
            $this->command?->warn('Generated admin password for '.$user->email.': '.$password);
            $this->command?->warn('Store it now — it will not be shown again.');
        }
    }
}
