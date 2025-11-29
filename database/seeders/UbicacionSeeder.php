<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ubicaciones = [
            ['nombre_ubicacion' => 'Armario de Ácidos', 'descripcion' => 'Almacenamiento ventilado para ácidos'],
            ['nombre_ubicacion' => 'Estante Vidriería A', 'descripcion' => 'Vidriería limpia y seca'],
            ['nombre_ubicacion' => 'Refrigerador 1 (4°C)', 'descripcion' => 'Reactivos termosensibles'],
            ['nombre_ubicacion' => 'Bodega General', 'descripcion' => 'Insumos y equipos grandes'],
            ['nombre_ubicacion' => 'Gabinete de Inflamables', 'descripcion' => 'Solventes orgánicos'],
        ];

        foreach ($ubicaciones as $ubicacion) {
            Ubicacion::create($ubicacion);
        }
    }
}
