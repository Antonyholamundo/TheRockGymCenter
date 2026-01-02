<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class membresiasUsuarios extends Model
{
    protected $fillable = [
        'usuario',
        'membresia',
        'membresia_id',
        'precio',
        'fecha_pago',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function membresia()
    {
        return $this->belongsTo(Membresias::class, 'membresia_id');
    }
}
