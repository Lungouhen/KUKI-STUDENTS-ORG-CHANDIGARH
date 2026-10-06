<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\FinancialAccount;
use App\Models\MedicalReliefClaim;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MedicalReliefWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private FinancialAccount $reliefFund;
    private MedicalReliefClaim $claim;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Medical Admin',
            'email' => 'medical-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->reliefFund = FinancialAccount::create([
            'account_code' => '1002',
            'account_name' => 'Emergency Relief Fund',
            'account_type' => 'Asset',
            'current_balance' => 500,
            'is_active' => true,
        ]);

        $this->claim = MedicalReliefClaim::create([
            'member_id' => 'KSO-TEST-001',
            'patient_name' => 'Test Patient',
            'hospital_name' => 'Test Hospital',
            'nature_of_illness' => 'Test illness',
            'amount_requested' => 300,
            'amount_approved' => 0,
            'status' => 'Pending',
        ]);
    }

    public function test_approval_creates_one_ledger_expense_and_disbursement_does_not_duplicate_it(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.medical.updateStatus', $this->claim), [
                'status' => 'Approved',
                'amount_approved' => '250.00',
            ])
            ->assertRedirect();

        $this->assertSame('Approved', $this->claim->fresh()->status);
        $this->assertSame('250.00', $this->claim->fresh()->amount_approved);
        $this->assertEquals(250, (float) $this->reliefFund->fresh()->current_balance);
        $this->assertDatabaseHas('transactions', [
            'type' => 'Expense',
            'category' => 'Medical Relief',
            'amount' => '250.00',
            'reference_no' => 'MED-CLAIM-' . $this->claim->id,
        ]);
        $this->assertSame(1, Transaction::count());

        $this->post(route('admin.medical.updateStatus', $this->claim), [
            'status' => 'Approved',
            'amount_approved' => '250.00',
        ])->assertRedirect();

        $this->assertSame(1, Transaction::count());
        $this->assertEquals(250, (float) $this->reliefFund->fresh()->current_balance);

        $this->post(route('admin.medical.updateStatus', $this->claim), [
            'status' => 'Disbursed',
            'amount_approved' => '250.00',
        ])->assertRedirect();

        $this->assertSame('Disbursed', $this->claim->fresh()->status);
        $this->assertSame(1, Transaction::count());
        $this->assertEquals(250, (float) $this->reliefFund->fresh()->current_balance);
    }

    public function test_amount_above_request_and_invalid_status_transition_do_not_change_claim_or_ledger(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.medical.updateStatus', $this->claim), [
                'status' => 'Approved',
                'amount_approved' => '300.01',
            ])
            ->assertSessionHasErrors('amount_approved');

        $this->post(route('admin.medical.updateStatus', $this->claim), [
            'status' => 'Disbursed',
            'amount_approved' => '200',
        ])->assertSessionHasErrors('status');

        $this->assertSame('Pending', $this->claim->fresh()->status);
        $this->assertEquals(0, (float) $this->claim->fresh()->amount_approved);
        $this->assertSame(0, Transaction::count());
        $this->assertEquals(500, (float) $this->reliefFund->fresh()->current_balance);
    }

    public function test_missing_relief_fund_does_not_save_an_approval(): void
    {
        $this->reliefFund->delete();

        $this->actingAs($this->admin)
            ->post(route('admin.medical.updateStatus', $this->claim), [
                'status' => 'Approved',
                'amount_approved' => '250',
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame('Pending', $this->claim->fresh()->status);
        $this->assertSame(0, Transaction::count());
        $this->assertSame(0, AuditLog::count());
    }

    public function test_failed_audit_write_rolls_back_claim_voucher_and_balance(): void
    {
        DB::statement("
            CREATE TRIGGER fail_medical_audit_insert
            BEFORE INSERT ON audit_logs
            BEGIN
                SELECT RAISE(ABORT, 'Audit log unavailable');
            END
        ");

        $this->actingAs($this->admin)
            ->post(route('admin.medical.updateStatus', $this->claim), [
                'status' => 'Approved',
                'amount_approved' => '250',
            ])
            ->assertServerError();

        $this->assertSame('Pending', $this->claim->fresh()->status);
        $this->assertEquals(0, (float) $this->claim->fresh()->amount_approved);
        $this->assertSame(0, Transaction::count());
        $this->assertEquals(500, (float) $this->reliefFund->fresh()->current_balance);
    }

    public function test_non_admin_cannot_change_a_claim(): void
    {
        $this->post(route('admin.medical.updateStatus', $this->claim), [
            'status' => 'Approved',
            'amount_approved' => '250',
        ])->assertRedirect(route('admin.login'));

        $this->assertSame('Pending', $this->claim->fresh()->status);
        $this->assertSame(0, Transaction::count());
    }
}
