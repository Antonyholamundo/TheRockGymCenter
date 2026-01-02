<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresias extends Model
{
    protected $table = 'membresias';
    protected $fillable = [
        'nombre',
        'precio',       
        'descripcion',
        'fecha_creacion',
        'estado',
    ];
}
