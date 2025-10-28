<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaProductoController extends Controller
{
    // Muestra los productos de una venta
    public function show($venta_id)
    {
        $venta = Venta::with('detalles.producto')->findOrFail($venta_id);
        return view('ventas.productos', compact('venta'));
    }
}
