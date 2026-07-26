<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'KSO Chandigarh Freshers\' Meet & Cultural Extravaganza 2026',
                'category' => 'Cultural',
                'date' => '2026-09-15',
                'time' => '10:00 AM - 05:00 PM',
                'venue' => 'Law Auditorium, Panjab University, Sector 14, Chandigarh',
                'description' => 'Welcoming all newly admitted Kuki students to Chandigarh institutions! Featuring traditional cultural performances, music, pageant, fellowship lunch, and guidance sessions.',
                'image' => '/images/event-freshers.jpg',
                'status' => 'Upcoming',
                'registration_link' => '#register',
                'is_featured' => true
            ],
            [
                'title' => 'Annual Inter-College Sports Meet 2026',
                'category' => 'Sports',
                'date' => '2026-10-20',
                'time' => '08:30 AM - 06:00 PM',
                'venue' => 'Panjab University Sports Ground, Sector 14, Chandigarh',
                'description' => '2-day annual sports meet including football, volleyball, badminton, athletics, and traditional games for students across Chandigarh.',
                'image' => '/images/event-sports.jpg',
                'status' => 'Upcoming',
                'registration_link' => '#register',
                'is_featured' => true
            ],
            [
                'title' => 'Blood Donation & Health Screening Camp',
                'category' => 'Social Service',
                'date' => '2026-08-12',
                'time' => '09:00 AM - 02:00 PM',
                'venue' => 'Student Centre Plaza, Panjab University, Chandigarh',
                'description' => 'In collaboration with PGIMER Blood Bank Team. Join hands to donate blood and save lives in Chandigarh medical institutions.',
                'image' => '/images/event-blood.jpg',
                'status' => 'Upcoming',
                'registration_link' => '#donate',
                'is_featured' => false
            ]
        ];

        foreach ($events as $e) {
            Event::updateOrCreate(['title' => $e['title']], $e);
        }
    }
}
