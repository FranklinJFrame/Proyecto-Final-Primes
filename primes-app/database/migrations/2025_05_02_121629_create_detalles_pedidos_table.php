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
        Schema::create('detalles_pedidos', function (Blueprint $table) {
            $table->id('DetalleID');
            $table->unsignedBigInteger('PedidoID');
            $table->foreign('PedidoID')->references('PedidoID')->on('pedidos')->onDelete('cascade');
            
            // Cambié de foreignId a unsignedBigInteger y referencié ProductoID
            $table->unsignedBigInteger('ProductoID');
            $table->foreign('ProductoID')->references('ProductoID')->on('productos')->onDelete('cascade');
            
            $table->integer('Cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_pedidos');
    }
};
