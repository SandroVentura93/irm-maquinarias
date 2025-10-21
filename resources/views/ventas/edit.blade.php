@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="px-6 py-6 border-b flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-edit"></i> Editar Venta #{{ $venta->id }}
                </h2>
                <a href="{{ route('ventas.index') }}" class="text-gray-500 hover:text-blue-600 text-sm flex items-center gap-1"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
            <form action="{{ route('ventas.update', $venta) }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="cliente_id" class="block text-sm font-semibold text-gray-700 mb-1">Cliente</label>
                        <select id="cliente_id" name="cliente_id" required class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $venta->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tipo_venta" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Venta</label>
                        <select id="tipo_venta" name="tipo_venta" required class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                            <option value="contado" {{ $venta->tipo_venta == 'contado' ? 'selected' : '' }}>Contado</option>
                            <option value="credito" {{ $venta->tipo_venta == 'credito' ? 'selected' : '' }}>Crédito</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition text-lg">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
