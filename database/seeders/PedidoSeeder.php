<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\PedidoDetalle;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        // Ensure there's at least one proveedor
        $prov = Proveedor::first();
        if (! $prov) {
            $prov = Proveedor::create([
                'nombre_razon_social' => 'Proveedor Prueba S.A.',
                'telefono' => '999999999',
                'direccion' => 'Av. Prueba 123'
            ]);
        }

        // Ensure there's at least one producto
        $prod = Producto::first();
        if (! $prod) {
            $prod = Producto::create([
                'codigo' => 'TEST-001',
                'nombre' => 'Producto de prueba',
                'descripcion' => 'Generado por PedidoSeeder',
                'precio_compra' => 10,
                'precio_venta' => 15,
                'stock_actual' => 1,
                'stock_minimo' => 5,
                'activo' => 1
            ]);
        }

        // Create a sample pedido
        $pedido = Pedido::create([
            'proveedor_id' => $prov->id,
            'observaciones' => 'Pedido generado por seed para pruebas',
            'estado' => 'pendiente',
            'total' => 0
        ]);

        $detalle = PedidoDetalle::create([
            'pedido_id' => $pedido->id,
            'producto_id' => $prod->id,
            'codigo_producto' => $prod->codigo,
            'descripcion' => $prod->nombre,
            'cantidad' => 10,
            'precio_unitario' => $prod->precio_compra,
            'subtotal' => 10 * $prod->precio_compra
        ]);

        $pedido->total = $detalle->subtotal;
        $pedido->save();
    }
}
