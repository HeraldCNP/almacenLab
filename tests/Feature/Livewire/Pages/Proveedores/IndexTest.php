<?php

namespace Tests\Feature\Livewire\Pages\Proveedores;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_render_proveedores_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('proveedores.index'));

        $response->assertSeeLivewire('pages.proveedores.index');
        $response->assertStatus(200);
    }

    public function test_it_can_create_a_proveedor()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Volt::test('pages.proveedores.create')
            ->set('nombre_proveedor', 'Proveedor Test')
            ->set('contacto_proveedor', 'Juan Perez')
            ->set('email', 'juan@test.com')
            ->set('telefono', '123456789')
            ->call('save')
            ->assertRedirect(route('proveedores.index'));

        $this->assertDatabaseHas('proveedores', [
            'nombre_proveedor' => 'Proveedor Test',
            'email' => 'juan@test.com',
        ]);
    }

    public function test_it_can_edit_a_proveedor()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $proveedor = Proveedor::create([
            'nombre_proveedor' => 'Proveedor Original',
            'contacto_proveedor' => 'Contacto Original',
        ]);

        Volt::test('pages.proveedores.edit', ['proveedor' => $proveedor])
            ->set('nombre_proveedor', 'Proveedor Editado')
            ->call('update')
            ->assertRedirect(route('proveedores.index'));

        $this->assertDatabaseHas('proveedores', [
            'id' => $proveedor->id,
            'nombre_proveedor' => 'Proveedor Editado',
        ]);
    }
}
