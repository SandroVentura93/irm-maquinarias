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
            'metodo_pago' => 'required|string',
            'items_json' => 'required|string',
        ]);

        // Decodificar los items desde el campo oculto
        $items = json_decode($request->items_json, true) ?? [];

        DB::beginTransaction();
        try {
            $venta = Venta::create([
                'fecha' => now(),
                'cliente_id' => $request->cliente_id,
                'usuario_id' => auth()->id(),
                'tipo_venta' => $request->tipo_venta,
                'metodo_pago' => $request->metodo_pago,
                // Los demás campos se envían como null/opcional
                'descripcion' => $request->descripcion ?? null,
                'tipo_comprobante' => $request->tipo_comprobante ?? null,
                'serie' => $request->serie ?? null,
                'correlativo' => $request->correlativo ?? null,
                'moneda_id' => $request->moneda_id ?? null,
                'tc_usado' => $request->tc_usado ?? null,
                'subtotal' => 0,
                'descuento_total' => 0,
                'recargo_total' => $request->recargo_total ?? 0,
                'total' => 0,
                'estado' => $request->estado ?? 'pendiente',
                'omitir_fe' => $request->omitir_fe ?? false,
                'observaciones' => $request->observaciones ?? null,
            ]);

            $subtotal = 0;
            $descuento_total = 0;
            foreach ($items as $item) {
                $precio_unitario = floatval($item['precio']);
                $descuento = floatval($item['descuento'] ?? 0);
                $cantidad = floatval($item['cantidad']);
                $precio_final = $precio_unitario - $descuento;
                $detalle_subtotal = $precio_final * $cantidad;
                $subtotal += $detalle_subtotal;
                $descuento_total += $descuento * $cantidad;
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'descripcion' => $item['descripcion'] ?? '',
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio_unitario,
                    'descuento' => $descuento,
                    'recargo' => 0,
                    'subtotal' => $detalle_subtotal,
                ]);
            }
            $venta->subtotal = $subtotal;
            $venta->descuento_total = $descuento_total;
            $venta->total = $subtotal + ($request->recargo_total ?? 0);
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