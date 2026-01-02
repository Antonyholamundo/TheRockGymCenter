<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EliminarColumnasActivoAccionesDeRoles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'activo')) {
                $table->dropColumn('activo');
            }
            if (Schema::hasColumn('roles', 'acciones')) {
                $table->dropColumn('acciones');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('activo')->nullable();
            $table->string('acciones')->nullable();
        });
    }
};
