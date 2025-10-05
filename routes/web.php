<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalisisProfundoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\RegistroFinancieroController;

// Dashboard principal
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Análisis 
Route::get('/analisis-profundo', [AnalisisProfundoController::class, 'index'])->name('analisis.profundo');

// Ingresos
Route::get('/ingreso/crear', [IngresoController::class, 'crear'])->name('ingreso.crear');
Route::post('/ingreso', [IngresoController::class, 'guardar'])->name('ingreso.guardar');

// Gastos
Route::get('/gasto/crear', [GastoController::class, 'crear'])->name('gasto.crear');
Route::post('/gasto', [GastoController::class, 'guardar'])->name('gasto.guardar');

// Listado de registros
Route::get('/registros', [RegistroFinancieroController::class, 'index'])->name('registros.index');
// Obtener categorías por tipo (para AJAX si lo necesitas después)
Route::get('/api/categorias', [GastoController::class, 'getCategorias'])->name('categorias.api');