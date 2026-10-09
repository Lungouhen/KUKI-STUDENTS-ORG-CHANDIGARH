<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->enum('field_type', ['text', 'textarea', 'number', 'date', 'select', 'radio', 'checkbox']);
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('member_custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->unsignedBigInteger('field_id');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
            $table->foreign('field_id')->references('id')->on('member_custom_fields')->cascadeOnDelete();
            $table->unique(['member_id', 'field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_custom_field_values');
        Schema::dropIfExists('member_custom_fields');
    }
};
