@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">Ventas</h2>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">Nueva Venta</a>
    </div>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Comprobante</th>
                    <th class="px-6 py-3">Moneda</th>
                    <th class="px-6 py-3">Descuento</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @foreach($ventas as $v)
                    <tr>
                        <td class="px-6 py-4">{{ $v->id }}</td>
                        <td class="px-6 py-4">{{ $v->fecha }}</td>
                        <td class="px-6 py-4">{{ $v->cliente->nombre ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $v->tipo_comprobante }}<br>{{ $v->serie }}-{{ $v->correlativo }}</td>
                        <td class="px-6 py-4">{{ $v->moneda->nombre ?? '-' }}</td>
                        <td class="px-6 py-4 text-green-600">{{ number_format($v->descuento_total, 2) }}</td>
                        <td class="px-6 py-4">{{ number_format($v->total, 2) }}</td>
                        <td class="px-6 py-4">{{ ucfirst($v->estado) }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('ventas.show', $v) }}" class="text-indigo-600 mr-2">Ver</a>
                            <a href="{{ route('ventas.imprimir', $v) }}" class="text-green-600">Imprimir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ventas->links() }}
    </div>
</div>
@endsection
