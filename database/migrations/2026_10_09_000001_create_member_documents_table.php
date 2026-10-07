<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_documents', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->foreign('member_id')->references('id')->on('members')->restrictOnDelete();
            $table->string('document_type', 50);
            $table->text('purpose');
            $table->text('document_details')->nullable();
            $table->json('member_snapshot')->nullable();
            $table->string('certificate_number', 80)->nullable()->unique();
            $table->string('status', 20)->default('pending')->index();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('issued_by_name')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_documents');
    }
};
