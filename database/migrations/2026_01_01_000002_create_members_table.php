<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. KSO-CHD-2026-0001
            $table->string('full_name');
            $table->string('gender')->default('Male');
            $table->date('dob')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('blood_group')->default('O+');
            $table->string('institution');
            $table->string('course');
            $table->string('department')->nullable();
            $table->string('year_of_study');
            $table->string('roll_no')->nullable();
            $table->text('permanent_address');
            $table->text('current_address');
            $table->string('emergency_contact');
            $table->string('emergency_phone');
            $table->string('photo')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->string('membership_type')->default('Regular Student Member');
            $table->date('applied_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('valid_until')->default('2027-06-30');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
