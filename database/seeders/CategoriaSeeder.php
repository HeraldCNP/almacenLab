<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Categoria::create(['nombre_categoria' => 'Electrónica']);
        \App\Models\Categoria::create(['nombre_categoria' => 'Ropa']);
        \App\Models\Categoria::create(['nombre_categoria' => 'Alimentos']);
        \App\Models\Categoria::create(['nombre_categoria' => 'Libros']);
    }
}
