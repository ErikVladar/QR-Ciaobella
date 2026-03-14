<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WaiterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'waiter@example.com';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Waiter',
                'email' => $email,
                'password' => Hash::make('Waiter123'),
                'role' => 'waiter',
            ]);
        } else {
            $user->role = 'waiter';
            $user->password = Hash::make('Waiter123');
            $user->save();
        }

        $this->command->info('Waiter user seeded: ' . $email . ' (password: Waiter123)');
    }
}
