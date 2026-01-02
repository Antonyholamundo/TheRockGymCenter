<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventario.productos', [
            'productos' => Productos::with('categoria')->get(),
            'categorias' => \App\Models\Categorias::where('estado', 'Activo')->get()
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
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|integer|min:1',
            'descripcion' => 'required|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Productos::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
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
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|integer|min:1',
            'descripcion' => 'required|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $producto = Productos::findOrFail($id);
        $producto->update([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Productos::destroy($id);
            return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000" || $e->getCode() == "23001") {
                return redirect()->route('productos.index')->with('error', 'No se puede eliminar el producto porque tiene ventas asociadas. Considere desactivarlo en su lugar.');
            }
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al intentar eliminar el producto.');
        }
    }
}