<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Cultural');
            $table->date('date');
            $table->string('time')->nullable();
            $table->string('venue');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('status')->default('Upcoming'); // Upcoming, Ongoing, Completed
            $table->string('registration_link')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Notice');
            $table->date('date');
            $table->text('content');
            $table->string('author')->default('Executive Desk');
            $table->boolean('is_important')->default(false);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Events');
            $table->string('image_url');
            $table->date('date')->nullable();
            $table->text('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name')->default('Anonymous');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('INR');
            $table->string('cause');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('payment_ref');
            $table->string('status')->default('Completed');
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('Unread'); // Unread, Read, Resolved
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('news');
        Schema::dropIfExists('events');
    }
};
