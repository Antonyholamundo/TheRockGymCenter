<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Ventas;
use App\Models\membresiasUsuarios;
use App\Models\Productos;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Usuarios Activos
        $usuariosActivos = Usuarios::where('estado', 'Activo')->count();

        // 2. Ingresos Hoy (Sumas de ventas totales del día)
        $ingresosHoy = Ventas::whereDate('created_at', Carbon::today())->sum('precio');

        // 3. Membresías Activas (Asignaciones vigentes)
        $membresiasActivas = membresiasUsuarios::where('estado', 'Activo')->count();

        // 4. Productos Bajos de Stock (Menos de 10 unidades)
        $productosBajos = Productos::where('stock', '<', 10)->count();

        return view('index', compact('usuariosActivos', 'ingresosHoy', 'membresiasActivas', 'productosBajos'));
    }
}
