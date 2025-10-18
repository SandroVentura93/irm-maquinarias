<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->bigInteger('cliente_id')->unsigned()->nullable();
            $table->bigInteger('usuario_id')->unsigned();
            $table->string('descripcion', 255)->nullable();
            $table->enum('tipo_venta', ['contado', 'credito']);
            $table->enum('tipo_comprobante', ['boleta', 'factura', 'ticket', 'guia_remision', 'cotizacion']);
            $table->string('serie', 10)->nullable();
            $table->integer('correlativo')->unsigned()->nullable();
            $table->tinyInteger('moneda_id')->unsigned()->default(1);
            $table->decimal('tc_usado', 12, 6)->nullable();
            $table->decimal('subtotal', 14, 4)->default(0);
            $table->decimal('descuento_total', 14, 4)->default(0);
            $table->decimal('recargo_total', 14, 4)->default(0);
            $table->decimal('total', 14, 4)->default(0);
            $table->enum('estado', ['pendiente', 'deuda', 'cancelado', 'anulado'])->default('pendiente');
            $table->boolean('omitir_fe')->default(false);
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('moneda_id')->references('id')->on('monedas');

            $table->index(['estado', 'fecha']);
            $table->index(['cliente_id']);
            $table->index(['tipo_comprobante', 'serie', 'correlativo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}