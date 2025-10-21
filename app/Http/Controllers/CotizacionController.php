<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    // Mostrar detalle de cotización
    public function show($id)
    {
        $cotizacion = \App\Models\Cotizacion::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    // Editar cotización
    public function edit($id)
    {
        $cotizacion = \App\Models\Cotizacion::findOrFail($id);
        $clientes = \App\Models\Cliente::all();
        return view('cotizaciones.edit', compact('cotizacion', 'clientes'));
    }

    // Actualizar cotización
    public function update(Request $request, $id)
    {
        $cotizacion = \App\Models\Cotizacion::findOrFail($id);
        $cotizacion->update($request->only(['cliente_id', 'fecha', 'estado', 'observaciones']));
        return redirect()->route('cotizaciones.show', $cotizacion->id)->with('success', 'Cotización actualizada');
    }

    // Eliminar cotización
    public function destroy($id)
    {
        $cotizacion = \App\Models\Cotizacion::findOrFail($id);
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada');
    }
    // Listar cotizaciones
    public function index()
    {
        $cotizaciones = \App\Models\Cotizacion::with('cliente')->orderByDesc('fecha')->paginate(20);
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    // Mostrar formulario de cotización
    public function create()
    {
        $clientes = \App\Models\Cliente::all();
        $productos = \App\Models\Producto::where('activo', 1)->get();
        return view('cotizaciones.create', compact('clientes', 'productos'));
    }

    // Guardar cotización
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $cotizacion = \App\Models\Cotizacion::create($request->only(['cliente_id', 'fecha', 'total', 'estado', 'observaciones']));
            foreach ($request->detalles as $detalle) {
                \App\Models\CotizacionDetalle::create([
                    'cotizacion_id' => $cotizacion->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }
        });
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización registrada');
    }

    // Convertir cotización en venta
    public function convertirAVenta($id)
    {
        $cotizacion = \App\Models\Cotizacion::with('detalles')->findOrFail($id);
        if ($cotizacion->estado !== 'aprobada') {
            return back()->with('error', 'Solo cotizaciones aprobadas pueden convertirse en venta');
        }
        \DB::transaction(function () use ($cotizacion) {
            $venta = \App\Models\Venta::create([
                'cliente_id' => $cotizacion->cliente_id,
                'fecha' => now(),
                'total' => $cotizacion->total,
                'estado' => 'pendiente',
            ]);
            foreach ($cotizacion->detalles as $detalle) {
                \App\Models\VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal' => $detalle->subtotal,
                ]);
                // Actualizar stock del producto
                \App\Models\Producto::where('id', $detalle->producto_id)->decrement('stock_actual', $detalle->cantidad);
            }
            $cotizacion->estado = 'convertida';
            $cotizacion->save();
        });
        return redirect()->route('ventas.index')->with('success', 'Cotización convertida en venta');
    }
}
