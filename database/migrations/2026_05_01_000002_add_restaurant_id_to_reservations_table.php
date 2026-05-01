<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('restaurant_id')
                ->nullable()
                ->after('id')
                ->constrained('restaurants')
                ->nullOnDelete();
        });

        $existingRestaurantNames = DB::table('reservations')
            ->select('restaurant_name')
            ->whereNotNull('restaurant_name')
            ->where('restaurant_name', '<>', '')
            ->distinct()
            ->orderBy('restaurant_name')
            ->get();

        foreach ($existingRestaurantNames as $existingRestaurant) {
            $restaurantId = DB::table('restaurants')
                ->where('name', $existingRestaurant->restaurant_name)
                ->value('id');

            if (! $restaurantId) {
                $restaurantId = DB::table('restaurants')->insertGetId([
                    'name' => $existingRestaurant->restaurant_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('reservations')
                ->where('restaurant_name', $existingRestaurant->restaurant_name)
                ->whereNull('restaurant_id')
                ->update([
                    'restaurant_id' => $restaurantId,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropColumn('restaurant_id');
        });
    }
};