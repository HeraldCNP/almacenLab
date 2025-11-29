<?php

use Livewire\Volt\Component;
use Livewire\WithPagination; // Aún lo necesitamos para el listado/paginación (R)
use App\Models\Categoria;
use Livewire\Attributes\Rule;

new class extends Component {
    // Livewire Features
    use WithPagination;

    // --- PROPIEDADES DE CREACIÓN ---
    #[Rule('required|string|min:3|max:50|unique:categorias,nombre_categoria')]
    public string $nombre_categoria = '';
    #[Rule('required|string|min:3|max:50')]
    public string $nombre_edicion = '';

    // --- PROPIEDADES INICIALES (Mínimo necesario para la tabla) ---
    public string $busqueda = '';
    public ?Categoria $categoriaAEditar = null;

    public bool $mostrarModalEdicion = false;

    /**
     * Define las categorías a mostrar (función de Lectura 'R').
     * Aunque solo implementaremos la Creación 'C', necesitamos esta función
     * para mostrar la tabla de resultados.
     */
    public function with()
    {
        // Aplicamos la lógica de búsqueda y paginación para mostrar el listado (R)
        $categorias = Categoria::query()
            ->when($this->busqueda, function ($query) {
                $query->where('nombre_categoria', 'like', '%' . $this->busqueda . '%');
            })
            ->latest()
            ->paginate(10);

        return [
            'categorias' => $categorias,
        ];
    }

    public function prepararEdicion(Categoria $categoria)
    {
        $this->categoriaAEditar = $categoria;
        $this->nombre_edicion = $categoria->nombre_categoria;
        $this->mostrarModalEdicion = true;
    }

    public function actualizarCategoria()
    {
        // Reglas de validación, crucial el 'ignore' para permitir su nombre actual.
        $reglas = [
            'nombre_edicion' => [
                'required',
                'string',
                'min:3',
                'max:50',
                // Ignorar el ID de la categoría que estamos editando
                \Illuminate\Validation\Rule::unique('categorias', 'nombre_categoria')->ignore($this->categoriaAEditar->id),
            ],
        ];

        $this->validate($reglas);

        $this->categoriaAEditar->update([
            'nombre_categoria' => $this->nombre_edicion,
        ]);

        $this->cerrarModalEdicion();
        session()->flash('success', '¡Categoría actualizada con éxito!');
    }

    /**
     * Reinicia el paginador al cambiar la búsqueda.
     */
    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    /**
     * Guarda una nueva categoría (función de Creación 'C').
     */
    public function guardarCategoria()
    {
        // 1. Validar solo el campo de creación
        $this->validateOnly('nombre_categoria');

        // 2. Crear el registro en la base de datos
        Categoria::create([
            'nombre_categoria' => $this->nombre_categoria,
        ]);

        // 3. Limpiar el formulario y resetear la paginación para ver el nuevo registro
        $this->reset('nombre_categoria');
        $this->resetPage();

        // 4. Mostrar mensaje de éxito
        session()->flash('success', '¡Categoría creada con éxito!');
    }

    public function cerrarModalEdicion()
    {
        $this->reset(['mostrarModalEdicion', 'categoriaAEditar', 'nombre_edicion']);
        $this->resetValidation();
    }

    public function eliminarCategoria(int $categoriaId)
    {
        // 1. Busca la categoría (findOrFail lanzará 404 si no existe)
        $categoria = Categoria::findOrFail($categoriaId);

        // 2. Elimina el registro
        $categoria->delete();

        // 3. Reinicia la paginación para asegurar que la lista se refresque
        $this->resetPage();

        // 4. Muestra un mensaje de advertencia o éxito.
        session()->flash('success', 'Categoría eliminada con éxito.');
    }
};

?>


