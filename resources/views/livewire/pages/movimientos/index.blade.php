<?php

use Livewire\Volt\Component;
use App\Models\Movimiento;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public function with()
    {
        return [
            'movimientos' => Movimiento::with(['lote.material', 'user'])
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    {{ __('Movimientos de Inventario') }}
                </h2>
                <a href="{{ route('movimientos.salida') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Registrar Salida') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Material / Lote</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($movimientos as $movimiento)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $classes = match($movimiento->tipo) {
                                            'ENTRADA' => 'bg-green-600 text-white border border-green-700 dark:bg-green-500',
                                            'SALIDA' => 'bg-red-600 text-white border border-red-700 dark:bg-red-500',
                                            'AJUSTE' => 'bg-yellow-500 text-white border border-yellow-600 dark:bg-yellow-400 dark:text-yellow-900',
                                            default => 'bg-gray-500 text-white border border-gray-600',
                                        };
                                        $icon = match($movimiento->tipo) {
                                            'ENTRADA' => 'arrow-down-tray',
                                            'SALIDA' => 'arrow-up-tray',
                                            'AJUSTE' => 'adjustments-horizontal',
                                            default => 'minus',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $classes }}">
                                        <flux:icon name="{{ $icon }}" class="w-3 h-3 mr-1" />
                                        {{ $movimiento->tipo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="font-medium">{{ $movimiento->lote->material->nombre_material }}</div>
                                    <div class="text-xs text-gray-500">{{ $movimiento->lote->lote }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-bold text-right">
                                    {{ $movimiento->cantidad }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movimiento->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movimiento->motivo }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $movimientos->links() }}
            </div>
        </div>
    </div>
</div>
