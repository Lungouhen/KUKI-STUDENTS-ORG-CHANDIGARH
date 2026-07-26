<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\Setting;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            [
                'title' => 'KSO Chandigarh Annual Membership Drive 2025-2026 is Now OPEN',
                'category' => 'Notice',
                'date' => '2026-07-01',
                'content' => 'All students from Manipur residing/studying in Chandigarh, Mohali, and Panchkula are requested to register for the official KSO membership. Digital ID cards will be issued upon approval.',
                'author' => 'Information & Publication Desk',
                'is_important' => true
            ],
            [
                'title' => 'Emergency Medical & Hostel Assistance Cell Activated',
                'category' => 'Welfare',
                'date' => '2026-06-20',
                'content' => 'KSO Chandigarh has set up a 24/7 student support helpline for freshers requiring hostel accommodation, PG setup, or medical emergencies in PGIMER / GMCH-32.',
                'author' => 'Executive Committee',
                'is_important' => true
            ]
        ];

        foreach ($news as $n) {
            News::updateOrCreate(['title' => $n['title']], $n);
        }
    }
}
