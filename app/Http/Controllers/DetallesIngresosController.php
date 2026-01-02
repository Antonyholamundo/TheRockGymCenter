<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallesIngresos;
use App\Models\Productos;

class DetallesIngresosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Ingresos.detallesIngresos', [
        'detalles_ingresos' => DetallesIngresos::with('producto')->get(),
        'productos' => Productos::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'ingreso' => 'required|string',
        'producto_id' => 'required|exists:productos,id',
        'cantidad' => 'required|integer|min:1',
    ]);

    $detalle = DetallesIngresos::create($request->all());

    // Update stock
    $producto = Productos::find($request->producto_id);
    if ($producto) {
        $producto->increment('stock', $request->cantidad);
    }

    return redirect()->route('detalles_ingresos.index')->with('success', 'Detalle Ingreso creado y stock actualizado.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, DetallesIngresos $detallesIngreso)
{
    $request->validate([
        'ingreso' => 'required|string',
        'producto_id' => 'required|exists:productos,id',
        'cantidad' => 'required|integer|min:1',
    ]);

    $detallesIngreso->update($request->all());

    return redirect()->route('detalles_ingresos.index')->with('success', 'Detalle Ingreso actualizado.');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(DetallesIngresos $detallesIngreso)
{
    $detallesIngreso->delete();
    return redirect()->route('detalles_ingresos.index')->with('success', 'Detalle Ingreso eliminado.');
}
}
