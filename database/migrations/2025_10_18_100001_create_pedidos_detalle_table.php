<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pedidos_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->string('codigo_producto')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('cantidad', 14, 3)->default(0);
            $table->decimal('precio_unitario', 14, 4)->default(0);
            $table->decimal('subtotal', 14, 4)->default(0);
            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos_detalle');
    }
};
