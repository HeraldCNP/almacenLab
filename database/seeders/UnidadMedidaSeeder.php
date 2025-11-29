<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            ['nombre' => 'Mililitro', 'abreviatura' => 'ml'],
            ['nombre' => 'Litro', 'abreviatura' => 'L'],
            ['nombre' => 'Gramo', 'abreviatura' => 'g'],
            ['nombre' => 'Kilogramo', 'abreviatura' => 'kg'],
            ['nombre' => 'Miligramo', 'abreviatura' => 'mg'],
            ['nombre' => 'Unidad', 'abreviatura' => 'und'],
            ['nombre' => 'Caja', 'abreviatura' => 'cja'],
            ['nombre' => 'Botella', 'abreviatura' => 'bot'],
            ['nombre' => 'Galón', 'abreviatura' => 'gal'],
        ];

        foreach ($unidades as $unidad) {
            UnidadMedida::create($unidad);
        }
    }
}
