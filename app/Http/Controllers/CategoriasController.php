<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventario.categorias', [
            'categorias' => Categorias::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No necesitamos vista separada, usamos modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Categorias::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // No necesitamos vista show por ahora
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // No necesitamos vista separada, usamos modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validación básica
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $categoria = Categorias::findOrFail($id);
        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Categorias::destroy($id);
        
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}