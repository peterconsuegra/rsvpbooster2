<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('meta_pixel_id')->nullable();
            $table->text('meta_access_token')->nullable();
            $table->string('tiktok_pixel_id')->nullable();
            $table->text('tiktok_access_token')->nullable();
            $table->timestamps();

            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};