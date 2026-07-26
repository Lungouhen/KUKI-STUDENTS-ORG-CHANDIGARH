<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\Setting;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donations = [
            [
                'donor_name' => 'Dr. Haokip (Alumni)',
                'amount' => 5000,
                'currency' => 'INR',
                'cause' => 'Student Emergency Welfare Fund',
                'phone' => '+91 98111 22334',
                'email' => 'dr.haokip@pugi.ac.in',
                'payment_ref' => 'UPI/628190381023',
                'status' => 'Completed',
                'date' => '2026-07-10'
            ],
            [
                'donor_name' => 'Thangmang & Family',
                'amount' => 2500,
                'currency' => 'INR',
                'cause' => 'Annual Cultural Meet',
                'phone' => '+91 98111 55667',
                'email' => 'thangmang@gmail.com',
                'payment_ref' => 'UPI/719283910283',
                'status' => 'Completed',
                'date' => '2026-07-15'
            ]
        ];

        foreach ($donations as $d) {
            Donation::updateOrCreate(['payment_ref' => $d['payment_ref']], $d);
        }
    }
}
