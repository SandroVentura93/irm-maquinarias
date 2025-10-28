@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Detalle de Venta</h2>
    <div class="bg-white shadow rounded-xl p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div><dt class="font-semibold">ID</dt><dd>{{ $detalle->id }}</dd></div>
            <div><dt class="font-semibold">Venta</dt><dd>{{ $detalle->venta_id }}</dd></div>
            <div><dt class="font-semibold">Producto</dt><dd>{{ $detalle->producto->descripcion ?? '-' }}</dd></div>
            <div><dt class="font-semibold">Cantidad</dt><dd>{{ number_format($detalle->cantidad, 2) }}</dd></div>
            <div><dt class="font-semibold">Precio Unitario</dt><dd>S/ {{ number_format($detalle->precio_unitario, 2) }}</dd></div>
            <div><dt class="font-semibold">Descuento</dt><dd>S/ {{ number_format($detalle->descuento, 2) }}</dd></div>
            <div><dt class="font-semibold">Subtotal</dt><dd>S/ {{ number_format($detalle->subtotal, 2) }}</dd></div>
            <div><dt class="font-semibold">Creado</dt><dd>{{ $detalle->created_at->format('d/m/Y H:i') }}</dd></div>
            <div><dt class="font-semibold">Actualizado</dt><dd>{{ $detalle->updated_at->format('d/m/Y H:i') }}</dd></div>
        </dl>
        <div class="mt-6">
            <a href="{{ route('ventas.show', $detalle->venta_id) }}" class="btn btn-secondary">Volver a venta</a>
        </div>
    </div>
</div>
@endsection
