<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// App Controllers
use App\Http\Controllers\DetallesIngresosController;
use App\Http\Controllers\IngresosController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\MembresiasController;
use App\Http\Controllers\MembresiasUsuariosController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ReportesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Route (Redirect to Login)
Route::get('/', function () {
    return redirect()->route('login');
})->name('index');

// Dashboard (Protected) - Points to the custom index view
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// User Profile Routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Application Routes (Protected by Auth)
Route::middleware(['auth'])->group(function () {
    // Rutas principales por controlador
    Route::resource('usuarios', UsuariosController::class);
    Route::resource('roles', RolesController::class);
    Route::resource('permisos', PermisosController::class);
    Route::resource('membresias', MembresiasController::class);
    Route::resource('membresias_usuarios', MembresiasUsuariosController::class);
    Route::resource('categorias', CategoriasController::class);
    Route::resource('productos', ProductosController::class);
    Route::resource('ingresos', IngresosController::class);
    Route::resource('detalles_ingresos', DetallesIngresosController::class);
    Route::resource('ventas', VentasController::class);

    // Rutas de reportes
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/ventas', [ReportesController::class, 'ventas'])->name('reportes.ventas');
    Route::get('/reportes/ingresos', [ReportesController::class, 'ingresos'])->name('reportes.ingresos');
    Route::get('/reportes/inventario', [ReportesController::class, 'inventario'])->name('reportes.inventario');
    Route::get('/reportes/usuarios', [ReportesController::class, 'usuarios'])->name('reportes.usuarios');
    Route::get('/reportes/membresias', [ReportesController::class, 'membresias'])->name('reportes.membresias');
    Route::get('/reportes/general', [ReportesController::class, 'general'])->name('reportes.general');

    // Rutas de reportes CSV
    Route::get('/reportes/ventas/csv', [ReportesController::class, 'ventasCsv'])->name('reportes.ventas.csv');
    Route::get('/reportes/ingresos/csv', [ReportesController::class, 'ingresosCsv'])->name('reportes.ingresos.csv');
    Route::get('/reportes/inventario/csv', [ReportesController::class, 'inventarioCsv'])->name('reportes.inventario.csv');
    Route::get('/reportes/usuarios/csv', [ReportesController::class, 'usuariosCsv'])->name('reportes.usuarios.csv');
    Route::get('/reportes/membresias/csv', [ReportesController::class, 'membresiasCsv'])->name('reportes.membresias.csv');
    Route::get('/reportes/general/csv', [ReportesController::class, 'generalCsv'])->name('reportes.general.csv');
});

require __DIR__.'/auth.php';
