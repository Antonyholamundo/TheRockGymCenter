<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('membresias');
    }

    public function down(): void
    {
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            // Aquí debes poner las columnas originales si quieres restaurarla con `rollback`
            $table->string('nombre');
            $table->decimal('precio', 8, 2);
            $table->timestamps();
        });
    }
};
