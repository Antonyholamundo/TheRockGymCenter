<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorias;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categorias = Categorias::where('estado', 'Activo')->get();
        return response()->json($categorias);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria = Categorias::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => 'Activo',
        ]);

        return response()->json([
            'message' => 'Categoría creada exitosamente',
            'categoria' => $categoria
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria = Categorias::findOrFail($id);
        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'message' => 'Categoría actualizada exitosamente',
            'categoria' => $categoria
        ]);
    }

    public function destroy($id)
    {
        try {
            Categorias::destroy($id);
            return response()->json(['message' => 'Categoría eliminada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar categoría'], 500);
        }
    }
}
