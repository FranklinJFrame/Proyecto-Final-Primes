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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id('DireccionID');
            $table->string('NumeroExterior', 50);
            $table->string('Calle');
            $table->unsignedBigInteger('ClienteID');
            $table->foreign('ClienteID')->references('ClienteID')->on('clientes')->onDelete('cascade');
            $table->timestamps(); // Agregado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
