@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Productos de la Venta #{{ $venta->id }}</h2>
    <div class="bg-white shadow rounded-xl p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-4 py-2">ID Producto</th>
                    <th class="px-4 py-2">Descripción</th>
                    <th class="px-4 py-2 text-right">Cantidad</th>
                    <th class="px-4 py-2 text-right">Precio Unitario</th>
                    <th class="px-4 py-2 text-right">Descuento</th>
                    <th class="px-4 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                    <tr>
                        <td class="px-4 py-2">{{ $detalle->producto->id ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $detalle->producto->descripcion ?? '-' }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($detalle->cantidad, 2) }}</td>
                        <td class="px-4 py-2 text-right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="px-4 py-2 text-right">S/ {{ number_format($detalle->descuento, 2) }}</td>
                        <td class="px-4 py-2 text-right">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver a ventas</a>
        </div>
    </div>
</div>
@endsection
