<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->string('dni', 8)->nullable()->unique('u_dni');
            $table->string('ruc', 11)->nullable()->unique('u_ruc');
            $table->string('nombre', 100);
            $table->string('apellido', 100)->nullable();
            $table->string('direccion', 150)->nullable();
            $table->smallInteger('region_id')->unsigned()->nullable();
            $table->integer('provincia_id')->unsigned()->nullable();
            $table->integer('distrito_id')->unsigned()->nullable();
            $table->enum('tipo_cliente', ['revendedor', 'cliente_final'])->default('cliente_final');
            $table->string('telefono', 30)->nullable();
            $table->string('email', 120)->nullable();

            $table->foreign('region_id')->references('id')->on('regiones');
            $table->foreign('provincia_id')->references('id')->on('provincias');
            $table->foreign('distrito_id')->references('id')->on('distritos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}