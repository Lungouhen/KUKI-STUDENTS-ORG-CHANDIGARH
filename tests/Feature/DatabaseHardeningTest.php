<?php

namespace Tests\Feature;

use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_indexes_cover_high_volume_listing_and_reporting_paths(): void
    {
        $this->assertIndexExists('members', ['status', 'created_at']);
        $this->assertIndexExists('transactions', ['transaction_date', 'id']);
        $this->assertIndexExists('transactions', ['type', 'transaction_date']);
        $this->assertIndexExists('transactions', ['financial_account_id', 'transaction_date']);
        $this->assertIndexExists('transactions', ['target_account_id', 'transaction_date']);
        $this->assertIndexExists('transactions', ['term_id', 'transaction_date']);
        $this->assertIndexExists('events', ['status', 'date']);
        $this->assertIndexExists('events', ['category', 'date']);
        $this->assertIndexExists('news', ['is_member_post', 'date']);
        $this->assertIndexExists('election_votes', ['member_id']);
        $this->assertIndexExists('candidates', ['member_id']);
        $this->assertIndexExists('audit_logs', ['created_at']);
        $this->assertIndexExists('member_fee_payments', ['member_id', 'paid_on']);
        $this->assertIndexExists('contact_messages', ['status', 'created_at']);
    }

    public function test_transaction_term_reference_is_enforced_and_nullable_when_a_term_is_deleted(): void
    {
        $account = FinancialAccount::create([
            'account_code' => 'DB-001',
            'account_name' => 'Database Test Account',
            'account_type' => 'Asset',
        ]);

        $term = Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-07-01',
            'end_date' => '2027-06-30',
            'is_active' => false,
        ]);

        DB::table('transactions')->insert($this->transactionRow($account->id, $term->id));
        $term->delete();

        $this->assertDatabaseHas('transactions', [
            'voucher_no' => 'DB-TERM-VALID',
            'term_id' => null,
        ]);

        $this->expectException(QueryException::class);
        DB::table('transactions')->insert($this->transactionRow($account->id, 999999));
    }

    public function test_member_references_reject_orphaned_election_and_fee_records(): void
    {
        $term = Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-07-01',
            'end_date' => '2027-06-30',
            'is_active' => false,
        ]);
        $electionId = DB::table('elections')->insertGetId([
            'term_id' => $term->id,
            'position' => 'President',
            'election_date' => '2026-10-01',
            'status' => 'Scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertForeignKeyViolation(fn () => DB::table('election_votes')->insert([
            'election_id' => $electionId,
            'member_id' => 'MISSING-MEMBER',
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        $this->assertForeignKeyViolation(fn () => DB::table('member_fee_payments')->insert([
            'member_id' => 'MISSING-MEMBER',
            'period' => '2026-27',
            'amount' => 100,
            'payment_method' => 'Cash',
            'paid_on' => '2026-10-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        $this->assertForeignKeyViolation(fn () => DB::table('candidates')->insert([
            'election_id' => $electionId,
            'member_id' => 'MISSING-MEMBER',
            'votes_received' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    public function test_member_with_financial_or_election_history_cannot_be_deleted(): void
    {
        $admin = User::create([
            'name' => 'Database Admin',
            'email' => 'database-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        $member = $this->createMember();

        DB::table('member_fee_payments')->insert([
            'member_id' => $member->id,
            'period' => '2026-27',
            'amount' => 100,
            'payment_method' => 'Cash',
            'paid_on' => '2026-10-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.members.destroy', $member->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('members', ['id' => $member->id]);
    }

    private function assertIndexExists(string $table, array $columns): void
    {
        $indexes = Schema::getIndexes($table);

        $this->assertTrue(
            collect($indexes)->contains(fn (array $index) => $index['columns'] === $columns),
            "Expected an index on {$table} (".implode(', ', $columns).').',
        );
    }

    private function transactionRow(int $accountId, int $termId): array
    {
        return [
            'voucher_no' => 'DB-TERM-'.($termId === 999999 ? 'INVALID' : 'VALID'),
            'term_id' => $termId,
            'financial_account_id' => $accountId,
            'type' => 'Income',
            'category' => 'Test',
            'amount' => 10,
            'transaction_date' => '2026-10-01',
            'payment_method' => 'Cash',
            'narration' => 'Database hardening test',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function assertForeignKeyViolation(callable $insert): void
    {
        try {
            $insert();
        } catch (QueryException $exception) {
            $this->assertInstanceOf(QueryException::class, $exception);

            return;
        }

        $this->fail('Expected the database to reject a row with a missing referenced record.');
    }

    private function createMember(): Member
    {
        return Member::create([
            'id' => 'KSO-CHD-2026-9999',
            'full_name' => 'Database Test Member',
            'gender' => 'Female',
            'phone' => '+91 90000 00000',
            'email' => 'database-member@example.org',
            'blood_group' => 'A+',
            'institution' => 'Test College',
            'course' => 'BCom',
            'year_of_study' => '1st Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 90000 00001',
            'status' => 'Approved',
        ]);
    }
}
