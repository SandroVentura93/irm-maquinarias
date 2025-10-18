@extends('layouts.app')

@section('content')
<div class="container px-6 mx-auto">
    <h3 class="text-3xl font-semibold text-gray-800 my-6">Dashboard</h3>

    <!-- RF08: Alertas de stock bajo -->
    @if($productosStockBajo->count() > 0)
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">¡Alerta de Stock Bajo!</p>
        <p>Hay {{ $productosStockBajo->count() }} productos con stock bajo.</p>
        <a href="{{ route('productos.stock-bajo') }}" class="text-red-700 underline">Ver detalles</a>
    </div>
    @endif

    <!-- RF11: Tipo de cambio actual -->
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
        <p class="font-bold">Tipo de Cambio Actual:</p>
        <p>1 USD = S/. {{ $tipoCambioActual ? number_format($tipoCambioActual->tipo_cambio, 3) : 'No disponible' }}</p>
    </div>

    <!-- Estadísticas generales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- RF05: Ventas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-600">Ventas Hoy</h4>
                    <p class="text-2xl font-bold text-gray-800">S/. {{ number_format($ventasHoy, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- RF01: Productos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-600">Total Productos</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalProductos }}</p>
                </div>
            </div>
        </div>

        <!-- RF02: Clientes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-600">Total Clientes</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalClientes }}</p>
                </div>
            </div>
        </div>

        <!-- RF03: Proveedores -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-600">Total Proveedores</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalProveedores }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RF10: Gráfico de ventas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">Ventas Últimos 7 días</h4>
            <canvas id="ventasChart" class="w-full"></canvas>
        </div>

        <!-- RF06: Comprobantes emitidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">Comprobantes Emitidos (Este mes)</h4>
            <div class="space-y-4">
                @foreach($comprobantesEmitidos as $comprobante)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 capitalize">{{ $comprobante->tipo_comprobante }}</span>
                    <span class="text-gray-800 font-semibold">{{ $comprobante->total }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RF12: Productos recientes y búsqueda -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xl font-semibold text-gray-800">Productos Recientes</h4>
                <div class="relative">
                    <input type="text" 
                           class="w-64 px-4 py-2 text-gray-700 bg-white border rounded-lg focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                           placeholder="Buscar productos...">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio (S/.)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productosRecientes as $producto)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $producto->codigo }}</td>
                            <td class="px-6 py-4">{{ Str::limit($producto->descripcion, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $producto->stock_actual }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($producto->precio_unitario, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const ventas = @json($ventasUltimaSemana);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ventas.map(v => new Date(v.fecha).toLocaleDateString()),
            datasets: [{
                label: 'Ventas Diarias',
                data: ventas.map(v => v.total),
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Tendencia de Ventas'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush