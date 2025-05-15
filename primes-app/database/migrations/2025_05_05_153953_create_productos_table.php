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
        Schema::create('productos', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $tabla->foreignId('marca_id')->constrained('marcas')->cascadeOnDelete();
            $tabla->string('nombre');
            $tabla->string('slug')->unique();
            $tabla->json('imagenes')->nullable();
            $tabla->longText('descripcion')->nullable();
            $tabla->decimal('precio', 10, 2);
            $tabla->string('moneda')->default('USD');
            $tabla->integer('cantidad')->default(0);
            $tabla->boolean('esta_activo')->default(true);
            $tabla->boolean('es_destacado')->default(false);
            $tabla->boolean('en_stock')->default(true);
            $tabla->boolean('en_oferta')->default(false);
            $tabla->timestamps();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};