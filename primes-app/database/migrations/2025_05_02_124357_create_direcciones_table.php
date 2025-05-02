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
            $table->foreignId('ClienteID')->constrained('clientes');
            $table->foreignId('CodigoPostalID')->constrained('codigos_postales');
            $table->string('Colonia');
            $table->string('Referencias', 500)->nullable();
            $table->timestamps();
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
