<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Ubicacion::create(['nombre_ubicacion' => 'Almacén Principal']);
        \App\Models\Ubicacion::create(['nombre_ubicacion' => 'Almacén Secundario']);
        \App\Models\Ubicacion::create(['nombre_ubicacion' => 'Zona de Cuarentena']);
    }
}
