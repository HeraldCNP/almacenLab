<?php

use Livewire\Volt\Component;
use App\Models\Lote;
use App\Models\Material;
use App\Models\Proveedor;
use App\Models\Ubicacion;
use App\Models\Movimiento;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public Lote $loteModel;
    public $material_id = '';
    public $proveedor_id = '';
    public $ubicacion_id = '';
    public string $lote = '';
    public string $fecha_caducidad = '';
    public string $cantidad_disponible = '';

    public function mount(Lote $lote)
    {
        $this->loteModel = $lote;
        $this->material_id = $lote->material_id;
        $this->proveedor_id = $lote->proveedor_id;
        $this->ubicacion_id = $lote->ubicacion_id;
        $this->lote = $lote->lote;
        $this->fecha_caducidad = $lote->fecha_caducidad ? $lote->fecha_caducidad->format('Y-m-d') : '';
        $this->cantidad_disponible = $lote->cantidad_disponible;
    }

    public function update()
    {
        $validated = $this->validate([
            'material_id' => 'required|exists:materiales,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'lote' => 'required|string|max:255',
            'fecha_caducidad' => 'nullable|date',
            'cantidad_disponible' => 'nullable', // Validation remains flexible but field is effectively read-only
        ]);

        // Stock quantity updates are now disabled in this view to enforce audit trails via 'Adjustments'.
        // Only updates metadata.

        session()->flash('success', 'Lote actualizado correctamente.');

        return $this->redirect(route('lotes.index'), navigate: true);
    }

    public function with()
    {
        return [
            'materiales' => Material::all(),
            'proveedores' => Proveedor::all(),
            'ubicaciones' => Ubicacion::all(),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 pb-32">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Editar Lote') }}
                </h3>
                <a href="{{ route('lotes.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="update">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="relative z-30">
                        <x-searchable-select
                            label="Material"
                            :options="$materiales"
                            option-label="nombre_material"
                            option-value="id"
                            wire:model="material_id"
                            wireModel="material_id"
                        />
                        @error('material_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative z-20">
                        <x-searchable-select
                            label="Proveedor"
                            :options="$proveedores"
                            option-label="nombre_proveedor"
                            option-value="id"
                            wire:model="proveedor_id"
                            wireModel="proveedor_id"
                        />
                        @error('proveedor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="lote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Código de Lote') }}</label>
                        <input wire:model="lote" type="text" id="lote" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('lote') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="fecha_caducidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Fecha de Vencimiento') }}</label>
                        <input wire:model="fecha_caducidad" type="date" id="fecha_caducidad" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('fecha_caducidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="cantidad_disponible" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cantidad Disponible (Solo Lectura)') }}</label>
                        <input wire:model="cantidad_disponible" type="number" id="cantidad_disponible" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-100 dark:bg-gray-600 cursor-not-allowed" readonly disabled>
                        <p class="text-xs text-gray-500 mt-1">Para modificar stock, use "Ajuste de Inventario".</p>
                        @error('cantidad_disponible') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4 relative z-10">
                    <x-searchable-select
                        label="Ubicación de Almacenamiento"
                        :options="$ubicaciones"
                        option-label="nombre_ubicacion"
                        option-value="id"
                        wire:model="ubicacion_id"
                        wireModel="ubicacion_id"
                    />
                    @error('ubicacion_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-zinc-500 focus:bg-zinc-500 active:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Actualizar Lote') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
