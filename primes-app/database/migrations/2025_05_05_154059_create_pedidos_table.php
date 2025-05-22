<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $tabla->decimal('total_general', 10, 2)->nullable();
            $tabla->string('metodo_pago')->nullable();
            $tabla->string('estado_pago')->nullable();
            $tabla->enum('estado', ['nuevo', 'procesando', 'enviado', 'entregado', 'cancelado'])->default('nuevo');
            $tabla->string('moneda')->nullable();
            $tabla->decimal('costo_envio', 10, 2)->nullable();
            $tabla->string('metodo_envio')->nullable();
            $tabla->text('notas')->nullable();
            // Campos de dirección
            $tabla->string('nombre')->nullable();
            $tabla->string('apellido')->nullable();
            $tabla->string('telefono')->nullable();
            $tabla->string('direccion_calle')->nullable();
            $tabla->string('ciudad')->nullable();
            $tabla->string('estado_direccion')->nullable();
            $tabla->string('codigo_postal')->nullable();
            $tabla->string('stripe_payment_intent')->nullable();
            $tabla->timestamps();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
