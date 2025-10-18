<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\VentasExport;
use App\Exports\ProductosExport;
use App\Exports\MovimientosExport;

class ReporteService
{
    public function generarReporteVentas($filtros)
    {
        $query = Venta::with(['cliente', 'detalles.producto', 'usuario'])
            ->where('estado', 'completada');

        if (isset($filtros['desde'])) {
            $query->whereDate('fecha_emision', '>=', $filtros['desde']);
        }

        if (isset($filtros['hasta'])) {
            $query->whereDate('fecha_emision', '<=', $filtros['hasta']);
        }

        if (isset($filtros['cliente_id'])) {
            $query->where('cliente_id', $filtros['cliente_id']);
        }

        if (isset($filtros['tipo_documento'])) {
            $query->where('tipo_documento', $filtros['tipo_documento']);
        }

        $ventas = $query->orderBy('fecha_emision', 'desc')->get();

        if ($filtros['formato'] == 'pdf') {
            $pdf = PDF::loadView('reportes.ventas_pdf', [
                'ventas' => $ventas,
                'filtros' => $filtros,
                'totales' => $this->calcularTotalesVentas($ventas)
            ]);

            return $pdf->stream('reporte_ventas.pdf');
        }

        return Excel::download(new VentasExport($ventas), 'reporte_ventas.xlsx');
    }

    public function generarReporteProductos($filtros)
    {
        $query = Producto::with('categoria');

        if (isset($filtros['categoria_id'])) {
            $query->where('categoria_id', $filtros['categoria_id']);
        }

        if (isset($filtros['stock']) && $filtros['stock'] == 'bajo') {
            $query->whereRaw('stock_actual <= stock_minimo');
        }

        $productos = $query->orderBy('codigo')->get();

        foreach ($productos as $producto) {
            // Calcular estadísticas de ventas
            $ventas = DB::table('venta_detalles')
                ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
                ->where('producto_id', $producto->id)
                ->where('ventas.estado', 'completada')
                ->whereMonth('ventas.fecha_emision', Carbon::now()->month)
                ->select(
                    DB::raw('SUM(cantidad) as cantidad_vendida'),
                    DB::raw('SUM(subtotal) as total_vendido')
                )
                ->first();

            $producto->cantidad_vendida = $ventas->cantidad_vendida ?? 0;
            $producto->total_vendido = $ventas->total_vendido ?? 0;
        }

        if ($filtros['formato'] == 'pdf') {
            $pdf = PDF::loadView('reportes.productos_pdf', [
                'productos' => $productos,
                'filtros' => $filtros
            ]);

            return $pdf->stream('reporte_productos.pdf');
        }

        return Excel::download(new ProductosExport($productos), 'reporte_productos.xlsx');
    }

    public function generarReporteMovimientos($filtros)
    {
        $query = MovimientoInventario::with(['producto', 'usuario']);

        if (isset($filtros['desde'])) {
            $query->whereDate('created_at', '>=', $filtros['desde']);
        }

        if (isset($filtros['hasta'])) {
            $query->whereDate('created_at', '<=', $filtros['hasta']);
        }

        if (isset($filtros['tipo_movimiento'])) {
            $query->where('tipo_movimiento', $filtros['tipo_movimiento']);
        }

        if (isset($filtros['producto_id'])) {
            $query->where('producto_id', $filtros['producto_id']);
        }

        $movimientos = $query->orderBy('created_at', 'desc')->get();

        if ($filtros['formato'] == 'pdf') {
            $pdf = PDF::loadView('reportes.movimientos_pdf', [
                'movimientos' => $movimientos,
                'filtros' => $filtros
            ]);

            return $pdf->stream('reporte_movimientos.pdf');
        }

        return Excel::download(new MovimientosExport($movimientos), 'reporte_movimientos.xlsx');
    }

    protected function calcularTotalesVentas($ventas)
    {
        return [
            'subtotal' => $ventas->sum('subtotal'),
            'igv' => $ventas->sum('igv'),
            'total' => $ventas->sum('total'),
            'cantidad' => $ventas->count()
        ];
    }
}