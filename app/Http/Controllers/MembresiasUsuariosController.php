<?php

namespace App\Http\Controllers;

use App\Models\membresiasUsuarios;
use App\Models\MembresiaUsuario;
use App\Models\Membresias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembresiasUsuariosController extends Controller
{
    public function index()
    {
        $membresiasUsuarios = membresiasUsuarios::all();
        $membresias = Membresias::all();
        $usuarios = \App\Models\Usuarios::where('estado', 'Activo')->get();
        return view('membresias.membresiasUsuarios', compact('membresiasUsuarios', 'membresias', 'usuarios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:255',
            'membresia_id' => 'required|exists:membresias,id',
            'precio' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:Activo,Inactivo'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Obtener el nombre de la membresía
        $membresia = Membresias::find($request->membresia_id);

        membresiasUsuarios::create([
            'usuario' => $request->usuario,
            'membresia_id' => $request->membresia_id,
            'membresia' => $membresia->nombre,
            'precio' => $request->precio,
            'fecha_pago' => $request->fecha_pago,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado
        ]);

        return redirect()->route('membresias_usuarios.index')
            ->with('success', 'Membresía de usuario creada correctamente');
    }

    public function update(Request $request, membresiasUsuarios $membresias_usuario)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:255',
            'membresia_id' => 'required|exists:membresias,id',
            'precio' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:Activo,Inactivo'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Obtener el nombre de la membresía
        $membresia =Membresias::find($request->membresia_id);
        
        $membresias_usuario->update([
            'usuario' => $request->usuario,
            'membresia_id' => $request->membresia_id,
            'membresia' => $membresia->nombre,
            'precio' => $request->precio,
            'fecha_pago' => $request->fecha_pago,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado
        ]);

        return redirect()->route('membresias_usuarios.index')
            ->with('success', 'Membresía de usuario actualizada correctamente');
    }

    public function destroy(membresiasUsuarios $membresias_usuario)
    {
        $membresias_usuario->delete();
        return redirect()->route('membresias_usuarios.index')
            ->with('success', 'Membresía de usuario eliminada correctamente');
    }
}