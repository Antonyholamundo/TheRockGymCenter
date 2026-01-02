<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $fillable = [
        'cliente',
        'vendedor',
        'producto_id', // Changed from producto string
        'cantidad',
        'precio',
        'fecha_venta',
        'pagado',
        'fecha_pago',
    ];

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
