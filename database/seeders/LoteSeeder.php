<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Lote;
use App\Models\Material;
use App\Models\Proveedor;
use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Lote::create([
            'material_id' => 1, // Laptop Dell XPS 15
            'lote' => 'LT-XPS-001',
            'fecha_caducidad' => null,
            'proveedor_id' => 1, // Proveedor A
            'cantidad_inicial' => 5,
            'cantidad_disponible' => 5,
            'ubicacion_id' => 1, // Almacén Principal
        ]);

        // Dynamic batch creation for existing materials
        $materiales = Material::all();
        $proveedores = Proveedor::all();
        $ubicaciones = Ubicacion::all();

        if ($materiales->isEmpty() || $proveedores->isEmpty() || $ubicaciones->isEmpty()) {
            // Log a warning or throw an exception if dependencies are not seeded
            // For now, just return to prevent errors
            return;
        }

        foreach ($materiales as $material) {
            // Create 1-2 batches per material
            $numLotes = rand(1, 2);

            for ($i = 0; $i < $numLotes; $i++) {
                $cantidad = rand(1, 14); // Quantity < 15
                $loteCode = 'L-' . now()->format('Ymd') . '-' . str_pad($material->id . $i, 3, '0', STR_PAD_LEFT);

                $lote = Lote::create([
                    'material_id' => $material->id,
                    'proveedor_id' => $proveedores->random()->id,
                    'ubicacion_id' => $ubicaciones->random()->id,
                    'lote' => $loteCode,
                    'fecha_caducidad' => now()->addMonths(rand(1, 24)),
                    'cantidad_inicial' => $cantidad,
                    'cantidad_disponible' => $cantidad,
                ]);

                // Update material stock
                $material->increment('stock_actual', $cantidad);
            }
        }

        // Example of a specific batch that might be needed
        \App\Models\Lote::create([
            'material_id' => 4, // El Señor de los Anillos
            'lote' => 'SL-AN-001',
            'fecha_caducidad' => null,
            'proveedor_id' => 1, // Proveedor A
            'cantidad_inicial' => 10,
            'cantidad_disponible' => 10,
            'ubicacion_id' => 1, // Zona de Cuarentena
        ]);
    }
}
