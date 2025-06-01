<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Cheese Burger', 'Chicken Pizza', 'Chicken Burger', 'Meet Pizza', 'Meet Burger']),
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id, // Random category
            'price' => $this->faker->randomFloat(2, 10, 100), // Generates a price between 10 and 100
            'description' => $this->faker->sentence(), // Short description
            'image' => $this->faker->imageUrl(300, 300, 'food'), // Random image URL
            'main_category' => $this->faker->randomElement(['Burger', 'Pizza']),
        ];
    }
}
