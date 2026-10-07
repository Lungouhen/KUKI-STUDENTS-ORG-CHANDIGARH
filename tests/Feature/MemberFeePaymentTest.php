<?php

namespace Tests\Feature;

use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\MemberFeePayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberFeePaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Member $member;
    private FinancialAccount $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Fees Admin',
            'email' => 'fees-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->member = Member::create([
            'id' => 'KSO-CHD-2026-0100',
            'full_name' => 'Fee Paying Member',
            'gender' => 'Female',
            'dob' => '2004-02-02',
            'phone' => '+91 91111 00000',
            'email' => 'feemember@kso.org',
            'blood_group' => 'A+',
            'institution' => 'DAV College',
            'course' => 'BCom',
            'year_of_study' => '1st Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 91111 11111',
            'status' => 'Approved',
        ]);

        $this->account = FinancialAccount::create([
            'account_code' => 'CASH-001',
            'account_name' => 'Cash Account',
            'account_type' => 'Asset',
            'current_balance' => 100,
            'is_active' => true,
        ]);
    }

    private function paymentPayload(array $overrides = []): array
    {
        return array_merge([
            'financial_account_id' => $this->account->id,
            'period' => '2026-27',
            'amount' => 250,
            'payment_method' => 'Cash',
            'reference_no' => 'RCPT-001',
            'paid_on' => '2026-08-01',
        ], $overrides);
    }

    public function test_admin_can_record_fee_payment_posting_income_voucher(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/admin/members/{$this->member->id}/fees", $this->paymentPayload());

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $payment = MemberFeePayment::where('member_id', $this->member->id)->first();
        $this->assertNotNull($payment);
        $this->assertSame('2026-27', $payment->period);
        $this->assertNotNull($payment->voucher_no);
        $this->assertSame($this->admin->id, $payment->recorded_by);

        $this->assertDatabaseHas('transactions', [
            'voucher_no' => $payment->voucher_no,
            'type' => 'Income',
            'category' => 'Membership Fee',
            'financial_account_id' => $this->account->id,
        ]);
        $this->assertEquals(350.0, (float) $this->account->fresh()->current_balance);
        $this->assertDatabaseHas('audit_logs', ['action' => 'RECORD_FEE_PAYMENT']);
    }

    public function test_duplicate_period_payment_is_rejected(): void
    {
        MemberFeePayment::create([
            'member_id' => $this->member->id,
            'period' => '2026-27',
            'amount' => 250,
            'payment_method' => 'Cash',
            'paid_on' => '2026-07-15',
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/members/{$this->member->id}/fees", $this->paymentPayload());

        $response->assertSessionHasErrors('period');
        $this->assertSame(1, MemberFeePayment::where('member_id', $this->member->id)->count());
        $this->assertEquals(100.0, (float) $this->account->fresh()->current_balance);
    }

    public function test_inactive_account_is_rejected(): void
    {
        $this->account->update(['is_active' => false]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/members/{$this->member->id}/fees", $this->paymentPayload());

        $response->assertSessionHasErrors('financial_account_id');
        $this->assertSame(0, MemberFeePayment::count());
    }

    public function test_guest_cannot_record_fee_payment(): void
    {
        $response = $this->post("/admin/members/{$this->member->id}/fees", $this->paymentPayload());

        $response->assertRedirect('/admin/login');
        $this->assertSame(0, MemberFeePayment::count());
    }

    public function test_fees_page_shows_payment_status(): void
    {
        MemberFeePayment::create([
            'member_id' => $this->member->id,
            'period' => MemberFeePayment::periodFor(now()),
            'amount' => 250,
            'payment_method' => 'Cash',
            'paid_on' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/members/fees');

        $response->assertOk();
        $response->assertSee('Paid');
        $response->assertSee($this->member->id);
    }

    public function test_period_for_matches_july_to_june_membership_year(): void
    {
        $this->assertSame('2026-27', MemberFeePayment::periodFor(new \DateTimeImmutable('2026-08-15')));
        $this->assertSame('2025-26', MemberFeePayment::periodFor(new \DateTimeImmutable('2026-03-15')));
    }
}
