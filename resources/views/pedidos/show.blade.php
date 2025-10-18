@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Pedido #{{ $pedido->id }}</h2>

    <div class="bg-white p-4 rounded shadow">
        <p><strong>Proveedor:</strong> {{ $pedido->proveedor->nombre_razon_social ?? $pedido->proveedor->nombre ?? '-' }}</p>
        <p><strong>Fecha:</strong> {{ $pedido->fecha }}</p>
        <p><strong>Estado:</strong> {{ $pedido->estado }}</p>
        <p class="mt-2"><strong>Observaciones:</strong> {{ $pedido->observaciones }}</p>

        <h3 class="mt-4 font-medium">Lineas</h3>
        <table class="min-w-full divide-y mt-2">
            <thead>
                <tr><th>Producto</th><th>Descripción</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($pedido->detalles as $d)
                    <tr>
                        <td class="px-2 py-1">{{ $d->producto->nombre ?? $d->codigo_producto ?? '-' }}</td>
                        <td class="px-2 py-1">{{ $d->descripcion }}</td>
                        <td class="px-2 py-1">{{ $d->cantidad }}</td>
                        <td class="px-2 py-1">{{ $d->precio_unitario }}</td>
                        <td class="px-2 py-1">{{ $d->subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <strong>Total: </strong> {{ $pedido->total }}
        </div>
    </div>
</div>
@endsection
