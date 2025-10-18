<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioMovimientosTable extends Migration
{
    public function up()
    {
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->bigInteger('producto_id')->unsigned();
            $table->enum('tipo', ['entrada', 'salida', 'ajuste']);
            $table->enum('referencia_tipo', ['venta', 'compra', 'anulacion_venta', 'anulacion_compra', 'manual']);
            $table->bigInteger('referencia_id')->unsigned()->nullable();
            $table->decimal('cantidad', 14, 3);
            $table->decimal('stock_resultante', 14, 3);
            $table->string('observacion', 200)->nullable();

            $table->foreign('producto_id')->references('id')->on('productos');
            $table->index(['referencia_tipo', 'referencia_id'], 'k_ref');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventario_movimientos');
    }
}