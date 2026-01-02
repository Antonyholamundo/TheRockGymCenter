<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado', // Agregado el campo estado
    ];

    public function getEstadoAttribute($value)
    {
        return $value ? 'Activo' : 'Inactivo';
    }
}
