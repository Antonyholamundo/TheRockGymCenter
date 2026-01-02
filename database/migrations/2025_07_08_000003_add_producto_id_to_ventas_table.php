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
        Schema::table('ventas', function (Blueprint $table) {
            // Drop the old string column
            if (Schema::hasColumn('ventas', 'producto')) {
                $table->dropColumn('producto');
            }

            // Add the new foreign key column
            // Assuming 'productos' table exists and has 'id'
            $table->unsignedBigInteger('producto_id')->after('vendedor');
            
            // Add constraint
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('restrict'); 
            // 'restrict' prevents deleting a product if it has sales history
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
            $table->string('producto'); // Restore string column
        });
    }
};
