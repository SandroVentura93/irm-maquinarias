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
        $ventas = Venta::with('cliente')->orderByDesc('id')->paginate(15);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        return view('ventas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'producto_id.*' => 'required|exists:productos,id',
            'cantidad.*' => 'required|numeric|min:0.01',
            'precio_unitario.*' => 'required|numeric|min:0',
            'descuento_pct.*' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $descuento_total = 0;
            $recargo_total = 0;

            // compute totals per line
            foreach ($request->producto_id as $i => $pid) {
                $cant = floatval($request->cantidad[$i]);
                $precio = floatval($request->precio_unitario[$i]);
                $desc_pct = floatval($request->descuento_pct[$i] ?? 0);
                $recargo = floatval($request->recargo[$i] ?? 0);

                $desc_monto = ($precio * ( $desc_pct / 100 )) * $cant; // descuento por % sobre precio unitario
                $line_sub = ($precio * $cant) - $desc_monto + $recargo;

                $subtotal += ($precio * $cant);
                $descuento_total += $desc_monto;
                $recargo_total += $recargo;
            }

            $total = $subtotal - $descuento_total + $recargo_total;

            $venta = Venta::create([
                'fecha' => $request->fecha ?? now(),
                'cliente_id' => $request->cliente_id,
                'usuario_id' => Auth::id(),
                'descripcion' => $request->descripcion,
                'tipo_venta' => $request->tipo_venta,
                'tipo_comprobante' => $request->tipo_comprobante,
                'serie' => $request->serie,
                'correlativo' => $request->correlativo,
                'moneda_id' => $request->moneda_id,
                'tc_usado' => $request->tc_usado,
                'subtotal' => $subtotal,
                'descuento_total' => $descuento_total,
                'recargo_total' => $recargo_total,
                'total' => $total,
                'estado' => 'registrado',
                'omitir_fe' => $request->omitir_fe ?? false,
                'observaciones' => $request->observaciones,
            ]);

            // Insert detalles
            foreach ($request->producto_id as $i => $pid) {
                $cant = floatval($request->cantidad[$i]);
                $precio = floatval($request->precio_unitario[$i]);
                $desc_pct = floatval($request->descuento_pct[$i] ?? 0);
                $desc_monto = ($precio * ($desc_pct / 100)) * $cant;
                $recargo = floatval($request->recargo[$i] ?? 0);
                $line_sub = ($precio * $cant) - $desc_monto + $recargo;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $pid,
                    'codigo_producto' => $request->codigo_producto[$i] ?? null,
                    'numero_parte' => $request->numero_parte[$i] ?? null,
                    'descripcion' => $request->descripcion_detalle[$i] ?? null,
                    'cantidad' => $cant,
                    'precio_unitario' => $precio,
                    'descuento' => $desc_monto,
                    'recargo' => $recargo,
                    'subtotal' => $line_sub,
                ]);

                // Opcional: descontar stock del producto (si quieres)
                // Producto::where('id', $pid)->decrement('stock', $cant);
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success','Venta registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error','Error: '.$e->getMessage());
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
    $venta->load(['cliente', 'usuario', 'moneda', 'detalles.producto']);
    $pdf = \PDF::loadView('ventas.ticket', compact('venta'));
    return $pdf->stream('ticket_venta_'.$venta->id.'.pdf');
    }
}