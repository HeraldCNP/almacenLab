<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lote>
 */
class LoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_id' => \App\Models\Material::factory(),
            'proveedor_id' => \App\Models\Proveedor::factory(),
            'ubicacion_id' => \App\Models\Ubicacion::factory(),
            'lote' => $this->faker->unique()->bothify('L-####'),
            'fecha_caducidad' => $this->faker->date(),
            'cantidad_inicial' => $this->faker->numberBetween(10, 100),
            'cantidad_disponible' => $this->faker->numberBetween(10, 100),
        ];
    }
}
