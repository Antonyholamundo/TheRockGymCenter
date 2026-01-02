<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Usuarios extends Model
{
    protected $table = 'usuarios';
    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'estado',
        'fecha_nacimiento' 
    ];

    public $timestamps = true;

    // Define any relationships or additional methods here if needed


}
