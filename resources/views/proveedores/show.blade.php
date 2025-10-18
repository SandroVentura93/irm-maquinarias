@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Proveedor: {{ $proveedor->nombre_razon_social }}</h2>

    <div class="bg-white shadow rounded p-4">
        <p><strong>DNI/RUC:</strong> {{ $proveedor->dni_ruc ?? '-' }}</p>
        <p><strong>Teléfono:</strong> {{ $proveedor->telefono ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $proveedor->email ?? '-' }}</p>
        <p><strong>Rubro:</strong> {{ $proveedor->rubro ?? '-' }}</p>
        <p><strong>Lugar:</strong> {{ $proveedor->lugar ?? '-' }}</p>

        <div class="mt-4">
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-primary mr-2">Editar</a>
            <a href="{{ route('proveedores.productos', $proveedor) }}" class="btn btn-secondary mr-2">Productos</a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
