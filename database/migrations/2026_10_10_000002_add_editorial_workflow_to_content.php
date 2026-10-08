<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['news', 'events', 'general_contents'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->string('publication_status')->default('published');
                $table->dateTime('scheduled_publish_at')->nullable();
                $table->index(
                    ['publication_status', 'scheduled_publish_at'],
                    substr($tableName, 0, 3) . '_publication_schedule_index'
                );
            });
        }

        DB::table('general_contents')
            ->where('is_published', false)
            ->update(['publication_status' => 'draft']);
    }

    public function down(): void
    {
        foreach (['news', 'events', 'general_contents'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropIndex(substr($tableName, 0, 3) . '_publication_schedule_index');
                $table->dropColumn(['publication_status', 'scheduled_publish_at']);
            });
        }
    }
};
