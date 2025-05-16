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
        Schema::create('productos_compatibles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
    $table->foreignId('compatible_with_id')->constrained('productos')->onDelete('cascade');
    $table->timestamps();

    $table->unique(['producto_id', 'compatible_with_id'], 'prod_compat_unique');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_compatibles');
    }
};
