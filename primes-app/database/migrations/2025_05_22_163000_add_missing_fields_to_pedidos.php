<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado_direccion')->nullable()->after('ciudad');
            $table->string('codigo_postal')->nullable()->after('estado_direccion');
            $table->string('stripe_payment_intent')->nullable()->after('codigo_postal');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['estado_direccion', 'codigo_postal', 'stripe_payment_intent']);
        });
    }
}; 