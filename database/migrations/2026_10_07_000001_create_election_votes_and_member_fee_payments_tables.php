<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->string('member_id'); // Link to members table (string PK)
            $table->timestamps();

            // One secret ballot per member per election; no candidate stored.
            $table->unique(['election_id', 'member_id']);
        });

        Schema::create('member_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('member_id'); // Link to members table (string PK)
            $table->string('period'); // e.g. 2026-27 membership year
            $table->decimal('amount', 12, 2);
            $table->string('payment_method');
            $table->string('reference_no')->nullable();
            $table->string('voucher_no')->nullable(); // Ledger voucher for the income entry
            $table->date('paid_on');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // One fee payment per member per membership year.
            $table->unique(['member_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_fee_payments');
        Schema::dropIfExists('election_votes');
    }
};
