<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasDetalleTable extends Migration
{
    public function up()
    {
        Schema::create('ventas_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->string('codigo_producto')->nullable();
            $table->string('numero_parte')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0); // monto (no %)
            $table->decimal('recargo', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas_detalle');
    }
}