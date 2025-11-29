<?php

use App\Models\User;
use App\Models\Categoria;
use Livewire\Volt\Volt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can render index page', function () {
    $this->actingAs($this->user);
    
    $component = Volt::test('pages.categorias.index');
    
    $component->assertSee('Categorías Existentes');
    $component->assertSee('Crear Nueva');
});

it('can create a category', function () {
    $this->actingAs($this->user);

    Volt::test('pages.categorias.create')
        ->set('nombre_categoria', 'Nueva Categoría')
        ->call('save')
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categorias', [
        'nombre_categoria' => 'Nueva Categoría',
    ]);
});

it('can edit a category', function () {
    $this->actingAs($this->user);
    $categoria = Categoria::factory()->create(['nombre_categoria' => 'Vieja Categoría']);

    Volt::test('pages.categorias.edit', ['categoria' => $categoria])
        ->set('nombre_categoria', 'Categoría Editada')
        ->call('update')
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categorias', [
        'id' => $categoria->id,
        'nombre_categoria' => 'Categoría Editada',
    ]);
});
