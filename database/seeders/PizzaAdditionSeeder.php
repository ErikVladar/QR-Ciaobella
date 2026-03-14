<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PizzaAddition;

class PizzaAdditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $additions = [
            ['name' => 'Extra Cheese', 'price' => 1.50],
            ['name' => 'Pepperoni', 'price' => 2.00],
            ['name' => 'Mushrooms', 'price' => 1.00],
            ['name' => 'Olives', 'price' => 1.25],
        ];

        foreach ($additions as $addition) {
            PizzaAddition::create($addition);
        }

        $this->command->info('Pizza additions seeded successfully.');
    }
}
