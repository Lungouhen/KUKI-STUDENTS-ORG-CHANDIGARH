<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('publication_status')->default('published');
            $table->dateTime('scheduled_publish_at')->nullable();
            $table->json('sections')->nullable();
        });

        DB::table('pages')
            ->where('is_published', false)
            ->update(['publication_status' => 'draft']);

        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->json('snapshot');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['page_id', 'version']);
        });

        DB::table('pages')->orderBy('id')->chunkById(100, function ($pages) {
            $now = now();
            $revisions = $pages->map(function ($page) use ($now) {
                return [
                    'page_id' => $page->id,
                    'version' => 1,
                    'snapshot' => json_encode([
                        'title' => $page->title,
                        'excerpt' => $page->excerpt,
                        'content' => $page->content,
                        'template' => $page->template,
                        'featured_image' => $page->featured_image,
                        'meta_title' => $page->meta_title,
                        'meta_description' => $page->meta_description,
                        'sections' => null,
                    ]),
                    'changed_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all();

            DB::table('page_revisions')->insert($revisions);
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();
            $table->string('original_name');
            $table->string('alt_text', 255);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('page_revisions');

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['publication_status', 'scheduled_publish_at', 'sections']);
        });
    }
};
