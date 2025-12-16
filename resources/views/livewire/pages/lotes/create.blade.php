<?php

use Livewire\Volt\Component;
use App\Models\Lote;
use App\Models\Material;
use App\Models\Proveedor;
use App\Models\Ubicacion;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public $material_id = '';
    public $proveedor_id = '';
    public $ubicacion_id = '';
    public string $lote = '';
    public string $fecha_caducidad = '';
    public string $cantidad_inicial = '';

    public function mount()
    {
        $this->generateLoteCode();
    }

    public function generateLoteCode()
    {
        $prefix = 'L-' . now()->format('Ymd');
        $count = Lote::where('lote', 'like', $prefix . '%')->count() + 1;
        $this->lote = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function save()
    {
        $validated = $this->validate([
            'material_id' => 'required|exists:materiales,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'lote' => 'required|string|max:255',
            'fecha_caducidad' => 'nullable|date',
            'cantidad_inicial' => 'required|integer|min:1',
        ]);

        $validated['cantidad_disponible'] = $validated['cantidad_inicial'];
        $validated['fecha_caducidad'] = $validated['fecha_caducidad'] ?: null;

        Lote::create($validated);

        // Actualizar stock total del material
        $material = Material::find($this->material_id);
        $material->increment('stock_actual', $validated['cantidad_inicial']);

        session()->flash('success', 'Lote registrado exitosamente.');

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
                    {{ __('Registrar Entrada de Lote') }}
                </h3>
                <a href="{{ route('lotes.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="save">
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
                        <div class="flex gap-2">
                            <input wire:model="lote" type="text" id="lote" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly>
                            <button type="button" wire:click="generateLoteCode" class="mt-1 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 cursor-pointer transition" title="Generar nuevo código">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </button>
                        </div>
                        @error('lote') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="fecha_caducidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Fecha de Vencimiento') }}</label>
                        <input wire:model="fecha_caducidad" type="date" id="fecha_caducidad" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('fecha_caducidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="cantidad_inicial" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cantidad Recibida') }}</label>
                        <input wire:model="cantidad_inicial" type="number" id="cantidad_inicial" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('cantidad_inicial') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Registrar Lote') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
