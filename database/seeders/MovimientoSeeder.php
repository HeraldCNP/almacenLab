<?php

namespace Database\Seeders;

use App\Models\Lote;
use App\Models\Movimiento;
use App\Models\User;
use Illuminate\Database\Seeder;

class MovimientoSeeder extends Seeder
{
    public function run(): void
    {
        $lotes = Lote::all();
        $user = User::first();

        if ($lotes->isEmpty() || !$user) {
            return;
        }

        // Create some movements for the first few batches
        foreach ($lotes->take(5) as $lote) {
            Movimiento::create([
                'lote_id' => $lote->id,
                'user_id' => $user->id,
                'tipo' => 'ENTRADA',
                'cantidad' => $lote->cantidad_inicial,
                'motivo' => 'Inventario Inicial',
                'fecha_movimiento' => now(),
            ]);

            // Simulate a small output if stock allows
            if ($lote->cantidad_disponible > 5) {
                Movimiento::create([
                    'lote_id' => $lote->id,
                    'user_id' => $user->id,
                    'tipo' => 'SALIDA',
                    'cantidad' => 2,
                    'motivo' => 'Consumo interno prueba',
                    'fecha_movimiento' => now()->addHours(2),
                ]);
                
                // Update stock to reflect the seeded movement
                $lote->decrement('cantidad_disponible', 2);
                $lote->material->decrement('stock_actual', 2);
            }
        }
    }
}
