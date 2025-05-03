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
            $table->id('PagoID');  // Definición de la clave primaria para 'pagos'
            $table->unsignedBigInteger('PedidoID');  // Definición de la columna 'PedidoID'
            $table->unsignedBigInteger('MetodoPagoID');  // Definición de la columna 'MetodoPagoID'
            $table->dateTime('FechaPago')->useCurrent();  // Fecha del pago con valor por defecto
            $table->decimal('Monto', 10, 2);  // Monto del pago
            $table->string('ReferenciaPago', 255)->nullable();  // Referencia opcional del pago
            
            // Definir las claves foráneas correctamente
            $table->foreign('PedidoID')->references('PedidoID')->on('pedidos')->onDelete('cascade');
            $table->foreign('MetodoPagoID')->references('MetodoPagoID')->on('metodos_pagos')->onDelete('cascade');
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
