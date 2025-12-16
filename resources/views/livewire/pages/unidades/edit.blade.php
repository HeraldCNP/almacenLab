<?php

use Livewire\Volt\Component;
use App\Models\UnidadMedida;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public UnidadMedida $unidad;
    public string $nombre = '';
    public string $abreviatura = '';

    public function mount(UnidadMedida $unidad)
    {
        $this->unidad = $unidad;
        $this->nombre = $unidad->nombre;
        $this->abreviatura = $unidad->abreviatura;
    }

    public function update()
    {
        $validated = $this->validate([
            'nombre' => 'required|string|max:255|unique:unidades_medida,nombre,' . $this->unidad->id,
            'abreviatura' => 'required|string|max:10',
        ]);

        $this->unidad->update($validated);

        session()->flash('success', 'Unidad de medida actualizada correctamente.');

        return $this->redirect(route('unidades.index'), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Editar Unidad de Medida') }}
                </h3>
                <a href="{{ route('unidades.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="update">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre') }}</label>
                    <input wire:model="nombre" type="text" id="nombre" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="abreviatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Abreviatura') }}</label>
                    <input wire:model="abreviatura" type="text" id="abreviatura" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('abreviatura') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Actualizar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
