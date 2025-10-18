<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\CajaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Excel;
use PDF;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrador,gerente');
    }

    public function ventas(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? Carbon::now()->startOfMonth();
        $fechaFin = $request->fecha_fin ?? Carbon::now();

        $ventas = Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with(['cliente', 'usuario'])
            ->get();

        $totales = [
            'contado' => $ventas->where('tipo_venta', 'contado')->sum('total'),
            'credito' => $ventas->where('tipo_venta', 'credito')->sum('total')
        ];

        return view('reportes.ventas', compact('ventas', 'totales', 'fechaInicio', 'fechaFin'));
    }

    public function productos()
    {
        $productos = Producto::with(['categoria', 'proveedor'])
            ->where('activo', 1)
            ->get();

        $estadisticas = [
            'total_productos' => $productos->count(),
            'stock_bajo' => $productos->where('stock_actual', '<=', DB::raw('stock_minimo'))->count(),
            'valor_inventario' => $productos->sum(DB::raw('stock_actual * precio_unitario'))
        ];

        return view('reportes.productos', compact('productos', 'estadisticas'));
    }

    public function clientes()
    {
        $clientes = Cliente::withCount(['ventas'])
            ->withSum('ventas', 'total')
            ->get();

        return view('reportes.clientes', compact('clientes'));
    }

    public function caja(Request $request)
    {
        $fecha = $request->fecha ?? Carbon::now()->format('Y-m-d');

        $movimientos = CajaMovimiento::whereDate('fecha', $fecha)
            ->orderBy('fecha')
            ->get();

        $totales = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'egresos' => $movimientos->where('tipo', 'egreso')->sum('monto')
        ];

        return view('reportes.caja', compact('movimientos', 'totales', 'fecha'));
    }

    public function exportar(Request $request, $tipo)
    {
        $data = [];
        $filename = '';

        switch ($request->reporte) {
            case 'ventas':
                $data = $this->prepararDatosVentas($request);
                $filename = 'ventas_' . Carbon::now()->format('Y-m-d');
                break;
            case 'productos':
                $data = $this->prepararDatosProductos();
                $filename = 'productos_' . Carbon::now()->format('Y-m-d');
                break;
            case 'clientes':
                $data = $this->prepararDatosClientes();
                $filename = 'clientes_' . Carbon::now()->format('Y-m-d');
                break;
            case 'caja':
                $data = $this->prepararDatosCaja($request);
                $filename = 'caja_' . Carbon::now()->format('Y-m-d');
                break;
        }

        if ($tipo === 'excel') {
            return Excel::download(new \App\Exports\GeneralExport($data), $filename . '.xlsx');
        } else {
            $pdf = PDF::loadView('reportes.pdf.' . $request->reporte, compact('data'));
            return $pdf->download($filename . '.pdf');
        }
    }

    private function prepararDatosVentas($request)
    {
        return Venta::whereBetween('fecha', [
                $request->fecha_inicio ?? Carbon::now()->startOfMonth(),
                $request->fecha_fin ?? Carbon::now()
            ])
            ->with(['cliente', 'usuario', 'detalles.producto'])
            ->get()
            ->map(function ($venta) {
                return [
                    'Fecha' => $venta->fecha->format('d/m/Y'),
                    'Comprobante' => $venta->tipo_comprobante . ' ' . $venta->serie . '-' . $venta->correlativo,
                    'Cliente' => $venta->cliente->nombre,
                    'Tipo' => $venta->tipo_venta,
                    'Total' => $venta->total
                ];
            });
    }

    private function prepararDatosProductos()
    {
        return Producto::with(['categoria', 'proveedor'])
            ->where('activo', 1)
            ->get()
            ->map(function ($producto) {
                return [
                    'Código' => $producto->codigo,
                    'Descripción' => $producto->descripcion,
                    'Categoría' => $producto->categoria->descripcion ?? '',
                    'Stock' => $producto->stock_actual,
                    'Precio' => $producto->precio_unitario
                ];
            });
    }

    private function prepararDatosClientes()
    {
        return Cliente::withCount(['ventas'])
            ->withSum('ventas', 'total')
            ->get()
            ->map(function ($cliente) {
                return [
                    'Cliente' => $cliente->nombre,
                    'DNI/RUC' => $cliente->dni ?? $cliente->ruc,
                    'Total Ventas' => $cliente->ventas_count,
                    'Monto Total' => $cliente->ventas_sum_total
                ];
            });
    }

    private function prepararDatosCaja($request)
    {
        return CajaMovimiento::whereDate('fecha', $request->fecha ?? Carbon::now())
            ->orderBy('fecha')
            ->get()
            ->map(function ($movimiento) {
                return [
                    'Hora' => $movimiento->fecha->format('H:i'),
                    'Tipo' => $movimiento->tipo,
                    'Descripción' => $movimiento->descripcion,
                    'Monto' => $movimiento->monto
                ];
            });
    }
}