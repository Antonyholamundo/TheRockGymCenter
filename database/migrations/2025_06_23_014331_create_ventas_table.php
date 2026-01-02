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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->string('vendedor');
            $table->string('producto');
            $table->decimal('precio', 10, 2);
            $table->date('fecha_venta');
            $table->boolean('pagado')->default(false);
            $table->date('fecha_pago')->nullable();
            $table->string('acciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
