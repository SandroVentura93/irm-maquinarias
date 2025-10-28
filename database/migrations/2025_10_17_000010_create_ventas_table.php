<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->default(DB::raw('CURRENT_DATE'));
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('descripcion')->nullable();
            $table->string('tipo_venta')->nullable();
            $table->string('tipo_comprobante')->nullable();
            $table->string('serie', 10)->nullable();
            $table->string('correlativo', 20)->nullable();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas');
            $table->decimal('tc_usado', 12, 2)->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento_total', 12, 2)->default(0);
            $table->decimal('recargo_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('estado')->default('registrado');
            $table->boolean('omitir_fe')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}