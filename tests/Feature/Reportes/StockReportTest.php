<?php

namespace Tests\Feature\Reportes;

use App\Models\Lote;
use App\Models\Material;
use App\Models\Categoria;
use App\Models\UnidadMedida;
use App\Models\Proveedor;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_generate_stock_report_with_prices()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $categoria = Categoria::create(['nombre_categoria' => 'Test Cat']);
        $unidad = UnidadMedida::create(['nombre' => 'Test Unit', 'abreviatura' => 'TU']);
        $proveedor = Proveedor::create(['nombre_proveedor' => 'Prov']);
        $ubicacion = Ubicacion::create(['nombre_ubicacion' => 'Ubi']);
        
        $material = Material::create([
            'nombre_material' => 'Material Test',
            'categoria_id' => $categoria->id,
            'unidad_medida_id' => $unidad->id,
            'stock_minimo' => 10,
            'stock_actual' => 0,
        ]);

        // Just create the Lote, the model logic/observer logic (if any) or shared service handles stock update? 
        // Or in this case the Lote create test handled it via code. 
        // Here we just want to verify the REPORT loads. 
        // Manually creating Lote.
        Lote::create([
            'material_id' => $material->id,
            'proveedor_id' => $proveedor->id,
            'ubicacion_id' => $ubicacion->id,
            'lote' => 'L1',
            'cantidad_inicial' => 10,
            'cantidad_disponible' => 10,
            'precio_compra' => 50.00,
        ]);

        $material->update(['stock_actual' => 10]);

        $response = $this->get(route('reportes.stock'));

        $response->assertStatus(200);
        // Assert header content type is PDF
        $response->assertHeader('content-type', 'application/pdf');
    }
}
