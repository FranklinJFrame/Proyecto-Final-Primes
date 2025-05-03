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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('PedidoID');
            
            $table->unsignedBigInteger('ClienteID');
            $table->foreign('ClienteID')->references('ClienteID')->on('clientes')->onDelete('cascade');

            $table->dateTime('FechaPedido')->useCurrent();

            $table->unsignedBigInteger('DireccionEntregaID');
            $table->foreign('DireccionEntregaID')->references('DireccionID')->on('direcciones')->onDelete('cascade');

            $table->unsignedBigInteger('EstadoID');
            $table->foreign('EstadoID')->references('EstadoID')->on('estados_pedidos')->onDelete('cascade');  // Cambio realizado aquí

            $table->decimal('Total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
