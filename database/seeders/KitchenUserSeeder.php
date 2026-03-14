<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KitchenUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'kitchen@example.com';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Kitchen',
                'email' => $email,
                'password' => 'Kitchen123',
                'role' => 'kitchen',
            ]);
        } else {
            // Ensure role is kitchen and password set (if you want to keep existing password, remove the next line)
            $user->role = 'kitchen';
            $user->password = $user->password ?: Hash::make('secret');
            $user->save();
        }

        // Output to console when running db:seed
        $this->command->info('Kitchen user seeded: ' . $email . ' (password: secret)');
    }
}
