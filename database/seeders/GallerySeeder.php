<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\Setting;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $gallery = [
            [
                'title' => 'Freshers Meet Cultural Dance Performance',
                'category' => 'Cultural Night',
                'image_url' => '/images/gallery-1.jpg',
                'date' => '2025-09-18',
                'caption' => 'Students performing traditional Kuki folk dance at PU Law Auditorium'
            ],
            [
                'title' => 'Annual Football Championship Winners',
                'category' => 'Annual Sports',
                'image_url' => '/images/gallery-2.jpg',
                'date' => '2025-10-25',
                'caption' => 'Victory moment of KSO Chandigarh Football Tournament'
            ],
            [
                'title' => 'Blood Donation Drive at PGIMER',
                'category' => 'Social Service',
                'image_url' => '/images/gallery-3.jpg',
                'date' => '2025-11-12',
                'caption' => 'Student volunteers donating blood for local hospitals'
            ],
            [
                'title' => 'Chavang Kut Festival Celebration',
                'category' => 'Cultural Night',
                'image_url' => '/images/gallery-5.jpg',
                'date' => '2025-11-01',
                'caption' => 'Celebrating Chavang Kut post-harvest festival in Chandigarh'
            ]
        ];

        foreach ($gallery as $g) {
            GalleryItem::updateOrCreate(['title' => $g['title']], $g);
        }
    }
}
