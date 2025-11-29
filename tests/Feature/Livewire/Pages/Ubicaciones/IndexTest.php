<?php

namespace Tests\Feature\Livewire\Pages\Ubicaciones;

use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_render_ubicaciones_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('ubicaciones.index'));

        $response->assertSeeLivewire('pages.ubicaciones.index');
        $response->assertStatus(200);
    }

    public function test_it_can_create_an_ubicacion()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Volt::test('pages.ubicaciones.create')
            ->set('nombre_ubicacion', 'Estante A')
            ->set('descripcion', 'Estante principal')
            ->call('save')
            ->assertRedirect(route('ubicaciones.index'));

        $this->assertDatabaseHas('ubicaciones', [
            'nombre_ubicacion' => 'Estante A',
            'descripcion' => 'Estante principal',
        ]);
    }

    public function test_it_can_edit_an_ubicacion()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ubicacion = Ubicacion::create([
            'nombre_ubicacion' => 'Ubicacion Original',
            'descripcion' => 'Descripcion Original',
        ]);

        Volt::test('pages.ubicaciones.edit', ['ubicacion' => $ubicacion])
            ->set('nombre_ubicacion', 'Ubicacion Editada')
            ->call('update')
            ->assertRedirect(route('ubicaciones.index'));

        $this->assertDatabaseHas('ubicaciones', [
            'id' => $ubicacion->id,
            'nombre_ubicacion' => 'Ubicacion Editada',
        ]);
    }
}
