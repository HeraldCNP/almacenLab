<?php

use Livewire\Volt\Component;
use App\Models\Material;
use App\Models\Lote;
use App\Models\Movimiento;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public function with()
    {
        return [
            'stockBajo' => Material::whereColumn('stock_actual', '<=', 'stock_minimo')->take(5)->get(),
            'lotesPorVencer' => Lote::where('fecha_caducidad', '<=', now()->addDays(30))
                ->where('cantidad_disponible', '>', 0)
                ->orderBy('fecha_caducidad')
                ->take(5)
                ->get(),
            'movimientosRecientes' => Movimiento::with(['user', 'lote.material'])
                ->latest()
                ->take(5)
                ->get(),
            'totalMateriales' => Material::count(),
            'totalLotes' => Lote::where('cantidad_disponible', '>', 0)->count(),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        {{-- Key Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 flex items-center justify-between">
                <div>
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">Total Materiales</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMateriales }}</div>
                </div>
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300">
                    <flux:icon.beaker class="w-8 h-8" />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 flex items-center justify-between">
                <div>
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">Lotes Activos</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalLotes }}</div>
                </div>
                <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-300">
                    <flux:icon.archive-box class="w-8 h-8" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Low Stock Alerts --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
                    <h3 class="text-lg font-medium text-red-800 dark:text-red-400 flex items-center gap-2">
                        <flux:icon.exclamation-triangle class="w-5 h-5" />
                        {{ __('Alertas de Stock Bajo') }}
                    </h3>
                </div>
                <div class="p-6">
                    @if($stockBajo->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No hay materiales con stock bajo.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($stockBajo as $material)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $material->nombre_material }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $material->stock_minimo }} | Actual: {{ $material->stock_actual }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Expiring Batches --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-yellow-50 dark:bg-yellow-900/20">
                    <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-400 flex items-center gap-2">
                        <flux:icon.clock class="w-5 h-5" />
                        {{ __('Lotes por Vencer (30 días)') }}
                    </h3>
                </div>
                <div class="p-6">
                    @if($lotesPorVencer->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No hay lotes próximos a vencer.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($lotesPorVencer as $lote)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lote->material->nombre_material }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Lote: {{ $lote->lote }} | Vence: {{ $lote->fecha_caducidad->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        {{ $lote->fecha_caducidad->diffForHumans() }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Movements --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <flux:icon.arrows-right-left class="w-5 h-5" />
                    {{ __('Movimientos Recientes') }}
                </h3>
            </div>
            <div class="p-6">
                @if($movimientosRecientes->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No hay movimientos registrados.') }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Material</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cantidad</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Usuario</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($movimientosRecientes as $mov)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $mov->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            @php
                                                $badgeClass = match($mov->tipo) {
                                                    'ENTRADA' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'SALIDA' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'AJUSTE' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                                {{ $mov->tipo }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $mov->lote->material->nombre_material }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $mov->cantidad }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $mov->user->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
