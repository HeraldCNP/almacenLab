<?php

namespace Tests\Feature\Livewire\Pages\Lotes;

use App\Models\Lote;
use App\Models\Material;
use App\Models\User;
use App\Models\Categoria;
use App\Models\UnidadMedida;
use App\Models\Proveedor;
use App\Models\Ubicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AjusteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_ajuste_page()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $material = Material::factory()->create();
        $lote = Lote::factory()->create(['material_id' => $material->id]);

        $this->actingAs($user)
            ->get(route('lotes.ajuste', $lote))
            ->assertOk()
            ->assertSee('Ajuste de Inventario')
            ->assertSee($lote->lote);
    }

    public function test_can_increment_stock()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $material = Material::factory()->create(['stock_actual' => 10]);
        $lote = Lote::factory()->create([
            'material_id' => $material->id,
            'cantidad_inicial' => 10,
            'cantidad_disponible' => 10
        ]);

        Volt::actingAs($user)
            ->test('pages.lotes.ajuste', ['lote' => $lote])
            ->set('tipo_ajuste', 'incremento')
            ->set('cantidad', 5)
            ->set('motivo', 'Found extra items')
            ->call('registrarAjuste')
            ->assertRedirect(route('lotes.index'));

        $this->assertDatabaseHas('lotes', [
            'id' => $lote->id,
            'cantidad_disponible' => 15,
        ]);

        $this->assertDatabaseHas('materiales', [
            'id' => $material->id,
            'stock_actual' => 15,
        ]);

        $this->assertDatabaseHas('movimientos', [
            'lote_id' => $lote->id,
            'tipo' => 'AJUSTE',
            'cantidad' => 5,
            'motivo' => '[INCREMENTO] Found extra items',
        ]);
    }

    public function test_can_decrement_stock()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $material = Material::factory()->create(['stock_actual' => 10]);
        $lote = Lote::factory()->create([
            'material_id' => $material->id,
            'cantidad_inicial' => 10,
            'cantidad_disponible' => 10
        ]);

        $component = Volt::actingAs($user)
            ->test('pages.lotes.ajuste', ['lote' => $lote])
            ->set('tipo_ajuste', 'disminucion')
            ->set('cantidad', 3)
            ->set('motivo', 'Broken items')
            ->call('registrarAjuste');

        if (session()->has('error')) {
            dump(session('error'));
        }
        


        $this->assertDatabaseHas('movimientos', [
            'lote_id' => $lote->id,
            'tipo' => 'AJUSTE',
            'cantidad' => 3,
            'motivo' => '[DISMINUCIÓN] Broken items',
        ]);

        $this->assertDatabaseHas('lotes', [
            'id' => $lote->id,
            'cantidad_disponible' => 7,
        ]);

        $this->assertDatabaseHas('materiales', [
            'id' => $material->id,
            'stock_actual' => 7,
        ]);
        
        $component->assertRedirect(route('lotes.index'));
    }

    public function test_cannot_decrement_more_than_available()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $material = Material::factory()->create(['stock_actual' => 10]);
        $lote = Lote::factory()->create([
            'material_id' => $material->id,
            'cantidad_disponible' => 5
        ]);

        Volt::actingAs($user)
            ->test('pages.lotes.ajuste', ['lote' => $lote])
            ->set('tipo_ajuste', 'disminucion')
            ->set('cantidad', 10) // More than available 5
            ->set('motivo', 'Lost items')
            ->call('registrarAjuste')
            ->assertHasErrors(['cantidad']);
            
        // Assert stock hasn't changed
        $this->assertDatabaseHas('lotes', [
            'id' => $lote->id,
            'cantidad_disponible' => 5,
        ]);
    }
}
