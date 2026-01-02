<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ventas;
use App\Models\Ingresos;
use App\Models\Productos;
use App\Models\Usuarios;
use App\Models\Membresias;
use App\Models\membresiasUsuarios;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    /**
     * Mostrar la página principal de reportes
     */
    public function index()
    {
        return view('reportes.index');
    }

    /**
     * Generar reporte de ventas
     */
    public function ventas(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $estado = $request->get('estado');

        $query = Ventas::query();

        if ($fechaInicio) {
            $query->where('fecha_venta', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_venta', '<=', $fechaFin);
        }

        // Corregir filtro de estado para booleano
        if ($estado === 'Pagado') {
            $query->where('pagado', 1);
        } elseif ($estado === 'Pendiente') {
            $query->where('pagado', 0);
        }

        $ventas = $query->get();
        $totalVentas = $ventas->sum('precio');
        $ventasPagadas = $ventas->where('pagado', 1)->sum('precio');
        $ventasPendientes = $ventas->where('pagado', 0)->sum('precio');

        $pdf = Pdf::loadView('reportes.pdf.ventas', compact('ventas', 'totalVentas', 'ventasPagadas', 'ventasPendientes', 'fechaInicio', 'fechaFin'));
        
        return $pdf->download('reporte_ventas.pdf');
    }

    /**
     * Generar reporte de ingresos
     */
    public function ingresos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = Ingresos::query();

        if ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        }

        $ingresos = $query->get();
        $totalIngresos = $ingresos->count();

        $pdf = Pdf::loadView('reportes.pdf.ingresos', compact('ingresos', 'totalIngresos', 'fechaInicio', 'fechaFin'));
        
        return $pdf->download('reporte_ingresos.pdf');
    }

    /**
     * Generar reporte de inventario
     */
    public function inventario(Request $request)
    {
        $categoria = $request->get('categoria');
        $estado = $request->get('estado');

        $query = Productos::with('categoria');

        if ($categoria) {
            $query->where('categoria_id', $categoria);
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $productos = $query->get();

        $pdf = Pdf::loadView('reportes.pdf.inventario', compact('productos'));
        
        return $pdf->download('reporte_inventario.pdf');
    }

    /**
     * Generar reporte de usuarios
     */
    public function usuarios(Request $request)
    {
        $estado = $request->get('estado');

        $query = Usuarios::query();

        if ($estado) {
            $query->where('estado', $estado);
        }

        $usuarios = $query->get();
        $totalUsuarios = $usuarios->count();
        $usuariosActivos = $usuarios->where('estado', 'Activo')->count();
        $usuariosInactivos = $usuarios->where('estado', 'Inactivo')->count();

        $pdf = Pdf::loadView('reportes.pdf.usuarios', compact('usuarios', 'totalUsuarios', 'usuariosActivos', 'usuariosInactivos'));
        
        return $pdf->download('reporte_usuarios.pdf');
    }

    /**
     * Generar reporte de membresías
     */
    public function membresias(Request $request)
    {
        $estado = $request->get('estado');

        $query = membresiasUsuarios::with('membresia');

        if ($estado) {
            $query->where('estado', $estado);
        }

        $membresias = $query->get();
        $totalMembresias = $membresias->count();
        $membresiasActivas = $membresias->where('estado', 'Activo')->count();
        $membresiasInactivas = $membresias->where('estado', 'Inactivo')->count();
        $totalIngresos = $membresias->sum('precio');

        $pdf = Pdf::loadView('reportes.pdf.membresias', compact('membresias', 'totalMembresias', 'membresiasActivas', 'membresiasInactivas', 'totalIngresos'));
        
        return $pdf->download('reporte_membresias.pdf');
    }

    /**
     * Generar reporte general
     */
    public function general()
    {
        $totalVentas = Ventas::count();
        $totalIngresos = Ingresos::count();
        $totalProductos = Productos::count();
        $totalUsuarios = Usuarios::count();
        $totalMembresias = membresiasUsuarios::count();
        
        $ingresosVentas = Ventas::sum('precio');
        $ingresosMembresias = membresiasUsuarios::sum('precio');
        $valorInventario = Productos::sum(\DB::raw('precio * stock'));

        $pdf = Pdf::loadView('reportes.pdf.general', compact(
            'totalVentas', 'totalIngresos', 'totalProductos', 'totalUsuarios', 'totalMembresias',
            'ingresosVentas', 'ingresosMembresias', 'valorInventario'
        ));
        
        return $pdf->download('reporte_general.pdf');
    }

    // --- EXPORTAR VENTAS CSV ---
    public function ventasCsv(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $estado = $request->get('estado');

        $query = Ventas::query();
        if ($fechaInicio) {
            $query->where('fecha_venta', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('fecha_venta', '<=', $fechaFin);
        }
        if ($estado === 'Pagado') {
            $query->where('pagado', 1);
        } elseif ($estado === 'Pendiente') {
            $query->where('pagado', 0);
        }
        $ventas = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_ventas.csv"',
        ];
        $callback = function() use ($ventas) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cliente', 'Vendedor', 'Producto', 'Precio', 'Fecha de Venta', 'Estado', 'Fecha de Pago']);
            foreach ($ventas as $venta) {
                fputcsv($handle, [
                    $venta->cliente,
                    $venta->vendedor,
                    $venta->producto,
                    $venta->precio,
                    $venta->fecha_venta,
                    $venta->pagado ? 'Pagado' : 'Pendiente',
                    $venta->fecha_pago
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // --- EXPORTAR INGRESOS CSV ---
    public function ingresosCsv(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $query = Ingresos::query();
        if ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        }
        $ingresos = $query->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_ingresos.csv"',
        ];
        $callback = function() use ($ingresos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cédula', 'Fecha', 'Detalles']);
            foreach ($ingresos as $ingreso) {
                fputcsv($handle, [
                    $ingreso->cedula,
                    $ingreso->fecha,
                    $ingreso->detalles
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // --- EXPORTAR INVENTARIO CSV ---
    public function inventarioCsv(Request $request)
    {
        $categoria = $request->get('categoria');
        $estado = $request->get('estado');
        $query = Productos::with('categoria');
        if ($categoria) {
            $query->where('categoria_id', $categoria);
        }
        if ($estado) {
            $query->where('estado', $estado);
        }
        $productos = $query->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_inventario.csv"',
        ];
        $callback = function() use ($productos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Producto', 'Categoría', 'Precio', 'Stock', 'Valor en Stock', 'Estado', 'Descripción']);
            foreach ($productos as $producto) {
                fputcsv($handle, [
                    $producto->nombre,
                    $producto->categoria ? $producto->categoria->nombre : 'Sin categoría',
                    $producto->precio,
                    $producto->stock,
                    $producto->precio * $producto->stock,
                    $producto->estado,
                    $producto->descripcion
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // --- EXPORTAR USUARIOS CSV ---
    public function usuariosCsv(Request $request)
    {
        $estado = $request->get('estado');
        $query = Usuarios::query();
        if ($estado) {
            $query->where('estado', $estado);
        }
        $usuarios = $query->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_usuarios.csv"',
        ];
        $callback = function() use ($usuarios) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cédula', 'Nombres', 'Apellidos', 'Email', 'Teléfono', 'Fecha Nacimiento', 'Estado']);
            foreach ($usuarios as $usuario) {
                fputcsv($handle, [
                    $usuario->cedula,
                    $usuario->nombres,
                    $usuario->apellidos,
                    $usuario->email,
                    $usuario->telefono,
                    $usuario->fecha_nacimiento,
                    $usuario->estado
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // --- EXPORTAR MEMBRESIAS CSV ---
    public function membresiasCsv(Request $request)
    {
        $estado = $request->get('estado');
        $query = membresiasUsuarios::with('membresia');
        if ($estado) {
            $query->where('estado', $estado);
        }
        $membresias = $query->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_membresias.csv"',
        ];
        $callback = function() use ($membresias) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Usuario', 'Membresía', 'Precio', 'Fecha de Pago', 'Fecha Inicio', 'Fecha Fin', 'Estado']);
            foreach ($membresias as $m) {
                fputcsv($handle, [
                    $m->usuario,
                    $m->membresia,
                    $m->precio,
                    $m->fecha_pago,
                    $m->fecha_inicio,
                    $m->fecha_fin,
                    $m->estado
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // --- EXPORTAR GENERAL CSV ---
    public function generalCsv()
    {
        $totalVentas = Ventas::count();
        $totalIngresos = Ingresos::count();
        $totalProductos = Productos::count();
        $totalUsuarios = Usuarios::count();
        $totalMembresias = membresiasUsuarios::count();
        $ingresosVentas = Ventas::sum('precio');
        $ingresosMembresias = membresiasUsuarios::sum('precio');
        $valorInventario = Productos::sum(\DB::raw('precio * stock'));
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_general.csv"',
        ];
        $callback = function() use (
            $totalVentas, $totalIngresos, $totalProductos, $totalUsuarios, $totalMembresias,
            $ingresosVentas, $ingresosMembresias, $valorInventario
        ) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Métrica', 'Valor']);
            fputcsv($handle, ['Total Ventas', $totalVentas]);
            fputcsv($handle, ['Total Ingresos', $totalIngresos]);
            fputcsv($handle, ['Total Productos', $totalProductos]);
            fputcsv($handle, ['Total Usuarios', $totalUsuarios]);
            fputcsv($handle, ['Total Membresías', $totalMembresias]);
            fputcsv($handle, ['Ingresos por Ventas', $ingresosVentas]);
            fputcsv($handle, ['Ingresos por Membresías', $ingresosMembresias]);
            fputcsv($handle, ['Valor del Inventario', $valorInventario]);
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }
} 