<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_material' => $this->faker->word(),
            'categoria_id' => \App\Models\Categoria::factory(),
            'unidad_medida_id' => \App\Models\UnidadMedida::factory(),
            'stock_minimo' => $this->faker->numberBetween(1, 10),
            'stock_actual' => 0,
        ];
    }
}
