<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $item = Item::inRandomOrder()->first(); // Get a random item
        $quantity = $this->faker->numberBetween(1, 5); // Random quantity
        $price = $item->price; // Get the item's price
        $totalPrice = $quantity * $price; // Calculate total price

        return [
            'user_id' => User::inRandomOrder()->first()->id, // Random user ID
            'item_id' => $item->id, // Random item ID
            'quantity' => $quantity,
            'price' => $price,
            'total_price' => $totalPrice, // Total price based on quantity
        ];
    }
}
