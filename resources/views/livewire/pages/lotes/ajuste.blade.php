<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use App\Models\Lote;
use App\Models\Movimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] class extends Component {
    public Lote $lote;

    #[Rule('required|in:incremento,disminucion')]
    public string $tipo_ajuste = 'disminucion';

    #[Rule('required|numeric|min:0.01')]
    public float $cantidad;

    #[Rule('required|string|min:5|max:255')]
    public string $motivo = '';

    public function mount(Lote $lote)
    {
        $this->lote = $lote;
    }

    public function registrarAjuste()
    {
        $this->validate();

        if ($this->tipo_ajuste === 'disminucion' && $this->cantidad > $this->lote->cantidad_disponible) {
            $this->addError('cantidad', 'La cantidad a disminuir no puede ser mayor al stock disponible.');
            return;
        }

            DB::transaction(function () {
                // 1. Update Lote Stock
                if ($this->tipo_ajuste === 'incremento') {
                    $this->lote->cantidad_disponible += $this->cantidad;
                } else {
                    $this->lote->cantidad_disponible -= $this->cantidad;
                }
                $this->lote->save();

                // 2. Update Material Stock
                if ($this->tipo_ajuste === 'incremento') {
                    $this->lote->material->increment('stock_actual', $this->cantidad);
                } else {
                    $this->lote->material->decrement('stock_actual', $this->cantidad);
                }

                // 3. Create Movement
                Movimiento::create([
                    'lote_id' => $this->lote->id,
                    'user_id' => Auth::id(),
                    'tipo' => 'AJUSTE',
                    'cantidad' => $this->cantidad,
                    'motivo' => ($this->tipo_ajuste === 'incremento' ? '[INCREMENTO] ' : '[DISMINUCIÓN] ') . $this->motivo,
                ]);
            });

            session()->flash('success', 'Ajuste de inventario realizado con éxito.');
            $this->redirect(route('lotes.index'));
    }
}; ?>

<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Ajuste de Inventario') }}
                </h3>
                <a href="{{ route('lotes.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    {{ __('Cancelar') }}
                </a>
            </div>

            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Detalles del Lote</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500 dark:text-gray-400">Material:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $this->lote->material->nombre_material }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 dark:text-gray-400">Lote:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $this->lote->lote }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 dark:text-gray-400">Stock Actual del Lote:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $this->lote->cantidad_disponible }} {{ $this->lote->material->unidadMedida->abreviatura }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 dark:text-gray-400">Vencimiento:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $this->lote->fecha_caducidad ? $this->lote->fecha_caducidad->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <form wire:submit="registrarAjuste" class="space-y-6">
                <!-- Tipo de Ajuste -->
                <div>
                    <flux:label>{{ __('Tipo de Ajuste') }}</flux:label>
                    <div class="mt-2 flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model.live="tipo_ajuste" value="disminucion" class="form-radio text-red-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Disminución (Pérdida, Rotura, Salida no reg.)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model.live="tipo_ajuste" value="incremento" class="form-radio text-green-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Incremento (Hallazgo, Devolución, etc.)</span>
                        </label>
                    </div>
                    <flux:error name="tipo_ajuste" />
                </div>

                <!-- Cantidad -->
                <div>
                    <flux:input label="{{ __('Cantidad a Ajustar') }}" type="number" step="0.01" wire:model="cantidad" placeholder="Ej: 5" />
                </div>

                <!-- Motivo -->
                <div>
                    <flux:textarea label="{{ __('Motivo del Ajuste') }}" wire:model="motivo" placeholder="Describa la razón del ajuste (Ej: Frasco roto durante limpieza)" rows="3" />
                </div>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" class="bg-zinc-600 hover:bg-zinc-500 text-white cursor-pointer">
                        {{ __('Registrar Ajuste') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
