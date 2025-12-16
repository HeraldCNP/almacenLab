<?php

use Livewire\Volt\Component;
use App\Models\Ubicacion;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $nombre_ubicacion = '';
    public string $descripcion = '';

    public function save()
    {
        $validated = $this->validate([
            'nombre_ubicacion' => 'required|string|max:255|unique:ubicaciones,nombre_ubicacion',
            'descripcion' => 'nullable|string|max:500',
        ]);

        Ubicacion::create($validated);

        session()->flash('success', 'Ubicación creada exitosamente.');

        return $this->redirect(route('ubicaciones.index'), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Crear Nueva Ubicación') }}
                </h3>
                <a href="{{ route('ubicaciones.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="save">
                <div class="mb-4">
                    <label for="nombre_ubicacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre de la Ubicación') }}</label>
                    <input wire:model="nombre_ubicacion" type="text" id="nombre_ubicacion" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('nombre_ubicacion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Descripción') }}</label>
                    <textarea wire:model="descripcion" id="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
