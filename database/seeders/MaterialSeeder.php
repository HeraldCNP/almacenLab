<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Material::create([
            'nombre_material' => 'Laptop Dell XPS 15',
            'categoria_id' => 1, // Electrónica
            'stock_minimo' => 5,
            'stock_actual' => 10,
        ]);

        \App\Models\Material::create([
            'nombre_material' => 'Camiseta de Algodón',
            'categoria_id' => 2, // Ropa
            'stock_minimo' => 50,
            'stock_actual' => 120,
        ]);

        \App\Models\Material::create([
            'nombre_material' => 'Arroz Basmati 1kg',
            'categoria_id' => 3, // Alimentos
            'stock_minimo' => 100,
            'stock_actual' => 250,
        ]);

        \App\Models\Material::create([
            'nombre_material' => 'El Señor de los Anillos',
            'categoria_id' => 4, // Libros
            'stock_minimo' => 10,
            'stock_actual' => 30,
        ]);
    }
}
