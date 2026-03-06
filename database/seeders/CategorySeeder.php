<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pizza', 'image_path' => 'imgs/pizza.png'],
            ['name' => 'Drinky', 'image_path' => 'imgs/drinks.png'],
            ['name' => 'Dezerty', 'image_path' => 'imgs/desserts.png'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
