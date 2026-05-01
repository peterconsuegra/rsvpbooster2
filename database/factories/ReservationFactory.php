<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'reservation_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'reservation_time' => fake()->time('H:i'),
            'party_size' => fake()->numberBetween(1, 8),
            'restaurant_name' => fake()->company().' Bistro',
            'status' => fake()->randomElement(Reservation::STATUSES),
            'purchase_value' => fake()->randomFloat(2, 20, 300),
            'currency' => 'USD',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
