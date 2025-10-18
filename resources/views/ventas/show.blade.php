@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Venta #{{ $venta->id }}</h2>

    <div class="bg-white shadow rounded p-4">
        <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
        <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? '-' }}</p>
        <p><strong>Tipo:</strong> {{ $venta->tipo_comprobante }} / {{ $venta->tipo_venta }}</p>
        <p><strong>Total:</strong> {{ number_format($venta->total, 2) }}</p>

        <h3 class="mt-4 font-semibold">Detalle</h3>
        <table class="min-w-full mt-2">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $d)
                    <tr>
                        <td>{{ $d->descripcion ?? ($d->producto->nombre ?? '-') }}</td>
                        <td class="text-right">{{ number_format($d->cantidad, 3) }}</td>
                        <td class="text-right">{{ number_format($d->precio_unitario, 2) }}</td>
                        <td class="text-right">{{ number_format($d->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <a href="{{ route('ventas.imprimir', $venta) }}" class="btn btn-primary mr-2">Imprimir</a>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
