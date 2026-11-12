<?php

use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\OperatorController;
use Illuminate\Support\Facades\Route;

// Redirección inicial
Route::get('/', fn () => redirect('/kiosco'));

// ===================== RUTAS PÚBLICAS =====================
Route::get('/kiosco', [KioskController::class, 'index'])->name('kiosco.index');
Route::post('/kiosco/turno', [KioskController::class, 'store'])->name('kiosco.store');
Route::get('/turno/{ticket}', [KioskController::class, 'show'])->name('ticket.show');
Route::get('/turno/{ticket}/posicion', [KioskController::class, 'position'])->name('ticket.position');
Route::get('/pantalla', [DisplayController::class, 'index'])->name('pantalla');

// ===================== AUTENTICACIÓN =====================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================== OPERADORES =====================
Route::middleware('auth')->prefix('operador')->group(function () {
    Route::get('/', [OperatorController::class, 'index']);
    Route::get('/cola', [OperatorController::class, 'waiting']);
    Route::post('/llamar', [OperatorController::class, 'next']);
    Route::post('/turno/{ticket}/iniciar', [OperatorController::class, 'start']);
    Route::post('/turno/{ticket}/finalizar', [OperatorController::class, 'finish']);
    Route::post('/turno/{ticket}/ausente', [OperatorController::class, 'noShow']);
});

// ===================== ADMINISTRACIÓN =====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports');
    Route::get('/reportes/exportar', [ReportController::class, 'export'])->name('reports.export');

    Route::resource('servicios', ServiceController::class)
        ->parameters(['servicios' => 'service'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('ventanillas', CounterController::class)
        ->parameters(['ventanillas' => 'counter'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('usuarios', UserController::class)
        ->parameters(['usuarios' => 'user'])
        ->only(['index', 'store', 'destroy']);
});
