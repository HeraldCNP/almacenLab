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
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'heraldcnp@gmail.com'],
            [
                'name' => 'Herald',
                'password' => Hash::make('123'),
            ]
        );
        $admin->assignRole('Administrador');

        // Technical Direction User
        $tecnica = \App\Models\User::firstOrCreate(
            ['email' => 'tecnica@lab.com'],
            [
                'name' => 'Dirección Técnica',
                'password' => Hash::make('123'),
            ]
        );
        $tecnica->assignRole('Dirección Técnica');

        // Operator User
        $analista = \App\Models\User::firstOrCreate(
            ['email' => 'analista@lab.com'],
            [
                'name' => 'Analista',
                'password' => Hash::make('123'),
            ]
        );
        $analista->assignRole('Operador');
    }
}
