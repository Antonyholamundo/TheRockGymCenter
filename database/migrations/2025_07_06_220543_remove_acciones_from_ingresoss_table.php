<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            if (Schema::hasColumn('ingresos', 'acciones')) {
                $table->dropColumn('acciones');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ingresoss', function (Blueprint $table) {
            $table->string('acciones')->nullable();
        });
    }
};
