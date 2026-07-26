<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->enum('account_type', ['Asset', 'Liability', 'Income', 'Expense', 'Equity']);
            $table->decimal('current_balance', 12, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique(); // e.g. VOUCH-2026-0001
            $table->foreignId('financial_account_id')->constrained('financial_accounts')->onDelete('cascade');
            $table->enum('type', ['Income', 'Expense', 'Transfer']);
            $table->string('category'); // Donation, Fee, Medical Relief, Event, Hostel Help, Printing, Misc
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->string('payment_method')->default('UPI'); // UPI, Cash, Bank Transfer, Cheque
            $table->string('reference_no')->nullable();
            $table->string('payer_payee_name')->nullable();
            $table->text('narration');
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_path');
            $table->string('btn_text')->nullable();
            $table->string('btn_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nav_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url_or_route');
            $table->string('location')->default('header'); // header, footer
            $table->string('icon_class')->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('General'); // Admissions, PG Help, Emergency, Membership
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_title'); // Alumni / Student Batch 2022
            $table->string('college_name');
            $table->text('quote');
            $table->string('photo')->nullable();
            $table->integer('rating')->default(5);
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('member_id')->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('institution');
            $table->string('ticket_code')->unique();
            $table->boolean('is_attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();
        });

        Schema::create('medical_relief_claims', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->string('patient_name');
            $table->string('hospital_name'); // PGIMER / GMCH 32 / Fortis
            $table->string('nature_of_illness');
            $table->decimal('amount_requested', 10, 2);
            $table->decimal('amount_approved', 10, 2)->default(0.00);
            $table->enum('status', ['Pending', 'Approved', 'Disbursed', 'Rejected'])->default('Pending');
            $table->text('doctor_notes')->nullable();
            $table->string('medical_document')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // CREATE_MEMBER, APPROVE_MEMBER, LOG_TRANSACTION, UPDATE_SETTING
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('medical_relief_claims');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('nav_menu_items');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('financial_accounts');
    }
};
