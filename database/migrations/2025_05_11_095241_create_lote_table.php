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
        Schema::create('lote', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('productos_id')
                ->constrained('productos')
                ->onUpdate('cascade');
            $table->string('cantidad');
            $table->date('fecha_caducidad')->nullable();
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote');
    }
};
