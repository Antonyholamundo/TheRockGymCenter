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
        // For Postgres, we need to use raw SQL for type casting if we want to preserve data correctly
        // But since we want 'Activo'/'Inactivo' instead of '1'/'0', explicit casting is best.
        
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE usuarios ALTER COLUMN estado TYPE VARCHAR(20) USING CASE WHEN estado THEN 'Activo' ELSE 'Inactivo' END");
            DB::statement("ALTER TABLE usuarios ALTER COLUMN estado SET DEFAULT 'Activo'");
        } else {
             Schema::table('usuarios', function (Blueprint $table) {
                // For SQLite/MySQL, this usually works, but data conversion might vary.
                // Given this is a fix, we'll enforce string type.
                $table->string('estado', 20)->default('Activo')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
             // It's hard to reverse perfectly without data loss (string -> boolean), 
             // but we can try generic boolean change.
             // This might fail if values are not convertable, but 'down' is rarely used in dev for fixes.
            $table->boolean('estado')->default(true)->change();
        });
    }
};
