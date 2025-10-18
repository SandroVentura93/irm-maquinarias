<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('proveedor')->latest()->paginate(15);
        return view('pedidos.list', compact('pedidos'));
    }

    public function create(Request $request)
    {
        $proveedores = Proveedor::all();
        $productos = Producto::all();

        // If producto_id query present (from alert), preselect it
        $productoSeleccionado = null;
        if ($request->filled('producto_id')) {
            $productoSeleccionado = Producto::find($request->producto_id);
        }

        return view('pedidos.create', compact('proveedores', 'productos', 'productoSeleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'observaciones' => 'nullable|string',
            'lineas' => 'required|array|min:1',
            'lineas.*.producto_id' => 'nullable|exists:productos,id',
            'lineas.*.descripcion' => 'required|string',
            'lineas.*.cantidad' => 'required|numeric|min:0.001',
            'lineas.*.precio_unitario' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'proveedor_id' => $request->proveedor_id,
                'observaciones' => $request->observaciones,
                'estado' => 'pendiente',
            ]);

            $total = 0;
            foreach ($request->lineas as $ln) {
                $precio = isset($ln['precio_unitario']) ? (float)$ln['precio_unitario'] : 0;
                $cantidad = (float)$ln['cantidad'];
                $subtotal = $precio * $cantidad;
                $detalle = PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $ln['producto_id'] ?? null,
                    'codigo_producto' => $ln['codigo'] ?? null,
                    'descripcion' => $ln['descripcion'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $pedido->total = $total;
            $pedido->save();
            DB::commit();
            return redirect()->route('pedidos.show', $pedido->id)->with('success', 'Pedido creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('No se pudo crear el pedido: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pedido = Pedido::with('detalles.producto', 'proveedor')->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }
}
