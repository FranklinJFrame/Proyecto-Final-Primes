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
            $table->id();
            $table->foreignId('ClienteID')->constrained('clientes');
            $table->dateTime('FechaPedido')->useCurrent();
            $table->foreignId('DireccionEntregaID')->constrained('direcciones');
            $table->foreignId('EstadoID')->constrained('estados_pedidos');
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
