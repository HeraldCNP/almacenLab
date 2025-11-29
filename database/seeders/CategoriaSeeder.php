<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre_categoria' => 'Reactivos Ácidos'],
            ['nombre_categoria' => 'Reactivos Básicos'],
            ['nombre_categoria' => 'Sales y Óxidos'],
            ['nombre_categoria' => 'Solventes Orgánicos'],
            ['nombre_categoria' => 'Vidriería Volumétrica'],
            ['nombre_categoria' => 'Vidriería General'],
            ['nombre_categoria' => 'Equipos de Medición'],
            ['nombre_categoria' => 'EPP'],
            ['nombre_categoria' => 'Consumibles'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
