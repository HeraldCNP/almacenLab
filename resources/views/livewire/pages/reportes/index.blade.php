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
                    <a href="{{ route('reportes.stock') }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
                    <a href="{{ route('reportes.movimientos') }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <flux:icon.arrow-down-tray class="w-4 h-4 mr-2" />
                        Descargar PDF
                    </a>
                </div>
            </div>

            {{-- Planilla de Entrega --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 col-span-1 md:col-span-2">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300">
                        <flux:icon.document-check class="w-8 h-8" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Planilla de Entrega</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Generar constancia de entrega con campos de firma.</p>
                    </div>
                </div>
                
                <form action="{{ route('reportes.planilla-entrega') }}" method="GET" target="_blank" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <flux:input type="date" name="fecha_inicio" label="Fecha Inicio" required value="{{ now()->startOfMonth()->format('Y-m-d') }}" />
                    </div>
                    <div>
                        <flux:input type="date" name="fecha_fin" label="Fecha Fin" required value="{{ now()->format('Y-m-d') }}" />
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 h-10">
                            <flux:icon.printer class="w-4 h-4 mr-2" />
                            Generar Planilla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
