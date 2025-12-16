<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $busqueda = '';

    public function with()
    {
        $proveedores = Proveedor::query()
            ->when($this->busqueda, function ($query) {
                $query->where('nombre_proveedor', 'like', '%' . $this->busqueda . '%')
                      ->orWhere('contacto_proveedor', 'like', '%' . $this->busqueda . '%')
                      ->orWhere('email', 'like', '%' . $this->busqueda . '%');
            })
            ->latest()
            ->paginate(10);

        return [
            'proveedores' => $proveedores,
        ];
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function eliminarProveedor(int $proveedorId)
    {
        try {
            $proveedor = Proveedor::findOrFail($proveedorId);
            $proveedor->delete();
            session()->flash('success', 'Proveedor eliminado con éxito.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('warning', 'El proveedor no fue encontrado.');
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
                    {{ __('Proveedores') }}
                </h3>
                
                @can('manage-inventory')
                <a href="{{ route('proveedores.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Registrar Nuevo') }}
                </a>
                @endcan
            </div>

            {{-- Campo de Búsqueda --}}
            <div class="mb-4">
                <input wire:model.live="busqueda" type="text" placeholder="{{ __('Buscar proveedor...') }}"
                    class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
            </div>

            {{-- Tabla de Datos --}}
            <div class="overflow-x-auto w-full">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Empresa') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Contacto') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Email') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Teléfono') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Acciones') }} </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($proveedores as $proveedor)
                            <tr wire:key="proveedor-{{ $proveedor->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $proveedor->nombre_proveedor }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $proveedor->contacto_proveedor ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $proveedor->email ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $proveedor->telefono ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @can('manage-inventory')
                                        <flux:button icon="pencil-square" href="{{ route('proveedores.edit', $proveedor) }}" wire:navigate variant="ghost" size="sm" title="{{ __('Editar') }}" class="cursor-pointer" />
                                        <flux:button icon="trash" wire:click="eliminarProveedor({{ $proveedor->id }})" wire:confirm="{{ __('¿Estás seguro de que quieres eliminar este proveedor?') }}" variant="ghost" size="sm" class="text-red-500 hover:text-red-700 cursor-pointer" title="{{ __('Eliminar') }}" />
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No se encontraron proveedores.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $proveedores->links() }}
            </div>
        </div>
    </div>
</div>
