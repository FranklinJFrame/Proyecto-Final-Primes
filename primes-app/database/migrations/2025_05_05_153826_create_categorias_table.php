<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void {
        Schema::create('categorias', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->string('nombre');
            $tabla->string('slug')->unique();
            $tabla->string('imagen')->nullable();
            $tabla->boolean('esta_activa')->default(true);
            $tabla->timestamps();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void {
        Schema::dropIfExists('categorias');
    }
};

