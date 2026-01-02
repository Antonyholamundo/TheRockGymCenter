<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membresias_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('membresia_id')->after('usuario');

            // Si quieres clave foránea:
            $table->foreign('membresia_id')->references('id')->on('membresias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('membresias_usuarios', function (Blueprint $table) {
            $table->dropForeign(['membresia_id']);
            $table->dropColumn('membresia_id');
        });
    }
};
