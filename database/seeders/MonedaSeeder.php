<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Moneda;

class MonedaSeeder extends Seeder
{
    public function run()
    {
        $monedas = [
            ['codigo' => 'PEN', 'nombre' => 'Soles', 'simbolo' => 'S/.', 'activo' => 1],
            ['codigo' => 'USD', 'nombre' => 'Dólar', 'simbolo' => '$', 'activo' => 1],
            ['codigo' => 'EUR', 'nombre' => 'Euro', 'simbolo' => '€', 'activo' => 0]
        ];

        foreach ($monedas as $m) {
            \Illuminate\Support\Facades\DB::table('monedas')->updateOrInsert(
                ['codigo' => $m['codigo']],
                ['nombre' => $m['nombre'], 'simbolo' => $m['simbolo']]
            );
        }
    }
}
