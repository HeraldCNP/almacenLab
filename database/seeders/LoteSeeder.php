<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Lote::create([
            'material_id' => 1, // Laptop Dell XPS 15
            'lote' => 'LT-XPS-001',
            'fecha_caducidad' => null,
            'proveedor_id' => 1, // Proveedor A
            'cantidad_inicial' => 5,
            'cantidad_disponible' => 5,
            'ubicacion_id' => 1, // Almacén Principal
        ]);

        \App\Models\Lote::create([
            'material_id' => 2, // Camiseta de Algodón
            'lote' => 'CM-ALG-001',
            'fecha_caducidad' => null,
            'proveedor_id' => 2, // Proveedor B
            'cantidad_inicial' => 50,
            'cantidad_disponible' => 50,
            'ubicacion_id' => 2, // Almacén Secundario
        ]);

        \App\Models\Lote::create([
            'material_id' => 3, // Arroz Basmati 1kg
            'lote' => 'AR-BAS-001',
            'fecha_caducidad' => '2026-07-20',
            'proveedor_id' => 3, // Proveedor C
            'cantidad_inicial' => 100,
            'cantidad_disponible' => 100,
            'ubicacion_id' => 1, // Almacén Principal
        ]);

        \App\Models\Lote::create([
            'material_id' => 4, // El Señor de los Anillos
            'lote' => 'SL-AN-001',
            'fecha_caducidad' => null,
            'proveedor_id' => 1, // Proveedor A
            'cantidad_inicial' => 10,
            'cantidad_disponible' => 10,
            'ubicacion_id' => 1, // Zona de Cuarentena
        ]);
    }
}
