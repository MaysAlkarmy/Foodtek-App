<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
      User::factory()->count(10)->create();
      Category::factory()->count(10)->create();
      Item::factory()->count(10)->create();
      Review::factory()->count(10)->create();
      Cart::factory()->count(10)->create();
    }
}
