<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaTable extends Migration
{
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->tinyInteger('id')->unsigned()->primary();
            $table->string('nombre', 150);
            $table->string('ruc', 11);
            $table->string('direccion', 200);
            $table->string('celulares', 100)->nullable();
            $table->string('correo', 120)->nullable();
        });

        // Insertar datos iniciales
        DB::table('empresa')->insert([
            'id' => 1,
            'nombre' => 'IRM',
            'ruc' => '20570639553',
            'direccion' => 'Av. Atahualpa 725, Cajamarca, Perú',
            'celulares' => '976390506 / 974179198',
            'correo' => null
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}