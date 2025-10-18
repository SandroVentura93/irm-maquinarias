<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\MonedaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Autenticación (rutas de login, register, etc.)
Auth::routes();

// Mostrar login a invitados y redirigir a dashboard si ya están autenticados
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // RF01: Gestión de Productos
    Route::resource('productos', ProductoController::class);
    Route::get('productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');
    Route::post('productos/actualizar-stock', [ProductoController::class, 'actualizarStock'])->name('productos.actualizar-stock');
// Ruta pública para ver productos con stock bajo
Route::get('productos/stock-bajo', [ProductoController::class, 'stockBajo'])->name('productos.stock-bajo');

    // RF02: Gestión de Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');
    Route::get('clientes/provincias/{region}', [ClienteController::class, 'provinciasByRegion'])->name('clientes.provincias');
    Route::get('clientes/distritos/{provincia}', [ClienteController::class, 'distritosByProvincia'])->name('clientes.distritos');

    // RF03: Gestión de Proveedores
    Route::resource('proveedores', ProveedorController::class);
    Route::get('proveedores/{proveedor}/productos', [ProveedorController::class, 'productos'])->name('proveedores.productos');
    Route::post('proveedores/{proveedor}/productos', [ProveedorController::class, 'asignarProductos'])->name('proveedores.asignar-productos');

    // RF04: Gestión de Usuarios
    Route::resource('usuarios', UsuarioController::class);
    Route::post('usuarios/{usuario}/cambiar-rol', [UsuarioController::class, 'cambiarRol'])->name('usuarios.cambiar-rol');

    // RF05-RF06: Gestión de Ventas y Comprobantes
    Route::resource('ventas', VentaController::class);
    Route::get('ventas/crear/{tipo}', [VentaController::class, 'crear'])->name('ventas.crear');
    Route::post('ventas/validar-stock', [VentaController::class, 'validarStock'])->name('ventas.validar-stock');
    Route::post('ventas/{venta}/aplicar-descuento', [VentaController::class, 'aplicarDescuento'])->name('ventas.aplicar-descuento');
    Route::get('ventas/{venta}/comprobante/{tipo}', [VentaController::class, 'generarComprobante'])->name('ventas.comprobante');
    Route::get('ventas/{venta}/imprimir', [VentaController::class, 'imprimir'])->name('ventas.imprimir');

    // RF07: Gestión de Cotizaciones
    Route::resource('cotizaciones', CotizacionController::class);
    Route::get('cotizaciones/{cotizacion}/convertir', [CotizacionController::class, 'convertirAVenta'])->name('cotizaciones.convertir');
    Route::get('cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.pdf');

    // RF08: Alertas y Pedidos
    // Pedidos full resource
    Route::resource('pedidos', App\Http\Controllers\PedidoController::class);
    // Backwards-compatible endpoints (previously in ProductoController)
    Route::get('pedidos/alertas', [ProductoController::class, 'pedidos'])->name('pedidos.alertas');
    Route::post('pedidos/generar', [App\Http\Controllers\PedidoController::class, 'store'])->name('pedidos.generar');

    // RF10: Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
        Route::get('productos', [ReporteController::class, 'productos'])->name('reportes.productos');
        Route::get('clientes', [ReporteController::class, 'clientes'])->name('reportes.clientes');
        Route::get('caja', [ReporteController::class, 'caja'])->name('reportes.caja');
        Route::post('exportar/{tipo}', [ReporteController::class, 'exportar'])->name('reportes.exportar');
    });

    // RF11: Configuración del Sistema
    Route::prefix('configuracion')->group(function () {
        // Configuración General
        Route::get('/', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('/general', [ConfiguracionController::class, 'actualizarGeneral'])->name('configuracion.actualizar-general');
        Route::post('/documentos', [ConfiguracionController::class, 'actualizarDocumentos'])->name('configuracion.actualizar-documentos');
        Route::post('/inventario', [ConfiguracionController::class, 'actualizarInventario'])->name('configuracion.actualizar-inventario');
        
        // Configuración de Moneda
        Route::get('/moneda', [ConfiguracionController::class, 'moneda'])->name('configuracion.moneda');
        Route::post('/tipo-cambio', [ConfiguracionController::class, 'actualizarTipoCambio'])->name('configuracion.tipo-cambio');
        
        // Backup y Restore
        Route::post('/backup', [ConfiguracionController::class, 'backup'])->name('configuracion.backup');
        Route::post('/restore', [ConfiguracionController::class, 'restore'])->name('configuracion.restore');
    });

    // RF12: Gestión de Monedas
    Route::resource('monedas', MonedaController::class);
    Route::patch('monedas/{moneda}/toggle-estado', [MonedaController::class, 'toggleEstado'])->name('monedas.toggle-estado');
});

// Ruta alternativa para /home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dev helper: quick-login as first user when APP_DEBUG=true (do not enable in production)
if (config('app.debug')) {
    Route::get('/_dev_login', function () {
        $user = App\Models\User::first();
        if (! $user) {
            return response('No user', 404);
        }
        auth()->login($user);
        return redirect('/');
    });
}
