<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingresos;
class IngresosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Ingresos.ingresos', [
            'ingresos' => Ingresos::all()
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
            'cedula' => 'required|string|max:20',
            'fecha' => 'required|date',
            'detalles' => 'nullable|string|max:255',
        ]);

        Ingresos::create($request->all());
        return redirect()->route('ingresos.index')->with('success', 'Ingreso creado.');
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
    public function update(Request $request, Ingresos $ingreso)
    {
      $request->validate([ /* mismos campos que store */ ]);
        $ingreso->update($request->all());
        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingresos $ingreso)
    {
        $ingreso->delete();
        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado.');

    }
};
