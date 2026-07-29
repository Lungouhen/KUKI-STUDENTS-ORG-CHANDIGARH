<?php

namespace Database\Seeders;

use App\Models\GeneralContent;
use App\Models\Partner;
use Illuminate\Database\Seeder;

/**
 * Seeds the CMS-managed homepage content that the public site now renders:
 * the hero slider (Swiper), achievements, and the partners carousel.
 *
 * These content types were editable in the admin panel but had no seed data,
 * so a fresh install showed empty sections.
 */
class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => "Kuki Students' Organisation Chandigarh",
                'content' => 'Uniting, empowering and guiding Kuki students across educational institutions in Chandigarh, Mohali and Panchkula.',
                'image' => '/images/gallery-1.jpg',
                'link' => null,
                'display_order' => 1,
            ],
            [
                'title' => 'Chavang Kut & Cultural Extravaganza',
                'content' => 'Celebrating our rich heritage, folk art and traditions with the wider Tricity student community.',
                'image' => '/images/gallery-3.jpg',
                'link' => '/events',
                'display_order' => 2,
            ],
            [
                'title' => '24/7 Student Welfare & Emergency Cell',
                'content' => 'Medical relief at PGIMER and GMCH-32, hostel guidance, and admission support whenever you need it.',
                'image' => '/images/event-blood.jpg',
                'link' => '/contact',
                'display_order' => 3,
            ],
        ];

        foreach ($slides as $slide) {
            GeneralContent::updateOrCreate(
                ['type' => 'slider', 'title' => $slide['title']],
                $slide + ['type' => 'slider', 'is_published' => true]
            );
        }

        $achievements = [
            ['title' => '500+ Students Supported', 'content' => 'Admission and hostel guidance delivered since inception.', 'display_order' => 1],
            ['title' => 'Annual Blood Donation Drive', 'content' => 'Organised in partnership with PGIMER Chandigarh.', 'display_order' => 2],
            ['title' => 'Merit Scholarship Programme', 'content' => 'Financial assistance for high-achieving students in need.', 'display_order' => 3],
        ];

        foreach ($achievements as $item) {
            GeneralContent::updateOrCreate(
                ['type' => 'achievement', 'title' => $item['title']],
                $item + ['type' => 'achievement', 'is_published' => true]
            );
        }

        $partners = [
            ['name' => 'PGIMER Chandigarh', 'category' => 'Government', 'type' => 'Collaborator'],
            ['name' => 'GMCH Sector 32', 'category' => 'Government', 'type' => 'Collaborator'],
            ['name' => 'Panjab University', 'category' => 'Government', 'type' => 'Collaborator'],
            ['name' => 'Kuki Inpi Chandigarh', 'category' => 'NGO', 'type' => 'Collaborator'],
            ['name' => 'North East Students Welfare Trust', 'category' => 'NGO', 'type' => 'Donor'],
            ['name' => 'Tricity Alumni Network', 'category' => 'NGO', 'type' => 'Sponsor'],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner + ['status' => 'Active']
            );
        }
    }
}
