<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Proveedor::create([
            'nombre_proveedor' => 'Proveedor A',
            'contacto_proveedor' => 'Juan Perez',
        ]);

        \App\Models\Proveedor::create([
            'nombre_proveedor' => 'Proveedor B',
            'contacto_proveedor' => 'Maria Garcia',
        ]);

        \App\Models\Proveedor::create([
            'nombre_proveedor' => 'Proveedor C',
            'contacto_proveedor' => 'Carlos Lopez',
        ]);
    }
}
