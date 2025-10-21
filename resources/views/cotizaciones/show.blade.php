@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Detalle de Cotización #{{ $cotizacion->id }}</h2>
    <div class="mb-4">
        <strong>Cliente:</strong> {{ $cotizacion->cliente->nombre ?? '-' }}<br>
        <strong>Fecha:</strong> {{ $cotizacion->fecha }}<br>
        <strong>Estado:</strong> {{ ucfirst($cotizacion->estado) }}<br>
        <strong>Observaciones:</strong> {{ $cotizacion->observaciones ?? '-' }}
    </div>
    <div class="bg-white shadow rounded overflow-hidden mb-4">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Producto</th>
                    <th class="px-6 py-3">Cantidad</th>
                    <th class="px-6 py-3">Precio Unitario</th>
                    <th class="px-6 py-3">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @foreach($cotizacion->detalles as $d)
                    <tr>
                        <td class="px-6 py-4">{{ $d->producto->codigo ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $d->producto->nombre ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $d->cantidad }}</td>
                        <td class="px-6 py-4">S/ {{ number_format($d->precio_unitario,2) }}</td>
                        <td class="px-6 py-4">S/ {{ number_format($d->subtotal,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mb-4 text-right">
        <strong>Total:</strong> S/ {{ number_format($cotizacion->total,2) }}
    </div>
    @if($cotizacion->estado === 'aprobada')
        <form action="{{ route('cotizaciones.convertir', $cotizacion->id) }}" method="GET">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Convertir en venta</button>
        </form>
    @endif
    <a href="{{ route('cotizaciones.index') }}" class="text-blue-600">&larr; Volver a cotizaciones</a>
</div>
@endsection
