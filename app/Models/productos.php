<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'precio',
        'stock',
        'categoria_id',
        'descripcion',
        'estado',
    ];

    public function getEstadoAttribute($value)
    {
        return $value === 'Activo' ? 'Activo' : 'Inactivo';
    }

    // Relación con categorías
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categorias::class, 'categoria_id');
    }
}