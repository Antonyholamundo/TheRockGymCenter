<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingresos extends Model
{

    protected $fillable = [
        'cedula',
        'fecha',
        'detalles',

    ];

}
