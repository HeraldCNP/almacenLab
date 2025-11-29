<?php

namespace Tests\Feature\Livewire\Pages\Movimientos;

use App\Models\Lote;
use App\Models\Material;
use App\Models\Movimiento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class SalidaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_salida_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('movimientos.salida'))
            ->assertOk()
            ->assertSee('Registrar Salida de Material');
    }

    public function test_can_register_salida_and_decrement_stock()
    {
        $user = User::factory()->create();
        $material = Material::factory()->create(['stock_actual' => 100]);
        $lote = Lote::factory()->create([
            'material_id' => $material->id,
            'cantidad_inicial' => 50,
            'cantidad_disponible' => 50,
            'lote' => 'L-TEST-001',
        ]);

        Volt::actingAs($user)
            ->test('pages.movimientos.create-salida')
            ->set('material_id', $material->id)
            ->set('lote_id', $lote->id)
            ->set('cantidad', 10)
            ->set('motivo', 'Test Output')
            ->call('save')
            ->assertRedirect(route('movimientos.index'));

        // Verify Lote Stock
        $this->assertEquals(40, $lote->fresh()->cantidad_disponible);

        // Verify Material Stock
        $this->assertEquals(90, $material->fresh()->stock_actual);

        // Verify Movimiento Record
        $this->assertDatabaseHas('movimientos', [
            'lote_id' => $lote->id,
            'user_id' => $user->id,
            'tipo' => 'SALIDA',
            'cantidad' => 10,
            'motivo' => 'Test Output',
        ]);
    }

    public function test_cannot_register_salida_exceeding_stock()
    {
        $user = User::factory()->create();
        $material = Material::factory()->create(['stock_actual' => 10]);
        $lote = Lote::factory()->create([
            'material_id' => $material->id,
            'cantidad_inicial' => 10,
            'cantidad_disponible' => 10,
        ]);

        Volt::actingAs($user)
            ->test('pages.movimientos.create-salida')
            ->set('material_id', $material->id)
            ->set('lote_id', $lote->id)
            ->set('cantidad', 20) // Exceeds 10
            ->set('motivo', 'Overdraft')
            ->call('save')
            ->assertHasErrors(['cantidad']);

        // Verify Stock Unchanged
        $this->assertEquals(10, $lote->fresh()->cantidad_disponible);
    }
}
