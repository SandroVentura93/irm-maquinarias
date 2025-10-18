<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 15; $i++) {
            $dni = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $now = now();
            \Illuminate\Support\Facades\DB::table('clientes')->insert([
                'dni' => $dni,
                'ruc' => null,
                'nombre' => 'Cliente' . $i,
                'apellido' => 'Apellido' . $i,
                'direccion' => 'Dirección ' . $i,
                'region_id' => null,
                'provincia_id' => null,
                'distrito_id' => null,
                'tipo_cliente' => 'cliente_final',
                'telefono' => '999' . rand(100000, 999999),
                'email' => 'cliente' . $i . '@example.com',
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