<div class="gestion-categorias-container">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensajes de Sesión --}}
            @if (session()->has('success'))
                {{-- Ajustado para Dark Mode: Se usa un tono más oscuro para el fondo en modo oscuro --}}
                <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-700 dark:border-green-600 dark:text-green-100 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('warning'))
                {{-- Ajustado para Dark Mode: Se usa un tono más oscuro para el fondo en modo oscuro --}}
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                </div>
            @endif

            {{-- CARD PRINCIPAL --}}
            {{-- Fondo de tarjeta principal: Blanco en claro, Gris oscuro en oscuro --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- FORMULARIO DE CREACIÓN (C) --}}
                {{-- Borde inferior ajustado para dark mode --}}
                <div class="mb-8 border-b dark:border-gray-700 pb-4">
                    {{-- Título ajustado para dark mode --}}
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Crear Nueva Categoría') }}</h3>
                    <form wire:submit="guardarCategoria" class="flex gap-4 items-end">
                        <div class="flex-grow">
                            {{-- Etiqueta ajustada para dark mode --}}
                            <label for="nombre_categoria"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre de la Categoría') }}</label>
                            {{-- Campo de entrada ajustado para dark mode --}}
                            <input wire:model="nombre_categoria" type="text" id="nombre_categoria"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Ej: Herramientas, Químicos, Electrónica">

                            @error('nombre_categoria')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring disabled:opacity-25 transition">
                            {{ __('Guardar') }}
                        </button>
                    </form>
                </div>
                {{-- FIN FORMULARIO DE CREACIÓN --}}

                {{-- LISTADO DE CATEGORÍAS (R) --}}
                {{-- Título ajustado para dark mode --}}
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Categorías Existentes') }}
                </h3>

                {{-- Campo de Búsqueda --}}
                <div class="mb-4">
                    {{-- Campo de entrada de búsqueda ajustado para dark mode --}}
                    <input wire:model.live="busqueda" type="text" placeholder="{{ __('Buscar categoría...') }}"
                        class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                </div>

                {{-- Tabla de Datos --}}
                <div class="overflow-x-auto">
                    {{-- Divisor de tabla ajustado para dark mode --}}
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        {{-- Cabecera de tabla ajustada para dark mode --}}
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                {{-- Texto de cabecera ajustado para dark mode --}}
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('ID') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Nombre') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Fecha Creación') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Acciones') }} </th>

                            </tr>
                        </thead>
                        {{-- Cuerpo de tabla ajustado para dark mode --}}
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($categorias as $categoria)
                                <tr wire:key="categoria-{{ $categoria->id }}">
                                    {{-- Texto de celdas ajustado para dark mode --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $categoria->id }}</td>
                                    {{-- Nombre de categoría (texto principal) ajustado para dark mode --}}
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $categoria->nombre_categoria }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $categoria->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Botones de acción ajustados para dark mode --}}
                                        <button wire:click="prepararEdicion({{ $categoria->id }})"
                                            class="text-indigo-400 hover:text-indigo-600 mr-3">
                                            {{ __('Editar') }}
                                        </button>
                                        <button wire:click="eliminarCategoria({{ $categoria->id }})"
                                            wire:confirm="{{ __('¿Estás seguro de que quieres eliminar esta categoría?') }}"
                                            class="text-red-400 hover:text-red-600">
                                            {{ __('Eliminar') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Texto de celda vacía ajustado para dark mode --}}
                                    <td colspan="4"
                                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No se encontraron categorías.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Enlaces de Paginación --}}
                <div class="mt-4">
                    {{-- Livewire automáticamente ajusta la paginación para dark mode si tu configuración de Tailwind lo permite. --}}
                    {{ $categorias->links() }}
                </div>
                {{-- FIN LISTADO --}}

            </div>
        </div>
    </div>


    {{-- ================================================= --}}
    {{-- MODAL DE EDICIÓN --}}
    {{-- ================================================= --}}
    @if ($mostrarModalEdicion)
        {{-- Fondo del overlay ajustado para dark mode (usamos bg-gray-900 más oscuro) --}}
        <div x-data="{ open: @entangle('mostrarModalEdicion') }" x-show="open" x-on:keydown.escape.window="open = false"
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 transition-opacity duration-300 ease-out">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="open = false" {{-- Contenido principal del modal ajustado para dark mode --}}
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <form wire:submit.prevent="actualizarCategoria">
                        {{-- Cuerpo del formulario ajustado para dark mode --}}
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Editar Categoría') }}
                            </h3>
                            <div class="mt-2">
                                <label for="nombre_edicion"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre') }}</label>
                                {{-- Campo de entrada ajustado para dark mode --}}
                                <input wire:model.live="nombre_edicion" type="text" id="nombre_edicion"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="Nombre de la categoría">

                                @error('nombre_edicion')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Pie de página del modal ajustado para dark mode --}}
                        <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Guardar Cambios') }}
                            </button>
                            <button type="button" wire:click="cerrarModalEdicion" {{-- Botón de cancelar ajustado para dark mode --}}
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-700 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Cancelar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
