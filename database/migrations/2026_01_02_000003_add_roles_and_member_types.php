<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('designation')->nullable()->after('full_name');
            $table->string('membership_category')->default('Individual'); // Individual, Family
            $table->integer('family_count')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('member')->after('is_admin');
            // secretary, finance_secretary, education_secretary, president, member, admin
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('term_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['designation', 'membership_category', 'family_count', 'is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('term_id');
        });
    }
};
