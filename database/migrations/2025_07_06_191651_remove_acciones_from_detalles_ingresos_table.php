<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalles_ingresos', function (Blueprint $table) {
            $table->dropColumn('acciones');
        });
    }

    public function down(): void
    {
        Schema::table('detalles_ingresos', function (Blueprint $table) {
            $table->string('acciones')->nullable(); // o usa el tipo original de la columna
        });
    }
};
