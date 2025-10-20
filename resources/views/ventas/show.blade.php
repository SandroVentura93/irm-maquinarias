@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Venta #{{ $venta->id }}</h2>

    <div class="bg-white shadow rounded p-4">
    <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
    <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? '-' }}</p>
    <p><strong>Usuario:</strong> {{ $venta->usuario->name ?? '-' }}</p>
    <p><strong>Descripción:</strong> {{ $venta->descripcion }}</p>
    <p><strong>Tipo de Venta:</strong> {{ $venta->tipo_venta }}</p>
    <p><strong>Comprobante:</strong> {{ $venta->tipo_comprobante }} - {{ $venta->serie }}-{{ $venta->correlativo }}</p>
    <p><strong>Moneda:</strong> {{ $venta->moneda->nombre ?? '-' }}</p>
    <p><strong>TC Usado:</strong> {{ $venta->tc_usado }}</p>
    <p><strong>Subtotal:</strong> {{ number_format($venta->subtotal, 2) }}</p>
    <p><strong>Descuento Total:</strong> {{ number_format($venta->descuento_total, 2) }}</p>
    <p><strong>Recargo Total:</strong> {{ number_format($venta->recargo_total, 2) }}</p>
    <p><strong>Total:</strong> {{ number_format($venta->total, 2) }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($venta->estado) }}</p>
    <p><strong>Omitir FE:</strong> {{ $venta->omitir_fe ? 'Sí' : 'No' }}</p>
    <p><strong>Observaciones:</strong> {{ $venta->observaciones }}</p>
    <p><strong>Creado:</strong> {{ $venta->created_at }}</p>
    <p><strong>Actualizado:</strong> {{ $venta->updated_at }}</p>
    @if($venta->deleted_at)
    <p><strong>Eliminado:</strong> {{ $venta->deleted_at }}</p>
    @endif

        <h3 class="mt-4 font-semibold">Detalle</h3>
        <table class="min-w-full mt-2">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio</th>
                    <th class="text-right">Descuento</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $d)
                    <tr>
                        <td>{{ $d->descripcion ?? ($d->producto->nombre ?? '-') }}</td>
                        <td class="text-right">{{ number_format($d->cantidad, 3) }}</td>
                        <td class="text-right">{{ number_format($d->precio_unitario, 2) }}</td>
                        <td class="text-right text-green-600">{{ number_format($d->descuento, 2) }}</td>
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
