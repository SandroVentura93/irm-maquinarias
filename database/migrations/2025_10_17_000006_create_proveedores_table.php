<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->string('dni_ruc', 11)->nullable()->unique('u_dniruc');
            $table->string('nombre_razon_social', 160);
            $table->string('lugar', 120)->nullable();
            $table->string('rubro', 120)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 120)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}