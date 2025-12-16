<?php

use Livewire\Volt\Component;
use App\Models\Material;
use App\Models\Lote;
use App\Models\Movimiento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public $material_id = '';
    public $lote_id = '';
    public $cantidad = '';
    public $motivo = '';
    public $recibido_por_id = '';

    public $lotes_disponibles = [];

    public function updatedMaterialId($value)
    {
        $this->lote_id = '';
        $this->lotes_disponibles = [];
        
        if ($value) {
            // FEFO: First Expired, First Out
            $this->lotes_disponibles = Lote::where('material_id', $value)
                ->where('cantidad_disponible', '>', 0)
                ->orderBy('fecha_caducidad', 'asc') // Oldest expiration first
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Auto-select the first batch if available (FEFO suggestion)
            if ($this->lotes_disponibles->isNotEmpty()) {
                $this->lote_id = $this->lotes_disponibles->first()->id;
            }
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'material_id' => 'required|exists:materiales,id',
            'lote_id' => 'required|exists:lotes,id',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:255',
            'recibido_por_id' => 'required|exists:users,id',
        ]);

        $lote = Lote::find($this->lote_id);

        if ($validated['cantidad'] > $lote->cantidad_disponible) {
            $this->addError('cantidad', 'La cantidad excede el stock disponible en este lote (' . $lote->cantidad_disponible . ').');
            return;
        }

        // Decrement Lote Stock
        $lote->decrement('cantidad_disponible', $validated['cantidad']);

        // Decrement Material Stock
        $lote->material->decrement('stock_actual', $validated['cantidad']);

        // Create Movimiento
        Movimiento::create([
            'lote_id' => $lote->id,
            'user_id' => Auth::id(),
            'tipo' => 'SALIDA',
            'cantidad' => $validated['cantidad'],
            'motivo' => $validated['motivo'],
            'recibido_por_id' => $validated['recibido_por_id'],
            'fecha_movimiento' => now(),
        ]);

        session()->flash('success', 'Salida registrada correctamente.');

        return $this->redirect(route('movimientos.index'), navigate: true);
    }

    public function with()
    {
        return [
            'materiales' => Material::where('stock_actual', '>', 0)->get(), // Only show materials with stock
            'usuarios' => User::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 pb-32">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Registrar Salida de Material') }}
                </h3>
                <a href="{{ route('movimientos.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Volver al historial') }}
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
                            wire:model.live="material_id"
                            wireModel="material_id"
                            placeholder="Seleccione un material..."
                        />
                        @error('material_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative z-20">
                        <label for="lote_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Lote (Sugerido: FEFO)') }}</label>
                        <select wire:model="lote_id" id="lote_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione un lote...</option>
                            @foreach($lotes_disponibles as $lote)
                                <option value="{{ $lote->id }}">
                                    {{ $lote->lote }} - Vence: {{ $lote->fecha_caducidad ? $lote->fecha_caducidad->format('d/m/Y') : 'N/A' }} - Disp: {{ $lote->cantidad_disponible }}
                                </option>
                            @endforeach
                        </select>
                        @if(empty($lotes_disponibles) && $material_id)
                            <span class="text-yellow-500 text-xs">No hay lotes con stock disponible.</span>
                        @endif
                        @error('lote_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cantidad a Retirar') }}</label>
                        <input wire:model="cantidad" type="number" id="cantidad" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('cantidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Motivo / Destino') }}</label>
                        <input wire:model="motivo" type="text" id="motivo" placeholder="Ej. Proyecto X, Muestra Calidad..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('motivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4 relative z-10">
                    <x-searchable-select
                        label="Entregado A / Recibido Por"
                        :options="$usuarios"
                        option-label="name"
                        option-value="id"
                        wire:model="recibido_por_id"
                        wireModel="recibido_por_id"
                        placeholder="Seleccione usuario..."
                    />
                    @error('recibido_por_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Registrar Salida') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
