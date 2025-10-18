@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Cliente: {{ $cliente->nombre }} {{ $cliente->apellido }}</h2>

    <div class="bg-white shadow rounded p-4">
        <p><strong>DNI/RUC:</strong> {{ $cliente->dni ?? $cliente->ruc ?? '-' }}</p>
        <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $cliente->email ?? '-' }}</p>
        <p><strong>Dirección:</strong> {{ $cliente->direccion ?? '-' }}</p>

        <div class="mt-4">
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary mr-2">Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
