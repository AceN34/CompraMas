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
        Schema::table('lote', function (Blueprint $table) {
            $table->renameColumn('productos_id', 'producto_id');
        });
    }

    public function down(): void
    {
        Schema::table('lote', function (Blueprint $table) {
            $table->renameColumn('producto_id', 'productos_id');
        });
    }

};
