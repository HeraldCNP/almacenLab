<?php

use Livewire\Volt\Component;
use App\Models\Categoria;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $nombre_categoria = '';

    public function save()
    {
        $validated = $this->validate([
            'nombre_categoria' => 'required|string|max:255|unique:categorias,nombre_categoria',
        ]);

        Categoria::create($validated);

        session()->flash('success', 'Categoría creada exitosamente.');

        return $this->redirect(route('categorias.index'), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Crear Nueva Categoría') }}
                </h2>
                <a href="{{ route('categorias.index') }}" wire:navigate class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    &larr; {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="save" class="max-w-md">
                <div class="mb-4">
                    <flux:input label="Nombre de la Categoría" wire:model="nombre_categoria" placeholder="Ej. Electrónica" />
                    <flux:error name="nombre_categoria" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-zinc-500 focus:bg-zinc-500 active:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Guardar Categoría') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
