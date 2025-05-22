<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->boolean('esta_activo')->default(true);
        });

        // Insertar métodos de pago por defecto
        DB::table('metodos_pago')->insert([
            ['nombre' => 'PayPal', 'codigo' => 'paypal', 'esta_activo' => true],
            ['nombre' => 'Tarjeta', 'codigo' => 'tarjeta', 'esta_activo' => true],
            ['nombre' => 'Pago contra entrega', 'codigo' => 'pce', 'esta_activo' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
}; 