<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'siteName' => 'Kuki Students\' Organisation Chandigarh',
            'abbreviation' => 'KSO CHANDIGARH',
            'tagline' => 'Empowering Students • Preserving Culture • Serving Community',
            'email' => 'ksochandigarh@gmail.com',
            'phone' => '+91 98765 43210',
            'helpline' => '+91 98765 43211',
            'address' => 'Room 12, Student Centre, Panjab University, Sector 14, Chandigarh, 160014',
            'announcement' => '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!',
            'upiId' => 'ksochandigarh@upi',
            'facebook' => 'https://facebook.com/ksochandigarh',
            'instagram' => 'https://instagram.com/kso_chandigarh',
            'whatsapp' => '+919876543210'
        ];

        foreach ($settings as $k => $v) {
            Setting::set($k, $v);
        }
    }
}
