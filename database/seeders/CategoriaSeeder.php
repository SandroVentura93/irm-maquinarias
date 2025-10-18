<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            'Excavadoras',
            'Cargadores',
            'Compactadoras',
            'Generadores',
            'Herramientas',
            'Accesorios'
        ];

        $i = 1;
        foreach ($categorias as $cat) {
            DB::table('categorias')->updateOrInsert(
                ['codigo' => 'C' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                ['descripcion' => $cat]
            );
            $i++;
        }
    }
}
