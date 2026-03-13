<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'Admin12345',
                'role' => 'admin',
            ]
        );

        $this->command?->info('Admin user seeded: admin@example.com (password: Admin12345)');
    }
}
