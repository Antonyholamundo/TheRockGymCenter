<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $fillable = ['usuario_id', 'rol', 'fecha_asignacion'];

    public function usuario() {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
