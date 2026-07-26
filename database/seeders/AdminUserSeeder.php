<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\News;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ksochandigarh.org'],
            [
                'name' => 'KSO Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
    }
}
