<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('is_volunteer')->default(false)->after('is_active');
            $table->string('occupation')->nullable()->after('year_of_study');
        });

        // For Volunteers specifically if needed separate
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->text('skills')->nullable();
            $table->text('availability')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['is_volunteer', 'occupation']);
        });
        Schema::dropIfExists('volunteers');
    }
};
