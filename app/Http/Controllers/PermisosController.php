<?php

namespace App\Http\Controllers;
use App\Models\Permisos;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permisos::with('usuario')->get();
        $usuarios = Usuarios::all();
        return view('usuarios.permisos', compact('permisos', 'usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = Usuarios::all();
        return view('usuarios.permisos', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $request->validate([
        'usuario_id' => 'required|exists:usuarios,id',
        'rol' => 'required|string',
        'fecha_asignacion' => 'required|date',
    ]);

    Permisos::create([
        'usuario_id' => $request->usuario_id,
        'rol' => $request->rol,
        'fecha_asignacion' => $request->fecha_asignacion,
    ]);

    return redirect()->route('permisos.index')->with('success', 'Permiso creado exitosamente.');
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
        'usuario_id' => 'required|exists:usuarios,id',
        'rol' => 'required|string',
        'fecha_asignacion' => 'required|date',
    ]);
    $permiso = Permisos::findOrFail($id);
    $permiso->update($request->all());
    return redirect()->route('permisos.index')->with('success', 'Permiso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Permisos::destroy($id);
        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado exitosamente.');

    }
}
