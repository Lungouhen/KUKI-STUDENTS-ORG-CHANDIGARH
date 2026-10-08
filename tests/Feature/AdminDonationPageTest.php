<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDonationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_register_has_status_filtering_and_accessible_receipts(): void
    {
        $admin = User::create([
            'name' => 'Donation Admin',
            'email' => 'donation-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        Donation::create([
            'donor_name' => 'Student Welfare Donor',
            'amount' => 500,
            'currency' => 'INR',
            'cause' => 'Medical relief',
            'phone' => '+91 90000 44444',
            'email' => 'donor@example.org',
            'payment_ref' => 'DON-REF-001',
            'status' => 'Completed',
            'date' => '2026-10-01',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.donations.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-donations.css')
            ->assertSee('js/pages/admin-donations.js')
            ->assertSee('Completed')
            ->assertSee('Total Collected: ₹500.00')
            ->assertSee('Filter donations by donor, cause, contact, reference, or status')
            ->assertSee('scope="col"', false)
            ->assertSee('aria-label="Open receipt for Student Welfare Donor"', false);
    }

    public function test_admin_receipt_is_printable_and_uses_scoped_assets(): void
    {
        $admin = User::create([
            'name' => 'Receipt Admin',
            'email' => 'receipt-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        $donation = Donation::create([
            'donor_name' => 'Receipt Donor',
            'amount' => 1250,
            'currency' => 'INR',
            'cause' => 'Student books',
            'payment_ref' => 'DON-REF-002',
            'status' => 'Completed',
            'date' => '2026-10-02',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.donations.receipt', $donation->id))
            ->assertOk()
            ->assertSee('css/pages/admin-donation-receipt.css')
            ->assertSee('js/pages/admin-donation-receipt.js')
            ->assertSee('scope="col"', false)
            ->assertSee('id="printDonationReceipt"', false)
            ->assertSee('alt="KSO Chandigarh emblem"', false);
    }
}
