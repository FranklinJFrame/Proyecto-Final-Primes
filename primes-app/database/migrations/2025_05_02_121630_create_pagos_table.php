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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('PagoID');
            $table->foreignId('PedidoID')->constrained('pedidos');
            $table->foreignId('MetodoPagoID')->constrained('metodo_pagos');
            $table->dateTime('FechaPago')->useCurrent();
            $table->decimal('Monto', 10, 2);
            $table->string('ReferenciaPago', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
