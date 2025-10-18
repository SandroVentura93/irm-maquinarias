<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasDetalleTable extends Migration
{
    public function up()
    {
        Schema::create('ventas_detalle', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('venta_id')->unsigned();
            $table->bigInteger('producto_id')->unsigned();
            $table->string('codigo_producto', 60)->nullable();
            $table->string('numero_parte', 60)->nullable();
            $table->string('descripcion', 255);
            $table->decimal('cantidad', 14, 3);
            $table->decimal('precio_unitario', 14, 4);
            $table->decimal('descuento', 14, 4)->default(0);
            $table->decimal('recargo', 14, 4)->default(0);
            $table->decimal('subtotal', 14, 4);

            $table->foreign('venta_id')->references('id')->on('ventas');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas_detalle');
    }
}