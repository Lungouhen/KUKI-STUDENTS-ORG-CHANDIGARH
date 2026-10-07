<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('medical_relief_claims');
    }

    public function down(): void
    {
        Schema::create('medical_relief_claims', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->string('patient_name');
            $table->string('hospital_name');
            $table->string('nature_of_illness');
            $table->decimal('amount_requested', 10, 2);
            $table->decimal('amount_approved', 10, 2)->default(0.00);
            $table->enum('status', ['Pending', 'Approved', 'Disbursed', 'Rejected'])->default('Pending');
            $table->text('doctor_notes')->nullable();
            $table->string('medical_document')->nullable();
            $table->timestamps();
        });
    }
};
