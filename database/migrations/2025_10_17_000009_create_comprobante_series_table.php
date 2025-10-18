<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobanteSeriesTable extends Migration
{
    public function up()
    {
        Schema::create('comprobante_series', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->enum('tipo', ['boleta', 'factura', 'ticket', 'guia_remision', 'cotizacion']);
            $table->string('serie', 10);
            $table->integer('correlativo_actual')->unsigned()->default(0);
            $table->boolean('activo')->default(true);

            $table->unique(['tipo', 'serie'], 'u_ts');
        });

        // Insertar datos iniciales
        DB::table('comprobante_series')->insert([
            ['tipo' => 'boleta', 'serie' => 'B001', 'correlativo_actual' => 0, 'activo' => 1],
            ['tipo' => 'factura', 'serie' => 'F001', 'correlativo_actual' => 0, 'activo' => 1],
            ['tipo' => 'ticket', 'serie' => 'T001', 'correlativo_actual' => 0, 'activo' => 1],
            ['tipo' => 'guia_remision', 'serie' => 'GR01', 'correlativo_actual' => 0, 'activo' => 1],
            ['tipo' => 'cotizacion', 'serie' => 'COT1', 'correlativo_actual' => 0, 'activo' => 1]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('comprobante_series');
    }
}