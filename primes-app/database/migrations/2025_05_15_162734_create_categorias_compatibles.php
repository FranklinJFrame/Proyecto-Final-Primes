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
        Schema::create('categorias_compatibles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
    $table->foreignId('compatible_category_id')->constrained('categorias')->onDelete('cascade');
    $table->timestamps();

    $table->unique(['categoria_id', 'compatible_category_id'], 'cat_compat_unique');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_compatibles');
    }
};
