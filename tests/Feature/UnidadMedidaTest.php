<?php

use App\Models\UnidadMedida;
use App\Models\Material;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a unit of measure', function () {
    $unidad = UnidadMedida::create([
        'nombre' => 'Kilogramo',
        'abreviatura' => 'kg',
    ]);

    $this->assertDatabaseHas('unidades_medida', [
        'nombre' => 'Kilogramo',
        'abreviatura' => 'kg',
    ]);
});

it('can assign a unit of measure to a material', function () {
    $unidad = UnidadMedida::create([
        'nombre' => 'Litro',
        'abreviatura' => 'L',
    ]);

    $categoria = Categoria::factory()->create();

    $material = Material::create([
        'nombre_material' => 'Leche',
        'categoria_id' => $categoria->id,
        'unidad_medida_id' => $unidad->id,
        'stock_minimo' => 10,
        'stock_actual' => 50,
    ]);

    $this->assertEquals($unidad->id, $material->unidadMedida->id);
    $this->assertEquals('Litro', $material->unidadMedida->nombre);
});
