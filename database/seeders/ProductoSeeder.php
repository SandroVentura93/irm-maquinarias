<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $categorias = Categoria::pluck('id')->toArray();
        if (empty($categorias)) {
            $categorias = [1];
        }

        for ($i = 1; $i <= 20; $i++) {
            $codigo = 'P' . str_pad($i, 4, '0', STR_PAD_LEFT);
            \Illuminate\Support\Facades\DB::table('productos')->updateOrInsert(
                ['codigo' => $codigo],
                [
                    'nombre' => 'Producto ' . $i,
                    'descripcion' => 'Descripción del producto ' . $i,
                    'precio_compra' => rand(100, 1000),
                    'precio_venta' => rand(120, 1500),
                    'stock_actual' => rand(0, 50),
                    'stock_minimo' => rand(1, 10),
                    'marca' => 'Marca ' . rand(1,5),
                    'categoria' => $categorias[array_rand($categorias)],
                    'unidad_medida' => 'unidad',
                    'ubicacion' => 'Almacén 1',
                    'activo' => 1
                ]
            );
        }
    }
}
