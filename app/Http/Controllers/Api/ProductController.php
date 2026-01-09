<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Productos;
use App\Models\Categorias;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Productos::with('categoria')->where('estado', 'Activo')->get();
        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $producto = Productos::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'producto' => $producto
        ], 201);
    }
    
    public function categories() {
        $categorias = Categorias::where('estado', 'Activo')->get();
        return response()->json($categorias);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|integer|exists:categorias,id',
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

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'producto' => $producto
        ]);
    }

    public function destroy($id)
    {
        try {
            Productos::destroy($id);
            return response()->json(['message' => 'Producto eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar producto'], 500);
        }
    }
}
