<?php

use Livewire\Volt\Component;
use App\Models\Material;
use App\Models\Categoria;
use App\Models\UnidadMedida;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $codigo = '';
    public string $nombre_material = '';
    public ?int $categoria_id = null;
    public ?int $unidad_medida_id = null;
    public int $stock_minimo = 0;

    public function with()
    {
        return [
            'categorias' => Categoria::all()->map(fn($c) => ['id' => $c->id, 'label' => $c->nombre_categoria]),
            'unidades' => UnidadMedida::all()->map(fn($u) => ['id' => $u->id, 'label' => $u->nombre . ' (' . $u->abreviatura . ')']),
        ];
    }

    public function save()
    {
        $validated = $this->validate([
            'codigo' => 'required|string|max:50|unique:materiales,codigo',
            'nombre_material' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        Material::create($validated);

        session()->flash('success', 'Material creado exitosamente.');

        return $this->redirect(route('materiales.index'), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Crear Nuevo Material') }}
                </h3>
                <a href="{{ route('materiales.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Código') }}</label>
                        <input wire:model="codigo" type="text" id="codigo" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('codigo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nombre_material" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre del Material') }}</label>
                        <input wire:model="nombre_material" type="text" id="nombre_material" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('nombre_material') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Categoría') }}</label>
                        <x-searchable-select 
                            wire-model="categoria_id" 
                            :options="$categorias" 
                            placeholder="Seleccione una categoría" 
                        />
                        @error('categoria_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="unidad_medida_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Unidad de Medida') }}</label>
                        <x-searchable-select 
                            wire-model="unidad_medida_id" 
                            :options="$unidades" 
                            placeholder="Seleccione una unidad" 
                        />
                        @error('unidad_medida_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Stock Mínimo') }}</label>
                    <input wire:model="stock_minimo" type="number" id="stock_minimo" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('stock_minimo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-zinc-500 focus:bg-zinc-500 active:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
