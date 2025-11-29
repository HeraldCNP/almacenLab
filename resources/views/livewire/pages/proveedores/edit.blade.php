<?php

use Livewire\Volt\Component;
use App\Models\Proveedor;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public Proveedor $proveedor;
    public string $nombre_proveedor = '';
    public string $contacto_proveedor = '';
    public string $email = '';
    public string $telefono = '';
    public string $direccion = '';

    public function mount(Proveedor $proveedor)
    {
        $this->proveedor = $proveedor;
        $this->nombre_proveedor = $proveedor->nombre_proveedor;
        $this->contacto_proveedor = $proveedor->contacto_proveedor ?? '';
        $this->email = $proveedor->email ?? '';
        $this->telefono = $proveedor->telefono ?? '';
        $this->direccion = $proveedor->direccion ?? '';
    }

    public function update()
    {
        $validated = $this->validate([
            'nombre_proveedor' => 'required|string|max:255|unique:proveedores,nombre_proveedor,' . $this->proveedor->id,
            'contacto_proveedor' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        $this->proveedor->update($validated);

        session()->flash('success', 'Proveedor actualizado correctamente.');

        return $this->redirect(route('proveedores.index'), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Editar Proveedor') }}
                </h3>
                <a href="{{ route('proveedores.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al listado') }}
                </a>
            </div>

            <form wire:submit="update">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nombre_proveedor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre de la Empresa') }}</label>
                        <input wire:model="nombre_proveedor" type="text" id="nombre_proveedor" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('nombre_proveedor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="contacto_proveedor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre del Contacto') }}</label>
                        <input wire:model="contacto_proveedor" type="text" id="contacto_proveedor" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('contacto_proveedor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                        <input wire:model="email" type="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Teléfono') }}</label>
                        <input wire:model="telefono" type="text" id="telefono" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dirección') }}</label>
                    <textarea wire:model="direccion" id="direccion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('direccion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-zinc-500 focus:bg-zinc-500 active:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Actualizar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
