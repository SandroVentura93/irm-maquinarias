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

        foreach ($categorias as $cat) {
            DB::table('categorias')->updateOrInsert(
                ['descripcion' => $cat],
                ['descripcion' => $cat]
            );
        }
    }
}
