<?php

namespace Tests\Feature;

use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FinancialLedgerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private FinancialAccount $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Finance Admin',
            'email' => 'finance-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->account = FinancialAccount::create([
            'account_code' => 'CASH-001',
            'account_name' => 'Cash Account',
            'account_type' => 'Asset',
            'current_balance' => 100,
            'is_active' => true,
        ]);
    }

    public function test_voucher_types_apply_the_expected_account_balance_changes(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('admin.financial.storeTransaction'), $this->voucher('Income', '25.50'))
            ->assertRedirect();
        $this->assertEquals(125.50, (float) $this->account->fresh()->current_balance);

        $this->post(route('admin.financial.storeTransaction'), $this->voucher('Expense', '10.25'))
            ->assertRedirect();
        $this->assertEquals(115.25, (float) $this->account->fresh()->current_balance);

        $this->post(route('admin.financial.storeTransaction'), $this->voucher('Transfer', '7.50'))
            ->assertRedirect();
        $this->assertEquals(115.25, (float) $this->account->fresh()->current_balance);
        $this->assertSame(3, Transaction::count());
    }

    public function test_failed_audit_write_rolls_back_voucher_and_balance_update(): void
    {
        DB::statement("
            CREATE TRIGGER fail_audit_log_insert
            BEFORE INSERT ON audit_logs
            BEGIN
                SELECT RAISE(ABORT, 'Audit log unavailable');
            END
        ");

        $this->actingAs($this->admin)
            ->post(route('admin.financial.storeTransaction'), $this->voucher('Income', '25.50'))
            ->assertServerError();

        $this->assertSame(0, Transaction::count());
        $this->assertEquals(100.00, (float) $this->account->fresh()->current_balance);
    }

    private function voucher(string $type, string $amount): array
    {
        return [
            'financial_account_id' => $this->account->id,
            'type' => $type,
            'category' => 'Membership Fee',
            'amount' => $amount,
            'transaction_date' => '2026-10-06',
            'payment_method' => 'UPI',
            'narration' => 'Test ledger entry',
        ];
    }
}
