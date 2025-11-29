<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Lote;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $busqueda = '';

    public function with()
    {
        $lotes = Lote::query()
            ->with(['material', 'proveedor', 'ubicacion'])
            ->when($this->busqueda, function ($query) {
                $query->where('lote', 'like', '%' . $this->busqueda . '%')
                      ->orWhereHas('material', function ($q) {
                          $q->where('nombre_material', 'like', '%' . $this->busqueda . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        return [
            'lotes' => $lotes,
        ];
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function eliminarLote(int $loteId)
    {
        try {
            $lote = Lote::findOrFail($loteId);
            $lote->delete();
            session()->flash('success', 'Lote eliminado con éxito.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('warning', 'El lote no fue encontrado.');
        }
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Mensajes de Sesión --}}
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-700 dark:border-green-600 dark:text-green-100 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('warning') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Inventario (Lotes)') }}
                </h3>
                
                @can('manage-inventory')
                <a href="{{ route('lotes.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-zinc-500 focus:bg-zinc-500 active:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Registrar Entrada') }}
                </a>
                @endcan
            </div>

            {{-- Campo de Búsqueda --}}
            <div class="mb-4">
                <input wire:model.live="busqueda" type="text" placeholder="{{ __('Buscar por material o código de lote...') }}"
                    class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
            </div>

            {{-- Tabla de Datos --}}
            <div class="overflow-x-auto w-full">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Material') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Lote') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Vencimiento') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Cantidad') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Ubicación') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Acciones') }} </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($lotes as $lote)
                            <tr wire:key="lote-{{ $lote->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $lote->material->nombre_material }}
                                    <div class="text-xs text-gray-500">{{ $lote->proveedor->nombre_proveedor }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $lote->lote }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if($lote->fecha_caducidad)
                                        <span class="{{ $lote->fecha_caducidad->isPast() ? 'text-red-600 font-bold' : ($lote->fecha_caducidad->diffInDays(now()) < 30 ? 'text-yellow-600 font-bold' : '') }}">
                                            {{ $lote->fecha_caducidad->format('d/m/Y') }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $lote->cantidad_disponible }} / {{ $lote->cantidad_inicial }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $lote->ubicacion->nombre_ubicacion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @can('manage-inventory')
                                        <flux:button icon="scale" href="{{ route('lotes.ajuste', $lote) }}" wire:navigate variant="ghost" size="sm" title="{{ __('Ajustar Stock') }}" class="cursor-pointer text-blue-600 hover:text-blue-800" />
                                        <flux:button icon="pencil-square" href="{{ route('lotes.edit', $lote) }}" wire:navigate variant="ghost" size="sm" title="{{ __('Editar') }}" class="cursor-pointer" />
                                        <flux:button icon="trash" wire:click="eliminarLote({{ $lote->id }})" wire:confirm="{{ __('¿Estás seguro de que quieres eliminar este lote?') }}" variant="ghost" size="sm" class="text-red-500 hover:text-red-700 cursor-pointer" title="{{ __('Eliminar') }}" />
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No se encontraron lotes registrados.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $lotes->links() }}
            </div>
        </div>
    </div>
</div>
