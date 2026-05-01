<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('meta_key')->index();
            $table->string('meta_value');
            $table->timestamps();

            $table->unique(['meta_key', 'meta_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};