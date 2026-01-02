<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallesIngresos extends Model
{
    protected $fillable = [
        'ingreso',
        'producto_id',
        'cantidad',
    ];
      public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
