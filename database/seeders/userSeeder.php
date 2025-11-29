<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::create([
            'name' => 'Herald',
            'email' => 'heraldcnp@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

        // Analyst User
        \App\Models\User::create([
            'name' => 'Analista',
            'email' => 'analista@lab.com',
            'password' => Hash::make('123'),
            'role' => 'user',
        ]);
    }
}
