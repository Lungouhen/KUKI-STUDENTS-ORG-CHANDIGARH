<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `projects.id` is an auto-incrementing BIGINT and is referenced by
 * `beneficiaries.project_id` via a foreign key, so it cannot be turned into a
 * string. The human-readable identifier (PROJ-2026-0001) therefore lives in its
 * own column instead of being forced into the primary key.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_code')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['project_code']);
            $table->dropColumn('project_code');
        });
    }
};
