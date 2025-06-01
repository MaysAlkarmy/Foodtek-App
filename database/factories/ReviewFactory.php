<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\User;
use App\Models\Item;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>User::inRandomOrder()->first()->id, // Random user
            'item_id' => Item::inRandomOrder()->first()->id, // Random item
            'rating' => $this->faker->numberBetween(1, 5), // Random rating between 1 and 5
            'review' => $this->faker->randomElement(['Good', 'very good', 'excellent']),

        ];
    }
}
