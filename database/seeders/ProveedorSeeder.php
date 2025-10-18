<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 8; $i++) {
            $dni_ruc = str_pad(rand(20000000000, 20999999999), 11, '0', STR_PAD_LEFT);
            \Illuminate\Support\Facades\DB::table('proveedores')->updateOrInsert(
                ['dni_ruc' => $dni_ruc],
                [
                    'nombre_razon_social' => 'Proveedor ' . $i,
                    'lugar' => 'Lugar ' . $i,
                    'rubro' => 'Rubro ' . $i,
                    'telefono' => '01' . rand(1000000, 9999999),
                    'email' => 'proveedor' . $i . '@example.com'
                ]
            );
        }
    }
}
