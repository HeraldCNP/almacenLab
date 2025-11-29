<?php

use App\Models\User;
use App\Models\UnidadMedida;
use Livewire\Volt\Volt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can render unidades index page', function () {
    $this->actingAs($this->user);
    
    $component = Volt::test('pages.unidades.index');
    
    $component->assertSee('Unidades de Medida');
    $component->assertSee('Crear Nueva');
});

it('can create a unit', function () {
    $this->actingAs($this->user);

    Volt::test('pages.unidades.create')
        ->set('nombre', 'Litros')
        ->set('abreviatura', 'L')
        ->call('save')
        ->assertRedirect(route('unidades.index'));

    $this->assertDatabaseHas('unidades_medida', [
        'nombre' => 'Litros',
        'abreviatura' => 'L',
    ]);
});

it('can edit a unit', function () {
    $this->actingAs($this->user);
    $unidad = UnidadMedida::create(['nombre' => 'Metros', 'abreviatura' => 'm']);

    Volt::test('pages.unidades.edit', ['unidad' => $unidad])
        ->set('nombre', 'Kilometros')
        ->set('abreviatura', 'km')
        ->call('update')
        ->assertRedirect(route('unidades.index'));

    $this->assertDatabaseHas('unidades_medida', [
        'id' => $unidad->id,
        'nombre' => 'Kilometros',
        'abreviatura' => 'km',
    ]);
});
