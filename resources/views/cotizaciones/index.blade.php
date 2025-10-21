@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Cotizaciones de productos</h2>
    <a href="{{ route('cotizaciones.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Nueva cotización</a>
    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse($cotizaciones as $c)
                    <tr>
                        <td class="px-6 py-4">{{ $c->id }}</td>
                        <td class="px-6 py-4">{{ $c->cliente->nombre ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $c->fecha }}</td>
                        <td class="px-6 py-4">S/ {{ number_format($c->total,2) }}</td>
                        <td class="px-6 py-4">{{ ucfirst($c->estado) }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('cotizaciones.show', $c->id) }}" class="text-blue-600">Ver</a>
                            @if($c->estado === 'aprobada')
                                <form action="{{ route('cotizaciones.convertir', $c->id) }}" method="GET" class="inline">
                                    <button type="submit" class="ml-2 text-green-600">Convertir en venta</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay cotizaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $cotizaciones->links() }}</div>
</div>
@endsection
