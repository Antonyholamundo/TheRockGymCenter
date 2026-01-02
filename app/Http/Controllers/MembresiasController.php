<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Membresias;
class MembresiasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           return view('membresias.membresias', [
            'membresias' => Membresias::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $membresias = Membresias::all();
        return view('membresias.membresias', compact('membresias'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'descripcion' => 'required|string',
        'fecha_creacion' => 'required|date',
        'estado' => 'required|in:activo,inactivo'
    ]);

    Membresias::create($request->all());

    return redirect()->route('membresias.index')
        ->with('success', 'Membresía creada correctamente');


}

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
    public function update(Request $request, string $id)
    {
       $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'descripcion' => 'required|string',
        'fecha_creacion' => 'required|date',
        'estado' => 'required|in:activo,inactivo'
    ]);
    $membresia = Membresias::findOrFail($id);
    $membresia->update($request->all());
    return redirect()->route('membresias.index')->with('success', 'Membresía actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
   Membresias::destroy($id);
        return redirect()->route('membresias.index')->with('success', 'Membresía eliminada exitosamente.');

    }
}
