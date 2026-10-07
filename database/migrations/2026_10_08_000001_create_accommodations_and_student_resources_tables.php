<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('Co-Living PG');
            $table->string('location');
            $table->string('landmark')->nullable();
            $table->decimal('rent_monthly', 10, 2);
            $table->text('description')->nullable();
            $table->string('contact_phone');
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('student_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Institutional Guides');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->boolean('is_member_post')->default(false)->after('is_important');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('is_member_post');
        });
        Schema::dropIfExists('student_resources');
        Schema::dropIfExists('accommodations');
    }
};
