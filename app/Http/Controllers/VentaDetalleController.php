<?php

namespace App\Http\Controllers;

use App\Models\VentaDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;

class VentaDetalleController extends Controller
{
    public function index()
    {
        $detalles = VentaDetalle::with('producto')->paginate(20);
        return view('ventadetalles.index', compact('detalles'));
    }

    public function show($id)
    {
        $detalle = VentaDetalle::with('producto')->findOrFail($id);
        return view('ventadetalles.show', compact('detalle'));
    }

    public function create()
    {
        $productos = Producto::where('activo', 1)->get();
        return view('ventadetalles.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:1',
            'precio_unitario' => 'required|numeric|min:0',
        ]);
        $detalle = VentaDetalle::create($request->all());
        return redirect()->route('ventadetalles.show', $detalle->id)->with('success', 'Detalle de venta registrado');
    }

    public function edit($id)
    {
        $detalle = VentaDetalle::findOrFail($id);
        $productos = Producto::where('activo', 1)->get();
        return view('ventadetalles.edit', compact('detalle', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:1',
            'precio_unitario' => 'required|numeric|min:0',
        ]);
        $detalle = VentaDetalle::findOrFail($id);
        $detalle->update($request->all());
        return redirect()->route('ventadetalles.show', $detalle->id)->with('success', 'Detalle de venta actualizado');
    }

    public function destroy($id)
    {
        $detalle = VentaDetalle::findOrFail($id);
        $detalle->delete();
        return redirect()->route('ventadetalles.index')->with('success', 'Detalle de venta eliminado');
    }
}
