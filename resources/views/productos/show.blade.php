@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Producto: {{ $producto->nombre }}</h2>

    <div class="bg-white shadow rounded p-4">
        <p><strong>Código:</strong> {{ $producto->codigo }}</p>
        <p><strong>Marca:</strong> {{ $producto->marca ?? '-' }}</p>
    <p><strong>Categoría:</strong> {{ $producto->categoria ? $producto->categoria->descripcion : '-' }}</p>
        <p><strong>Precio venta:</strong> {{ number_format($producto->precio_venta, 2) }}</p>
        <p><strong>Stock actual:</strong> {{ $producto->stock_actual }}</p>

        <div class="mt-4">
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-primary mr-2">Editar</a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
