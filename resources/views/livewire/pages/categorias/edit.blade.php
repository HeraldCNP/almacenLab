<?php

use Livewire\Volt\Component;
use App\Models\Categoria;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public Categoria $categoria;
    public string $nombre_categoria = '';

    public function mount(Categoria $categoria)
    {
        $this->categoria = $categoria;
        $this->nombre_categoria = $categoria->nombre_categoria;
    }

    public function update()
    {
        $validated = $this->validate([
            'nombre_categoria' => 'required|string|max:255|unique:categorias,nombre_categoria,' . $this->categoria->id,
        ]);

        $this->categoria->update($validated);

        session()->flash('success', 'Categoría actualizada correctamente.');

        return $this->redirect(route('categorias.index'), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Editar Categoría') }}
                </h2>
                <a href="{{ route('categorias.index') }}" wire:navigate class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    &larr; {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="update" class="max-w-md">
                <div class="mb-4">
                    <flux:input label="Nombre de la Categoría" wire:model="nombre_categoria" />
                    <flux:error name="nombre_categoria" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Actualizar Categoría') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
