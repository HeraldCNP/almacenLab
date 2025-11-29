<?php

namespace Tests\Feature\Livewire\Categorias;

use App\Models\Categoria; // Importante
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rule;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GestionCategoriasTest extends TestCase
{
    // Usa RefreshDatabase para asegurar una base de datos limpia en cada prueba
    use RefreshDatabase;

    protected User $usuario;

    // Método que se ejecuta antes de cada prueba
    protected function setUp(): void
    {
        parent::setUp();
        // Creamos un usuario para simular la autenticación
        $this->usuario = User::factory()->create();
    }

    /** @test */
    public function puede_renderizar_la_pagina_de_gestion_de_categorias()
    {
        // Verificación de acceso y renderizado del componente Volt
        $this->actingAs($this->usuario)
            ->get(route('categorias.index'))
            ->assertSuccessful()
            ->assertSeeVolt('pages.categorias.gestion-categorias');
    }

    // ----------------------------------------------------------------------
    // Bloque de pruebas para la CREACIÓN de Categorías (C)
    // ----------------------------------------------------------------------

    /** @test */
    public function puede_crear_una_nueva_categoria()
    {
        Volt::actingAs($this->usuario)
            ->test('pages.categorias.gestion-categorias')
            ->set('nombre_categoria', 'Material de Consumo')
            ->call('guardarCategoria')
            ->assertHasNoErrors()
            ->assertSet('nombre_categoria', '')
            ->assertSee('Categoría creada con éxito');

        $this->assertDatabaseHas('categorias', [
            'nombre_categoria' => 'Material de Consumo',
        ]);
    }

    /** @test */
    public function falla_al_crear_categoria_con_datos_invalidos()
    {
        // 1. Caso: Nombre vacío (required)
        Volt::actingAs($this->usuario)
            ->test('pages.categorias.gestion-categorias')
            ->set('nombre_categoria', '')
            ->call('guardarCategoria')
            ->assertHasErrors(['nombre_categoria' => 'required']);

        // 2. Caso: Nombre duplicado (unique)
        Categoria::factory()->create(['nombre_categoria' => 'Reactivos']);

        Volt::actingAs($this->usuario)
            ->test('pages.categorias.gestion-categorias')
            ->set('nombre_categoria', 'Reactivos') // Nombre duplicado
            ->call('guardarCategoria')
            ->assertHasErrors(['nombre_categoria' => 'unique']);

        // Se espera que solo exista la categoría creada inicialmente (1 registro)
        $this->assertDatabaseCount('categorias', 1);
    }

    /** @test */
    public function puede_editar_una_categoria_existente()
    {
        // 1. Crear una categoría inicial
        $categoria = Categoria::factory()->create(['nombre_categoria' => 'Nombre Antiguo']);

        Volt::actingAs($this->usuario)
            ->test('pages.categorias.gestion-categorias')

            // 2. Simular clic en editar: Esto prepara el modal y carga datos.
            ->call('prepararEdicion', $categoria)
            ->assertSet('mostrarModalEdicion', true) // Verifica que el modal se abre
            ->assertSet('nombre_edicion', 'Nombre Antiguo') // Verifica que los datos se precargan

            // 3. Modificar los datos y llamar al método de actualización
            ->set('nombre_edicion', 'Nombre Actualizado')
            ->call('actualizarCategoria')

            // 4. Aserciones del componente
            ->assertHasNoErrors()
            ->assertSet('mostrarModalEdicion', false) // El modal debe cerrarse
            ->assertSee('Categoría actualizada con éxito');

        // 5. Aserción de la Base de Datos: El nombre antiguo debe desaparecer, el nuevo debe existir.
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre_categoria' => 'Nombre Actualizado',
        ]);
        $this->assertDatabaseMissing('categorias', [
            'nombre_categoria' => 'Nombre Antiguo',
        ]);
    }

    /** @test */
    public function edicion_falla_si_se_usa_un_nombre_duplicado_de_otra_categoria()
    {
        // 1. Crear dos categorías
        Categoria::factory()->create(['nombre_categoria' => 'Nombre Duplicado']);
        $categoriaAEditar = Categoria::factory()->create(['nombre_categoria' => 'Original']);

        Volt::actingAs($this->usuario)
            ->test('pages.categorias.gestion-categorias')

            // 2. Preparamos la edición para 'Original'
            ->call('prepararEdicion', $categoriaAEditar)

            // 3. Intentamos cambiar 'Original' por 'Nombre Duplicado'
            ->set('nombre_edicion', 'Nombre Duplicado')
            ->call('actualizarCategoria')

            // 4. Aserción de Fallo: Debe fallar por la regla 'unique'
            ->assertHasErrors(['nombre_edicion' => Rule::unique('categorias', 'nombre_categoria')]);

        // 5. Aserción de la Base de Datos: La categoría debe mantener su nombre original.
        $this->assertDatabaseHas('categorias', [
            'id' => $categoriaAEditar->id,
            'nombre_categoria' => 'Original',
        ]);
    }

    /** @test */
    public function puede_eliminar_una_categoria()
    {
        // 1. Crear una categoría que será eliminada
        $categoria = Categoria::factory()->create(['nombre_categoria' => 'A Eliminar']);

        // 2. Ejecutar la acción de eliminación
        Volt::actingAs($this->usuario)
            ->test('pages.categorias.gestion-categorias')
            ->call('eliminarCategoria', $categoria->id)
            ->assertSee('Categoría eliminada con éxito.');

        // 3. Aserción de la Base de Datos: El registro NO debe existir.
        $this->assertDatabaseMissing('categorias', [
            'id' => $categoria->id,
            'nombre_categoria' => 'A Eliminar',
        ]);
    }
}
