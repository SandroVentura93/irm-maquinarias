<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonedasTable extends Migration
{
    public function up()
    {
        Schema::create('monedas', function (Blueprint $table) {
            $table->tinyInteger('id')->unsigned()->autoIncrement();
            $table->string('codigo', 3)->unique();
            $table->string('nombre', 50);
            $table->string('simbolo', 5);
        });

        // Insertar datos iniciales
        DB::table('monedas')->insert([
            ['id' => 1, 'codigo' => 'PEN', 'nombre' => 'Soles', 'simbolo' => 'S/'],
            ['id' => 2, 'codigo' => 'USD', 'nombre' => 'Dólares', 'simbolo' => '$']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('monedas');
    }
}