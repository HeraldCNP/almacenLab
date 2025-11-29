<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    //
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Reporte de Stock --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                        <flux:icon.clipboard-document-list class="w-8 h-8" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Reporte de Stock Actual</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Listado completo de materiales y existencias.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('reportes.stock') }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <flux:icon.arrow-down-tray class="w-4 h-4 mr-2" />
                        Descargar PDF
                    </a>
                </div>
            </div>

            {{-- Reporte de Movimientos --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300">
                        <flux:icon.arrows-right-left class="w-8 h-8" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Movimientos del Mes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Historial de entradas y salidas de {{ now()->format('F') }}.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('reportes.movimientos') }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <flux:icon.arrow-down-tray class="w-4 h-4 mr-2" />
                        Descargar PDF
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
