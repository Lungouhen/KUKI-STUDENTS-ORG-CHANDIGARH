<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Executive Terms
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 2025-2026
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Partners Management
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('type'); // donor, collaborator, sponsor
            $table->string('category'); // NGO, Corporate, Government
            $table->text('notes')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // Projects & Campaigns
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('budget', 15, 2)->default(0);
            $table->string('status')->default('Planned'); // Planned, Active, Completed
            $table->timestamps();
        });

        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('type'); // Individual, Family, Community
            $table->text('support_needed')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // Elections Module
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained();
            $table->string('position');
            $table->date('election_date');
            $table->string('status')->default('Scheduled');
            $table->timestamps();
        });

        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained();
            $table->string('member_id'); // Link to members table
            $table->integer('votes_received')->default(0);
            $table->timestamps();
        });

        // Expand Members table with more fields via a separate migration or modify here if allowed.
        // For MVP expansion, I'll add columns to members in a separate migration step or just update the main one if we are resetting.
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
        Schema::dropIfExists('elections');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('terms');
    }
};
