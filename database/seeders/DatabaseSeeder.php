<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\ProductoSeeder;
use Database\Seeders\ClienteSeeder;
use Database\Seeders\ProveedorSeeder;
use Database\Seeders\MonedaSeeder;
use Database\Seeders\VentaSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador por defecto (no duplicar)
        User::firstOrCreate(
            ['email' => 'admin@irmmaquinarias.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'tipo' => 'admin',
                'activo' => true
            ]
        );

        // Configuraciones iniciales del sistema (si la tabla existe)
        $configuraciones = [
            [
                'clave' => 'razon_social',
                'valor' => 'IRM MAQUINARIAS',
                'tipo' => 'texto',
                'descripcion' => 'Razón social de la empresa'
            ],
            [
                'clave' => 'ruc',
                'valor' => '20000000000',
                'tipo' => 'texto',
                'descripcion' => 'RUC de la empresa'
            ],
            [
                'clave' => 'direccion',
                'valor' => 'Dirección pendiente',
                'tipo' => 'texto',
                'descripcion' => 'Dirección fiscal de la empresa'
            ],
            [
                'clave' => 'telefono',
                'valor' => '',
                'tipo' => 'texto',
                'descripcion' => 'Teléfono de contacto'
            ],
            [
                'clave' => 'email',
                'valor' => 'contacto@irmmaquinarias.com',
                'tipo' => 'texto',
                'descripcion' => 'Email de contacto'
            ],
            [
                'clave' => 'serie_boleta',
                'valor' => 'B001',
                'tipo' => 'texto',
                'descripcion' => 'Serie para boletas de venta'
            ],
            [
                'clave' => 'serie_factura',
                'valor' => 'F001',
                'tipo' => 'texto',
                'descripcion' => 'Serie para facturas'
            ],
            [
                'clave' => 'igv',
                'valor' => '0.18',
                'tipo' => 'numero',
                'descripcion' => 'Porcentaje de IGV actual'
            ],
            [
                'clave' => 'logo',
                'valor' => '',
                'tipo' => 'texto',
                'descripcion' => 'Ruta del logo de la empresa'
            ],
            [
                'clave' => 'alerta_stock',
                'valor' => 'true',
                'tipo' => 'booleano',
                'descripcion' => 'Activar alertas de stock bajo'
            ]
        ];

        if (\Illuminate\Support\Facades\Schema::hasTable('configuraciones')) {
            foreach ($configuraciones as $config) {
                Configuracion::firstOrCreate(['clave' => $config['clave']], $config);
            }
        }

        // Seed adicionales
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
            ClienteSeeder::class,
            ProveedorSeeder::class,
            MonedaSeeder::class,
            VentaSeeder::class
        ]);
    }
}
