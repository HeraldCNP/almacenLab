<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // El campo que estamos probando:
            'nombre_categoria' => $this->faker->unique()->word().' '.$this->faker->numberBetween(1, 5),
            // El uso de ->unique() asegura que los nombres generados por el Factory no choquen
            // con la restricción UNIQUE de la base de datos si creamos múltiples categorías.
            // Es importante, ya que tu campo de la DB es unique.
        ];
    }
}
