<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->dateTime('fecha');
            $table->decimal('total', 12, 2)->default(0);
            $table->string('estado')->default('pendiente'); // pendiente, aprobada, rechazada
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cliente_id')->references('id')->on('clientes');
        });

        Schema::create('cotizacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotizacion_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('cotizacion_id')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cotizacion_detalles');
        Schema::dropIfExists('cotizaciones');
    }
}
