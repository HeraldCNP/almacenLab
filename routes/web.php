<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::middleware(['auth'])->group(function () {
    Volt::route('categorias', 'pages.categorias.index')->name('categorias.index');
    Volt::route('categorias/crear', 'pages.categorias.create')->name('categorias.create');
    Volt::route('categorias/{categoria}/editar', 'pages.categorias.edit')->name('categorias.edit');

    Volt::route('unidades', 'pages.unidades.index')->name('unidades.index');
    Volt::route('unidades/crear', 'pages.unidades.create')->name('unidades.create');
    Volt::route('unidades/{unidad}/editar', 'pages.unidades.edit')->name('unidades.edit');

    Volt::route('materiales', 'pages.materiales.index')->name('materiales.index');
    Volt::route('materiales/crear', 'pages.materiales.create')->name('materiales.create');
    Volt::route('materiales/{material}/editar', 'pages.materiales.edit')->name('materiales.edit');

    Volt::route('proveedores', 'pages.proveedores.index')->name('proveedores.index');
    Volt::route('proveedores/crear', 'pages.proveedores.create')->name('proveedores.create');
    Volt::route('proveedores/{proveedor}/editar', 'pages.proveedores.edit')->name('proveedores.edit');

    Volt::route('ubicaciones', 'pages.ubicaciones.index')->name('ubicaciones.index');
    Volt::route('ubicaciones/crear', 'pages.ubicaciones.create')->name('ubicaciones.create');
    Volt::route('ubicaciones/{ubicacion}/editar', 'pages.ubicaciones.edit')->name('ubicaciones.edit');

    Volt::route('lotes', 'pages.lotes.index')->name('lotes.index');
    Volt::route('lotes/crear', 'pages.lotes.create')->name('lotes.create');
    Volt::route('lotes/{lote}/editar', 'pages.lotes.edit')->name('lotes.edit');

    Volt::route('movimientos', 'pages.movimientos.index')->name('movimientos.index');
    Volt::route('movimientos/salida', 'pages.movimientos.create-salida')->name('movimientos.salida');

    Volt::route('reportes', 'pages.reportes.index')->name('reportes.index');
    Route::get('reportes/stock', [App\Http\Controllers\ReporteController::class, 'stock'])->name('reportes.stock');
    Route::get('/reportes/movimientos', [App\Http\Controllers\ReporteController::class, 'movimientos'])->name('reportes.movimientos');

    Volt::route('/lotes/{lote}/ajuste', 'pages.lotes.ajuste')->name('lotes.ajuste');
});

require __DIR__.'/auth.php';
