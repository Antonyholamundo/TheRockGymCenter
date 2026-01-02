<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membresias_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario')->unique();
       
            $table->string('membresia');
            $table->string('precio');
            $table->date('fecha_pago')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('activo'); // 'activo', 'inactivo'
            $table->string('acciones')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias_usuarios');
    }
};
