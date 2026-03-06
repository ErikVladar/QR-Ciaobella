<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // DatabaseSeeder.php
    public function run(): void
    {
        $this->call([
            KitchenUserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PizzaAdditionSeeder::class,
        ]);
    }
}
