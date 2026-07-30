<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('title');
            $table->string('image_url');
            $table->string('redirect_url')->nullable();
            $table->string('placement')->default('Portal_Sidebar'); // Portal_Sidebar, Feed_Banner, Public_Footer
            $table->string('status')->default('Active'); // Active, Paused
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('clicks_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
