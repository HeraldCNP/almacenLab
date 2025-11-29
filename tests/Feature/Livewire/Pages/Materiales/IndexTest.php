<?php

use App\Models\User;
use App\Models\Material;
use App\Models\Categoria;
use App\Models\UnidadMedida;
use Livewire\Volt\Volt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->categoria = Categoria::factory()->create();
    $this->unidad = UnidadMedida::create(['nombre' => 'Litros', 'abreviatura' => 'L']);
});

it('can render materiales index page', function () {
    $this->actingAs($this->user);
    
    $component = Volt::test('pages.materiales.index');
    
    $component->assertSee('Materiales');
    $component->assertSee('Crear Nuevo');
});

it('can create a material', function () {
    $this->actingAs($this->user);

    Volt::test('pages.materiales.create')
        ->set('nombre_material', 'Ácido Sulfúrico')
        ->set('categoria_id', $this->categoria->id)
        ->set('unidad_medida_id', $this->unidad->id)
        ->set('stock_minimo', 5)
        ->call('save')
        ->assertRedirect(route('materiales.index'));

    $this->assertDatabaseHas('materiales', [
        'nombre_material' => 'Ácido Sulfúrico',
        'categoria_id' => $this->categoria->id,
        'unidad_medida_id' => $this->unidad->id,
    ]);
});

it('can edit a material', function () {
    $this->actingAs($this->user);
    $material = Material::create([
        'nombre_material' => 'Agua Destilada',
        'categoria_id' => $this->categoria->id,
        'unidad_medida_id' => $this->unidad->id,
        'stock_minimo' => 10,
        'stock_actual' => 0
    ]);

    Volt::test('pages.materiales.edit', ['material' => $material])
        ->set('nombre_material', 'Agua Bidestilada')
        ->set('stock_minimo', 20)
        ->call('update')
        ->assertRedirect(route('materiales.index'));

    $this->assertDatabaseHas('materiales', [
        'id' => $material->id,
        'nombre_material' => 'Agua Bidestilada',
        'stock_minimo' => 20,
    ]);
});
