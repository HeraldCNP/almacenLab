<?php

namespace Tests\Feature\Livewire\Pages\Lotes;

use App\Models\Lote;
use App\Models\Material;
use App\Models\Proveedor;
use App\Models\Ubicacion;
use App\Models\UnidadMedida;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_render_lotes_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('lotes.index'));

        $response->assertSeeLivewire('pages.lotes.index');
        $response->assertStatus(200);
    }

    public function test_it_can_create_a_lote_and_update_material_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $categoria = Categoria::create(['nombre_categoria' => 'Test Cat']);
        $unidad = UnidadMedida::create(['nombre' => 'Test Unit', 'abreviatura' => 'TU']);
        
        $material = Material::create([
            'nombre_material' => 'Material Test',
            'categoria_id' => $categoria->id,
            'unidad_medida_id' => $unidad->id,
            'stock_minimo' => 10,
            'stock_actual' => 0,
        ]);

        $proveedor = Proveedor::create(['nombre_proveedor' => 'Proveedor Test']);
        $ubicacion = Ubicacion::create(['nombre_ubicacion' => 'Ubicacion Test']);

        Volt::test('pages.lotes.create')
            ->set('material_id', $material->id)
            ->set('proveedor_id', $proveedor->id)
            ->set('ubicacion_id', $ubicacion->id)
            ->set('lote', 'LOTE-123')
            ->set('fecha_caducidad', now()->addYear()->format('Y-m-d'))
            ->set('cantidad_inicial', 100)
            ->call('save')
            ->assertRedirect(route('lotes.index'));

        $this->assertDatabaseHas('lotes', [
            'lote' => 'LOTE-123',
            'cantidad_inicial' => 100,
            'cantidad_disponible' => 100,
        ]);

        // Verify stock update
        $this->assertEquals(100, $material->fresh()->stock_actual);
    }

    public function test_it_can_edit_a_lote_and_adjust_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $categoria = Categoria::create(['nombre_categoria' => 'Test Cat']);
        $unidad = UnidadMedida::create(['nombre' => 'Test Unit', 'abreviatura' => 'TU']);
        
        $material = Material::create([
            'nombre_material' => 'Material Test',
            'categoria_id' => $categoria->id,
            'unidad_medida_id' => $unidad->id,
            'stock_minimo' => 10,
            'stock_actual' => 100,
        ]);

        $proveedor = Proveedor::create(['nombre_proveedor' => 'Proveedor Test']);
        $ubicacion = Ubicacion::create(['nombre_ubicacion' => 'Ubicacion Test']);

        $lote = Lote::create([
            'material_id' => $material->id,
            'proveedor_id' => $proveedor->id,
            'ubicacion_id' => $ubicacion->id,
            'lote' => 'LOTE-ORIGINAL',
            'cantidad_inicial' => 100,
            'cantidad_disponible' => 100,
        ]);

        Volt::test('pages.lotes.edit', ['lote' => $lote])
            ->set('lote', 'LOTE-EDITADO')
            ->set('cantidad_disponible', 80) // Used 20
            ->call('update')
            ->assertRedirect(route('lotes.index'));

        $this->assertDatabaseHas('lotes', [
            'id' => $lote->id,
            'lote' => 'LOTE-EDITADO',
            'cantidad_disponible' => 80,
        ]);

        // Verify stock adjustment (100 - 20 = 80)
        // Note: The logic in edit.blade.php adjusts stock based on difference.
        // New (80) - Old (100) = -20. Stock (100) - 20 = 80.
        $this->assertEquals(80, $material->fresh()->stock_actual);
    }
}
