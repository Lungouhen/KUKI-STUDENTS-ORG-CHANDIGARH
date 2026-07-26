<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            MemberSeeder::class,
            CommitteeSeeder::class,
            EventSeeder::class,
            NewsSeeder::class,
            GallerySeeder::class,
            DonationSeeder::class,
            SettingSeeder::class,
            FinancialSeeder::class,
            PageSeeder::class,
        ]);
    }
}
