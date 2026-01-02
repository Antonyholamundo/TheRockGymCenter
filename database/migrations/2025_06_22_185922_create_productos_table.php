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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');         // Nombre del producto
            $table->decimal('precio', 8, 2);  // Precio con 2 decimales
            $table->integer('stock');         // Stock disponible
            $table->unsignedBigInteger('categoria_id'); // ID de la categoría
            $table->string('descripcion');    // Descripción del producto
            $table->string('estado');         // Estado (Activo/Inactivo)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};