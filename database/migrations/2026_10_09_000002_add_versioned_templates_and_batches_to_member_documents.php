<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 50);
            $table->unsignedInteger('version');
            $table->string('title', 150);
            $table->text('statement');
            $table->string('style', 30)->default('classic');
            $table->boolean('is_active')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['document_type', 'version']);
            $table->index(['document_type', 'is_active']);
        });

        $now = now();
        foreach (config('member_documents.types', []) as $type => $definition) {
            DB::table('member_document_templates')->insert([
                'document_type' => $type,
                'version' => 1,
                'title' => $definition['label'],
                'statement' => $definition['statement'],
                'style' => 'classic',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::create('member_document_batches', function (Blueprint $table) {
            $table->id();
            $table->string('idempotency_key', 36)->unique();
            $table->string('document_type', 50);
            $table->foreignId('template_id')->nullable()->constrained('member_document_templates')->nullOnDelete();
            $table->unsignedInteger('template_version')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('processing')->index();
            $table->text('shared_details');
            $table->json('criteria')->nullable();
            $table->unsignedInteger('recipient_count')->default(0);
            $table->unsignedInteger('issued_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('member_document_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('member_document_batches')->cascadeOnDelete();
            $table->string('member_id');
            $table->foreign('member_id')->references('id')->on('members')->restrictOnDelete();
            $table->foreignId('member_document_id')->nullable()->constrained('member_documents')->nullOnDelete();
            $table->string('status', 20)->index();
            $table->string('message', 500)->nullable();
            $table->timestamps();
            $table->unique(['batch_id', 'member_id']);
        });

        Schema::table('member_documents', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->constrained('member_document_templates')->nullOnDelete();
            $table->unsignedInteger('template_version')->nullable();
            $table->json('content_snapshot')->nullable();
            $table->foreignId('batch_id')->nullable()->constrained('member_document_batches')->nullOnDelete();
            $table->string('notification_status', 20)->default('not_sent')->index();
            $table->timestamp('notification_sent_at')->nullable();
            $table->index(['document_type', 'status', 'issued_at']);
            $table->index(['batch_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::table('member_documents', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropForeign(['batch_id']);
            $table->dropIndex(['document_type', 'status', 'issued_at']);
            $table->dropIndex(['batch_id', 'member_id']);
            $table->dropColumn([
                'template_id',
                'template_version',
                'content_snapshot',
                'batch_id',
                'notification_status',
                'notification_sent_at',
            ]);
        });

        Schema::dropIfExists('member_document_batch_items');
        Schema::dropIfExists('member_document_batches');
        Schema::dropIfExists('member_document_templates');
    }
};
