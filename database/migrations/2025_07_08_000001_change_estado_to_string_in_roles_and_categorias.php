<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // Fix for 'roles' table
            DB::statement("ALTER TABLE roles ALTER COLUMN estado TYPE VARCHAR(20) USING CASE WHEN estado THEN 'Activo' ELSE 'Inactivo' END");
            DB::statement("ALTER TABLE roles ALTER COLUMN estado SET DEFAULT 'Activo'");

            // Fix for 'categorias' table
            DB::statement("ALTER TABLE categorias ALTER COLUMN estado TYPE VARCHAR(20) USING CASE WHEN estado THEN 'Activo' ELSE 'Inactivo' END");
            DB::statement("ALTER TABLE categorias ALTER COLUMN estado SET DEFAULT 'Activo'");

        } else {
             Schema::table('roles', function (Blueprint $table) {
                $table->string('estado', 20)->default('Activo')->change();
            });

             Schema::table('categorias', function (Blueprint $table) {
                $table->string('estado', 20)->default('Activo')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('estado')->default(true)->change();
        });
        
        Schema::table('categorias', function (Blueprint $table) {
            $table->boolean('estado')->default(true)->change();
        });
    }
};
