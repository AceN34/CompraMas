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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                ->constrained('cliente')
                ->onDelete('cascade');
            $table->string('nombre', 255);
            $table->string('direccion', 255);
            $table->string('ciudad', 255);
            $table->string('codigo_postal', 255);
            $table->string('telefono', 255);
            $table->string('comentarios', 255)->nullable();
            $table->decimal('total', 10);
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
