<?php


namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('usuarios.usuario', [
            'usuarios' => Usuarios::all()
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
            'cedula' => 'required|string|max:20|regex:/^[0-9]+$/|unique:usuarios,cedula',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Usuarios::create($request->all());

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'cedula' => 'required|string|max:20|regex:/^[0-9]+$/|unique:usuarios,cedula,' . $id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $id,
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $usuario = Usuarios::findOrFail($id);
        $usuario->update($request->all());

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Usuarios::destroy($id);
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
