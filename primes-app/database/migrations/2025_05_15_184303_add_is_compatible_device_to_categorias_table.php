<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCompatibleDeviceToCategoriasTable extends Migration
{
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->boolean('is_compatible_device')->default(true)->after('esta_activa');
        });
    }

    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('is_compatible_device');
        });
    }
}

