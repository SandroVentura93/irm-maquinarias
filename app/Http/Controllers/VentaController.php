<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\VentaDetalle;
use App\Models\Moneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ventas = Venta::with(['cliente', 'usuario'])->latest()->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::where('activo', 1)->get();
        $monedas = Moneda::all();
        return view('ventas.create', compact('clientes', 'productos', 'monedas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_venta' => 'required|in:contado,credito',
            'tipo_comprobante' => 'required|in:boleta,factura,ticket',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
            'productos.*.precio' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'usuario_id' => auth()->id(),
                'tipo_venta' => $request->tipo_venta,
                'tipo_comprobante' => $request->tipo_comprobante,
                'moneda_id' => $request->moneda_id ?? 1,
                'tc_usado' => $request->tc_usado,
                'subtotal' => 0,
                'descuento_total' => $request->descuento_total ?? 0,
                'total' => 0
            ]);

            $subtotal = 0;
            foreach ($request->productos as $producto) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'descuento' => $producto['descuento'] ?? 0,
                    'subtotal' => $producto['cantidad'] * $producto['precio']
                ]);
                $subtotal += $producto['cantidad'] * $producto['precio'];
            }

            $venta->subtotal = $subtotal;
            $venta->total = $subtotal - $venta->descuento_total;
            $venta->save();

            DB::commit();
            return redirect()->route('ventas.show', $venta)->with('success', 'Venta registrada exitosamente');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'detalles.producto', 'usuario']);
        return view('ventas.show', compact('venta'));
    }

    public function validarStock(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:0.01'
        ]);

        $producto = Producto::find($request->producto_id);
        $disponible = $producto->stock_actual >= $request->cantidad;

        return response()->json([
            'disponible' => $disponible,
            'stock_actual' => $producto->stock_actual
        ]);
    }

    public function aplicarDescuento(Request $request, Venta $venta)
    {
        $request->validate([
            'descuento' => 'required|numeric|min:0|max:' . $venta->subtotal
        ]);

        $venta->descuento_total = $request->descuento;
        $venta->total = $venta->subtotal - $request->descuento;
        $venta->save();

        return response()->json([
            'success' => true,
            'nuevo_total' => $venta->total
        ]);
    }

    public function generarComprobante(Venta $venta, $tipo)
    {
        $pdf = PDF::loadView('ventas.comprobantes.' . $tipo, compact('venta'));
        return $pdf->download($tipo . '_' . $venta->id . '.pdf');
    }

    public function imprimir(Venta $venta)
    {
        return view('ventas.imprimir', compact('venta'));
    }
}