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
        Schema::create('venta_producto', function (Blueprint $table) {
            $table->foreignId('venta_id')
                ->constrained('venta')
                ->onUpdate('cascade');
            $table->foreignId('producto_id')
                ->constrained('producto')
                ->onUpdate('cascade');
            $table->integer('cantidad');
            $table->timestamps();

            $table->primary(['venta_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_producto');
    }
};
