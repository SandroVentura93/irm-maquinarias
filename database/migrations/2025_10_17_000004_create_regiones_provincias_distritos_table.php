<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionesProvinciasDistritosTable extends Migration
{
    public function up()
    {
        Schema::create('regiones', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('nombre', 80)->unique();
        });

        Schema::create('provincias', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->smallInteger('region_id')->unsigned();
            $table->string('nombre', 80);
            
            $table->unique(['region_id', 'nombre'], 'u_prov');
            $table->foreign('region_id')->references('id')->on('regiones');
        });

        Schema::create('distritos', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('provincia_id')->unsigned();
            $table->string('nombre', 80);
            
            $table->unique(['provincia_id', 'nombre'], 'u_dist');
            $table->foreign('provincia_id')->references('id')->on('provincias');
        });
    }

    public function down()
    {
        Schema::dropIfExists('distritos');
        Schema::dropIfExists('provincias');
        Schema::dropIfExists('regiones');
    }
}