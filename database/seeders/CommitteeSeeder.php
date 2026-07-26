<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\News;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\Setting;

class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        $committee = [
            [
                'name' => 'Lungouhen Haokip',
                'designation' => 'President',
                'institution' => 'Panjab University, Sector 14',
                'phone' => '+91 98765 43210',
                'email' => 'president.ksochd@gmail.com',
                'photo' => '/images/default-avatar-m.png',
                'tenure' => '2025 - 2026',
                'display_order' => 1
            ],
            [
                'name' => 'Thangboi Kipgen',
                'designation' => 'Vice President',
                'institution' => 'DAV College, Sector 10',
                'phone' => '+91 98765 43212',
                'email' => 'vp.ksochd@gmail.com',
                'photo' => '/images/default-avatar-m.png',
                'tenure' => '2025 - 2026',
                'display_order' => 2
            ],
            [
                'name' => 'Lhingneiching Gangte',
                'designation' => 'General Secretary',
                'institution' => 'MCM DAV College, Sector 36',
                'phone' => '+91 98765 43213',
                'email' => 'gensec.ksochd@gmail.com',
                'photo' => '/images/default-avatar-f.png',
                'tenure' => '2025 - 2026',
                'display_order' => 3
            ],
            [
                'name' => 'Seiminlun Doungel',
                'designation' => 'Finance Secretary',
                'institution' => 'Sri Guru Gobind Singh College, Sector 26',
                'phone' => '+91 98765 43214',
                'email' => 'finance.ksochd@gmail.com',
                'photo' => '/images/default-avatar-m.png',
                'tenure' => '2025 - 2026',
                'display_order' => 4
            ],
            [
                'name' => 'Nemneihoi Chongloi',
                'designation' => 'Info & Publication Secretary',
                'institution' => 'Post Graduate Govt College, Sector 11',
                'phone' => '+91 98765 43215',
                'email' => 'info.ksochd@gmail.com',
                'photo' => '/images/default-avatar-f.png',
                'tenure' => '2025 - 2026',
                'display_order' => 5
            ]
        ];

        foreach ($committee as $c) {
            CommitteeMember::updateOrCreate(['name' => $c['name']], $c);
        }
    }
}
