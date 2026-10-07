<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->assertNoOrphans('transactions', 'term_id', 'terms', true);
        $this->assertNoOrphans('election_votes', 'member_id', 'members');
        $this->assertNoOrphans('member_fee_payments', 'member_id', 'members');
        $this->assertNoOrphans('candidates', 'member_id', 'members');

        Schema::table('members', function (Blueprint $table) {
            $table->index('created_at', 'members_created_at_index');
            $table->index(['status', 'created_at'], 'members_status_created_at_index');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['transaction_date', 'id'], 'transactions_date_id_index');
            $table->index(['type', 'transaction_date'], 'transactions_type_date_index');
            $table->index(['financial_account_id', 'transaction_date'], 'transactions_account_date_index');
            $table->index(['target_account_id', 'transaction_date'], 'transactions_target_account_date_index');
            $table->index(['term_id', 'transaction_date'], 'transactions_term_date_index');
            $table->foreign('term_id', 'transactions_term_id_foreign')
                ->references('id')
                ->on('terms')
                ->nullOnDelete();
        });

        Schema::table('election_votes', function (Blueprint $table) {
            $table->index('member_id', 'election_votes_member_id_index');
            $table->foreign('member_id', 'election_votes_member_id_foreign')
                ->references('id')
                ->on('members')
                ->restrictOnDelete();
        });

        Schema::table('member_fee_payments', function (Blueprint $table) {
            $table->foreign('member_id', 'member_fee_payments_member_id_foreign')
                ->references('id')
                ->on('members')
                ->restrictOnDelete();
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->index('member_id', 'candidates_member_id_index');
            $table->foreign('member_id', 'candidates_member_id_foreign')
                ->references('id')
                ->on('members')
                ->restrictOnDelete();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index('date', 'events_date_index');
            $table->index(['status', 'date'], 'events_status_date_index');
            $table->index(['category', 'date'], 'events_category_date_index');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->index('date', 'news_date_index');
            $table->index(['is_member_post', 'date'], 'news_member_post_date_index');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('created_at', 'audit_logs_created_at_index');
        });

        Schema::table('member_fee_payments', function (Blueprint $table) {
            $table->index(['member_id', 'paid_on'], 'member_fee_payments_member_paid_on_index');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('created_at', 'contact_messages_created_at_index');
            $table->index(['status', 'created_at'], 'contact_messages_status_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex('contact_messages_status_created_at_index');
            $table->dropIndex('contact_messages_created_at_index');
        });

        Schema::table('member_fee_payments', function (Blueprint $table) {
            $table->dropIndex('member_fee_payments_member_paid_on_index');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_created_at_index');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('news_member_post_date_index');
            $table->dropIndex('news_date_index');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_category_date_index');
            $table->dropIndex('events_status_date_index');
            $table->dropIndex('events_date_index');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['term_id']);
            $table->dropIndex('transactions_term_date_index');
            $table->dropIndex('transactions_target_account_date_index');
            $table->dropIndex('transactions_account_date_index');
            $table->dropIndex('transactions_type_date_index');
            $table->dropIndex('transactions_date_id_index');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropIndex('candidates_member_id_index');
        });

        Schema::table('member_fee_payments', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
        });

        Schema::table('election_votes', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropIndex('election_votes_member_id_index');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('members_status_created_at_index');
            $table->dropIndex('members_created_at_index');
        });
    }

    private function assertNoOrphans(
        string $table,
        string $column,
        string $referencedTable,
        bool $nullable = false,
    ): void {
        $query = DB::table($table);

        if ($nullable) {
            $query->whereNotNull($column);
        }

        $hasOrphans = $query
            ->whereNotExists(function ($subquery) use ($table, $column, $referencedTable) {
                $subquery->selectRaw('1')
                    ->from($referencedTable)
                    ->whereColumn("{$referencedTable}.id", "{$table}.{$column}");
            })
            ->exists();

        if ($hasOrphans) {
            throw new RuntimeException(
                "Cannot enforce {$table}.{$column} -> {$referencedTable}.id: orphaned rows exist. Resolve them before retrying this migration."
            );
        }
    }
};
