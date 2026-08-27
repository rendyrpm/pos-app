<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Cashier user
        \App\Models\User::updateOrCreate(
            ['email' => 'kasir@pos.com'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'role' => 'cashier',
            ]
        );
    }
}
