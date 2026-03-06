<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // For each category, create 5 products
        Category::all()->each(function ($category) {
            Product::factory()->count(5)->create([
                'category_id' => $category->id
            ]);
        });
    }
}
