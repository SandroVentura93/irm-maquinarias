<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposCambioTable extends Migration
{
    public function up()
    {
        Schema::create('tipos_cambio', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->tinyInteger('moneda_origen')->unsigned();
            $table->tinyInteger('moneda_destino')->unsigned();
            $table->date('fecha');
            $table->decimal('tipo_cambio', 12, 6);
            
            $table->unique(['moneda_origen', 'moneda_destino', 'fecha'], 'u_tc');
            $table->foreign('moneda_origen')->references('id')->on('monedas');
            $table->foreign('moneda_destino')->references('id')->on('monedas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_cambio');
    }
}