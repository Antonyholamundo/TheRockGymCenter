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
        Schema::table('permisos', function (Blueprint $table) {
            // Si tienes una columna antigua, elimínala
            if (Schema::hasColumn('permisos', 'usuario')) {
                $table->dropColumn('usuario');
            }
            if (Schema::hasColumn('permisos', 'cedula')) {
                $table->dropColumn('cedula');
            }
            // Agrega la clave foránea
            $table->unsignedBigInteger('usuario_id')->after('id');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {
            //
        });
    }
};
