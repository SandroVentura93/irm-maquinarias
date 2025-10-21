@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Editar Cotización #{{ $cotizacion->id }}</h2>
    <form action="{{ route('cotizaciones.update', $cotizacion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">Cliente</label>
            <select name="cliente_id" class="w-full border rounded px-3 py-2">
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" @if($cotizacion->cliente_id == $cliente->id) selected @endif>{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Fecha</label>
            <input type="date" name="fecha" class="w-full border rounded px-3 py-2" value="{{ $cotizacion->fecha }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Observaciones</label>
            <textarea name="observaciones" class="w-full border rounded px-3 py-2">{{ $cotizacion->observaciones }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Estado</label>
            <select name="estado" class="w-full border rounded px-3 py-2">
                <option value="pendiente" @if($cotizacion->estado=='pendiente') selected @endif>Pendiente</option>
                <option value="aprobada" @if($cotizacion->estado=='aprobada') selected @endif>Aprobada</option>
                <option value="rechazada" @if($cotizacion->estado=='rechazada') selected @endif>Rechazada</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar cotización</button>
    </form>
    <a href="{{ route('cotizaciones.show', $cotizacion->id) }}" class="text-blue-600 mt-4 inline-block">&larr; Volver al detalle</a>
</div>
@endsection
