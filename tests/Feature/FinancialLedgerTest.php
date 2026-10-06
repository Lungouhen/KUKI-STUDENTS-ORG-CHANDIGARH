<?php

namespace Tests\Feature;

use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\Term;
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
    private FinancialAccount $destination;

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

        $this->destination = FinancialAccount::create([
            'account_code' => 'BANK-001',
            'account_name' => 'Bank Account',
            'account_type' => 'Asset',
            'current_balance' => 20,
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

        $this->post(route('admin.financial.storeTransaction'), $this->voucher('Transfer', '7.50', $this->destination->id))
            ->assertRedirect();
        $this->assertEquals(107.75, (float) $this->account->fresh()->current_balance);
        $this->assertEquals(27.50, (float) $this->destination->fresh()->current_balance);
        $this->assertSame(3, Transaction::count());
        $this->assertSame($this->destination->id, Transaction::where('type', 'Transfer')->value('target_account_id'));
    }

    public function test_transfer_requires_a_distinct_destination_account(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.financial.storeTransaction'), $this->voucher('Transfer', '10.00'))
            ->assertSessionHasErrors('target_account_id');

        $this->post(route('admin.financial.storeTransaction'), $this->voucher('Transfer', '10.00', $this->account->id))
            ->assertSessionHasErrors('target_account_id');

        $this->assertSame(0, Transaction::count());
        $this->assertEquals(100, (float) $this->account->fresh()->current_balance);
        $this->assertEquals(20, (float) $this->destination->fresh()->current_balance);
    }

    public function test_vouchers_cannot_be_posted_to_inactive_accounts(): void
    {
        $this->account->update(['is_active' => false]);

        $this->actingAs($this->admin)
            ->post(route('admin.financial.storeTransaction'), $this->voucher('Income', '25.50'))
            ->assertSessionHasErrors('financial_account_id');

        $this->assertSame(0, Transaction::count());
        $this->assertEquals(100, (float) $this->account->fresh()->current_balance);
    }

    public function test_transfers_cannot_be_posted_to_inactive_destination_accounts(): void
    {
        $this->destination->update(['is_active' => false]);

        $this->actingAs($this->admin)
            ->post(route('admin.financial.storeTransaction'), $this->voucher('Transfer', '10.00', $this->destination->id))
            ->assertSessionHasErrors('financial_account_id');

        $this->assertSame(0, Transaction::count());
        $this->assertEquals(100, (float) $this->account->fresh()->current_balance);
        $this->assertEquals(20, (float) $this->destination->fresh()->current_balance);
    }

    public function test_voucher_amount_must_fit_ledger_precision(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.financial.storeTransaction'), $this->voucher('Income', '10.001'))
            ->assertSessionHasErrors('amount');

        $this->assertSame(0, Transaction::count());
        $this->assertEquals(100, (float) $this->account->fresh()->current_balance);
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

    public function test_financial_summary_only_counts_current_term_transactions(): void
    {
        Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
            'is_active' => true,
        ]);

        $this->createTransaction('Income', '300.00', '2026-05-01');
        $this->createTransaction('Expense', '75.00', '2026-05-02');
        $this->createTransaction('Income', '900.00', '2025-03-31');
        $this->createTransaction('Transfer', '40.00', '2026-05-03');

        $this->actingAs($this->admin)
            ->get(route('admin.financial.index'))
            ->assertOk()
            ->assertSee('2026-2027')
            ->assertSee('Term Income')
            ->assertSee('300.00')
            ->assertSee('75.00')
            ->assertDontSee('All-time Income');
    }

    public function test_financial_summary_is_labeled_all_time_without_an_active_term(): void
    {
        $this->createTransaction('Income', '125.00', '2024-03-31');

        $this->actingAs($this->admin)
            ->get(route('admin.financial.index'))
            ->assertOk()
            ->assertSee('All time')
            ->assertSee('All-time Income')
            ->assertSee('125.00');
    }

    public function test_transaction_report_filters_by_date_type_and_either_transfer_account(): void
    {
        $this->createTransaction('Income', '125.00', '2026-05-01');
        $transfer = $this->createTransaction('Transfer', '40.00', '2026-05-10');
        $transfer->update(['target_account_id' => $this->destination->id]);
        $this->createTransaction('Expense', '20.00', '2026-06-01');
        $this->createTransaction('Income', '900.00', '2025-05-01');

        $this->actingAs($this->admin)
            ->get(route('admin.financial.index', [
                'from' => '2026-05-01',
                'to' => '2026-05-31',
                'account_id' => $this->destination->id,
            ]))
            ->assertOk()
            ->assertSee($transfer->voucher_no)
            ->assertDontSee('2026-06-01')
            ->assertDontSee('2025-05-01')
            ->assertSee('Transfer · 1 entries')
            ->assertSee('₹40.00');
    }

    public function test_filtered_csv_export_matches_report_scope_and_neutralizes_formula_cells(): void
    {
        $matching = $this->createTransaction('Expense', '20.00', '2026-05-10');
        $matching->update([
            'payer_payee_name' => '=HYPERLINK("https://example.org")',
            'narration' => '@SUM(1,1)',
        ]);
        $this->createTransaction('Income', '900.00', '2025-05-01');

        $response = $this->actingAs($this->admin)->get(route('admin.financial.export', [
            'type' => 'Expense',
            'from' => '2026-05-01',
            'to' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString(
            'financial-transactions-' . now()->format('Y-m-d') . '.csv',
            $response->headers->get('content-disposition', '')
        );

        $csv = $response->streamedContent();
        $this->assertStringContainsString($matching->voucher_no, $csv);
        $this->assertStringContainsString("'=HYPERLINK", $csv);
        $this->assertStringContainsString("'@SUM", $csv);
        $this->assertStringNotContainsString('900.00', $csv);
    }

    public function test_transaction_report_rejects_invalid_filters_and_requires_admin_access(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.financial.index', [
                'from' => '2026-06-01',
                'to' => '2026-05-01',
            ]))
            ->assertSessionHasErrors('to');

        $this->get(route('admin.financial.export'))
            ->assertRedirect(route('admin.login'));
    }

    private function createTransaction(string $type, string $amount, string $date): Transaction
    {
        return Transaction::create([
            'voucher_no' => 'TEST-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'financial_account_id' => $this->account->id,
            'type' => $type,
            'category' => 'Test',
            'amount' => $amount,
            'transaction_date' => $date,
            'payment_method' => 'UPI',
            'narration' => 'Test report entry',
        ]);
    }

    private function voucher(string $type, string $amount, ?int $targetAccountId = null): array
    {
        return [
            'financial_account_id' => $this->account->id,
            'type' => $type,
            'target_account_id' => $targetAccountId,
            'category' => 'Membership Fee',
            'amount' => $amount,
            'transaction_date' => '2026-10-06',
            'payment_method' => 'UPI',
            'narration' => 'Test ledger entry',
        ];
    }
}
