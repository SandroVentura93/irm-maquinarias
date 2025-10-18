<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Cliente;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all();
        $clientes = Cliente::all();

        if ($productos->isEmpty() || $clientes->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 25; $i++) {
            $cliente = $clientes->random();
            $fecha = Carbon::now()->subDays(rand(0, 30));

            $venta = Venta::create([
                'cliente_id' => $cliente->id,
                'usuario_id' => 1,
                'fecha' => $fecha,
                'total' => 0,
                'tipo_comprobante' => 'boleta',
                'tipo_venta' => 'contado',
                // 'estado' must match the enum on the ventas table: pendiente, deuda, cancelado, anulado
                'estado' => 'pendiente'
            ]);

            $total = 0;
            $cantidadItems = rand(1, 5);
            for ($j = 0; $j < $cantidadItems; $j++) {
                $prod = $productos->random();
                $cantidad = rand(1, 3);
                $precio = $prod->precio_venta ?? rand(100,500);
                $sub = $precio * $cantidad;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $prod->id,
                    // ventas_detalle requires a non-null descripcion column
                    'descripcion' => $prod->nombre ?? $prod->descripcion ?? 'Producto',
                    // cantidad should match decimal(14,3) — store with 3 decimals
                    'cantidad' => number_format($cantidad, 3, '.', ''),
                    'precio_unitario' => $precio,
                    // descuento and recargo have defaults in table; subtotal kept as decimal
                    'subtotal' => $sub
                ]);

                $total += $sub;
            }

            $venta->total = $total;
            $venta->save();
        }
    }
}
