<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Venta;
use App\Models\Moneda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Hacer que el dashboard sea resiliente a fallos de BD
        try {
            // Si por alguna razón las tablas no existen, evitar lanzar excepciones
            if (!Schema::hasTable('productos')) {
                throw new \Exception('Tabla productos no existe');
            }

            // RF08: Alertas de stock bajo
            $productosStockBajo = Producto::whereRaw('stock_actual <= LEAST(stock_minimo, 5)')
                ->where('activo', 1)
                ->get();

            // Habilitar alerta de stock bajo
            $alertaStockEnabled = true;

            // RF01, RF12: Estadísticas de productos
            $totalProductos = Producto::count();
            $productosRecientes = Producto::latest()->take(5)->get();
            
            // RF02: Estadísticas de clientes
            $totalClientes = Schema::hasTable('clientes') ? Cliente::count() : 0;
            $clientesRecientes = Schema::hasTable('clientes') ? Cliente::latest()->take(5)->get() : collect();
            
            // RF03: Estadísticas de proveedores
            $totalProveedores = Schema::hasTable('proveedores') ? Proveedor::count() : 0;
            
            // RF04: Estadísticas de usuarios
            $totalUsuarios = User::count();
            
            // RF05, RF10: Estadísticas de ventas y reportes financieros
            $ventasHoy = Schema::hasTable('ventas') ? Venta::whereDate('fecha', Carbon::today())->sum('total') : 0;
            $ventasSemana = Schema::hasTable('ventas') ? Venta::whereBetween('fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total') : 0;
            $ventasMes = Schema::hasTable('ventas') ? Venta::whereYear('fecha', Carbon::now()->year)
                ->whereMonth('fecha', Carbon::now()->month)
                ->sum('total') : 0;
                
            // Ventas por tipo (contado/crédito)
            $ventasPorTipo = Schema::hasTable('ventas') ? Venta::select('tipo_venta', DB::raw('COUNT(*) as total'))
                ->whereMonth('fecha', Carbon::now()->month)
                ->groupBy('tipo_venta')
                ->get() : collect();
                
            // RF06: Estadísticas de comprobantes
            $comprobantesEmitidos = Schema::hasTable('ventas') ? Venta::select('tipo_comprobante', DB::raw('COUNT(*) as total'))
                ->whereMonth('fecha', Carbon::now()->month)
                ->groupBy('tipo_comprobante')
                ->get() : collect();
                
            // RF11: Información de monedas
            $monedas = Schema::hasTable('monedas') ? Moneda::all() : collect();
            $tipoCambioActual = Schema::hasTable('tipos_cambio') ? DB::table('tipos_cambio')
                ->where('moneda_origen', 1) // PEN
                ->where('moneda_destino', 2) // USD
                ->orderBy('fecha', 'desc')
                ->first() : null;

            // Gráfico de ventas últimos 7 días
            $ventasUltimaSemana = Schema::hasTable('ventas') ? Venta::select(
                DB::raw('DATE(fecha) as fecha'),
                DB::raw('SUM(total) as total')
            )
                ->whereBetween('fecha', [Carbon::now()->subDays(6), Carbon::now()])
                ->groupBy('fecha')
                ->get() : collect();

        } catch (\Exception $e) {
            // Loguear el error para diagnóstico y devolver valores por defecto para que la vista no explote
            Log::error('Error cargando dashboard: ' . $e->getMessage());

            $productosStockBajo = collect();
            $totalProductos = 0;
            $productosRecientes = collect();
            $totalClientes = 0;
            $clientesRecientes = collect();
            $totalProveedores = 0;
            $totalUsuarios = 0;
            $ventasHoy = 0;
            $ventasSemana = 0;
            $ventasMes = 0;
            $ventasPorTipo = collect();
            $comprobantesEmitidos = collect();
            $monedas = collect();
            $tipoCambioActual = null;
            $ventasUltimaSemana = collect();
        }

        return view('dashboard', compact(
            'productosStockBajo',
            'totalProductos',
            'productosRecientes',
            'totalClientes',
            'clientesRecientes',
            'totalProveedores',
            'totalUsuarios',
            'ventasHoy',
            'ventasSemana',
            'ventasMes',
            'ventasPorTipo',
            'comprobantesEmitidos',
            'monedas',
            'tipoCambioActual',
            'ventasUltimaSemana',
            'alertaStockEnabled'
        ));
    }
}