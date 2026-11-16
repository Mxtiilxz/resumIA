<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumenController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('resumenes.index'));

// Las cuentas. Solo para quien NO tiene sesión: el middleware guest.
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar']);
    Route::get('/login',     [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'entrar']);
});

Route::post('/logout', [AuthController::class, 'salir'])->middleware('auth')->name('logout');

// Los resúmenes. Todo pide sesión.
Route::middleware('auth')->group(function () {
    Route::get('/resumenes',                       [ResumenController::class, 'index'])->name('resumenes.index');
    Route::get('/resumenes/crear',                 [ResumenController::class, 'create'])->name('resumenes.create');
    Route::post('/resumenes',                      [ResumenController::class, 'store'])->name('resumenes.store');
    Route::get('/resumenes/{resumen}',             [ResumenController::class, 'show'])->name('resumenes.show');
    Route::get('/resumenes/{resumen}/editar',      [ResumenController::class, 'edit'])->name('resumenes.edit');
    Route::put('/resumenes/{resumen}',             [ResumenController::class, 'update'])->name('resumenes.update');
    Route::patch('/resumenes/{resumen}/regenerar', [ResumenController::class, 'regenerar'])->name('resumenes.regenerar');
    Route::get('/resumenes/{resumen}/eliminar',    [ResumenController::class, 'eliminar'])->name('resumenes.eliminar');
    Route::delete('/resumenes/{resumen}',          [ResumenController::class, 'destroy'])->name('resumenes.destroy');
});
