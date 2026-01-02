<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalles_ingresos', function (Blueprint $table) {
            // Elimina la columna antigua si existe
            if (Schema::hasColumn('detalles_ingresos', 'producto')) {
                $table->dropColumn('producto');
            }

            // Agrega la columna nueva producto_id
            $table->unsignedBigInteger('producto_id')->after('ingreso');

            // Clave foránea (opcional pero recomendado)
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detalles_ingresos', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
            $table->string('producto'); // restaurar columna vieja si haces rollback
        });
    }
};
