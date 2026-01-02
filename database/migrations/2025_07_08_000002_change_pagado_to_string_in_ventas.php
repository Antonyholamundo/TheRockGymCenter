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
            DB::statement("ALTER TABLE ventas ALTER COLUMN pagado TYPE VARCHAR(20) USING CASE WHEN pagado THEN 'Pagado' ELSE 'Pendiente' END");
            DB::statement("ALTER TABLE ventas ALTER COLUMN pagado SET DEFAULT 'Pagado'");
        } else {
             Schema::table('ventas', function (Blueprint $table) {
                $table->string('pagado', 20)->default('Pagado')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->boolean('pagado')->default(true)->change();
        });
    }
};
