<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Material;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        // Helper to get ID by name
        $cat = fn($name) => Categoria::where('nombre_categoria', $name)->first()->id ?? Categoria::first()->id;
        $und = fn($name) => UnidadMedida::where('nombre', $name)->first()->id ?? UnidadMedida::first()->id;

        $materiales = [
            // Reactivos Ácidos
            ['codigo' => 'ACD-001', 'nombre_material' => 'Ácido Sulfúrico 98%', 'categoria_id' => $cat('Reactivos Ácidos'), 'unidad_medida_id' => $und('Litro'), 'stock_minimo' => 2],
            ['codigo' => 'ACD-002', 'nombre_material' => 'Ácido Clorhídrico 37%', 'categoria_id' => $cat('Reactivos Ácidos'), 'unidad_medida_id' => $und('Litro'), 'stock_minimo' => 2],
            ['codigo' => 'ACD-003', 'nombre_material' => 'Ácido Nítrico 65%', 'categoria_id' => $cat('Reactivos Ácidos'), 'unidad_medida_id' => $und('Litro'), 'stock_minimo' => 1],

            // Reactivos Básicos
            ['codigo' => 'BAS-001', 'nombre_material' => 'Hidróxido de Sodio (Lentejas)', 'categoria_id' => $cat('Reactivos Básicos'), 'unidad_medida_id' => $und('Kilogramo'), 'stock_minimo' => 1],
            ['codigo' => 'BAS-002', 'nombre_material' => 'Amoníaco 25%', 'categoria_id' => $cat('Reactivos Básicos'), 'unidad_medida_id' => $und('Litro'), 'stock_minimo' => 1],

            // Solventes
            ['codigo' => 'SOL-001', 'nombre_material' => 'Acetona', 'categoria_id' => $cat('Solventes Orgánicos'), 'unidad_medida_id' => $und('Litro'), 'stock_minimo' => 5],
            ['codigo' => 'SOL-002', 'nombre_material' => 'Etanol 96%', 'categoria_id' => $cat('Solventes Orgánicos'), 'unidad_medida_id' => $und('Litro'), 'stock_minimo' => 5],

            // Vidriería
            ['codigo' => 'VID-001', 'nombre_material' => 'Matraz Erlenmeyer 250ml', 'categoria_id' => $cat('Vidriería Volumétrica'), 'unidad_medida_id' => $und('Unidad'), 'stock_minimo' => 10],
            ['codigo' => 'VID-002', 'nombre_material' => 'Vaso Precipitado 100ml', 'categoria_id' => $cat('Vidriería General'), 'unidad_medida_id' => $und('Unidad'), 'stock_minimo' => 10],
            ['codigo' => 'VID-003', 'nombre_material' => 'Pipeta Graduada 10ml', 'categoria_id' => $cat('Vidriería Volumétrica'), 'unidad_medida_id' => $und('Unidad'), 'stock_minimo' => 5],

            // EPP
            ['codigo' => 'EPP-001', 'nombre_material' => 'Guantes de Nitrilo (Caja)', 'categoria_id' => $cat('EPP'), 'unidad_medida_id' => $und('Caja'), 'stock_minimo' => 3],
            ['codigo' => 'EPP-002', 'nombre_material' => 'Lentes de Seguridad', 'categoria_id' => $cat('EPP'), 'unidad_medida_id' => $und('Unidad'), 'stock_minimo' => 5],
        ];

        foreach ($materiales as $mat) {
            Material::create([
                'codigo' => $mat['codigo'],
                'nombre_material' => $mat['nombre_material'],
                'categoria_id' => $mat['categoria_id'],
                'unidad_medida_id' => $mat['unidad_medida_id'],
                'stock_minimo' => $mat['stock_minimo'],
                'stock_actual' => 0, // Inicialmente 0, se llena con Lotes
            ]);
        }
    }
}
