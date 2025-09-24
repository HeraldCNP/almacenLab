<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Movimiento::create([
            'lote_id' => 1, // LT-XPS-001
            'user_id' => 1, // Assuming a user with ID 1 exists
            'tipo_movimiento' => 'entrada',
            'cantidad_movimiento' => 5,
            'observaciones' => 'Entrada inicial de laptops',
        ]);

        \App\Models\Movimiento::create([
            'lote_id' => 2, // CM-ALG-001
            'user_id' => 1, // Assuming a user with ID 1 exists
            'tipo_movimiento' => 'salida',
            'cantidad_movimiento' => 10,
            'observaciones' => 'Salida para venta minorista',
        ]);

        \App\Models\Movimiento::create([
            'lote_id' => 3, // AR-BAS-001
            'user_id' => 1, // Assuming a user with ID 1 exists
            'tipo_movimiento' => 'entrada',
            'cantidad_movimiento' => 100,
            'observaciones' => 'Recepción de nuevo stock de arroz',
        ]);
    }
}
