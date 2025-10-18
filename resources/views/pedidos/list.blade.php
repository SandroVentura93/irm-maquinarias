@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Pedidos</h2>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Proveedor</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @foreach($pedidos as $p)
                    <tr>
                        <td class="px-6 py-4">{{ $p->id }}</td>
                        <td class="px-6 py-4">{{ $p->proveedor->nombre_razon_social ?? $p->proveedor->nombre ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->created_at }}</td>
                        <td class="px-6 py-4">{{ $p->estado }}</td>
                        <td class="px-6 py-4">{{ $p->total }}</td>
                        <td class="px-6 py-4"><a href="{{ route('pedidos.show', $p->id) }}" class="text-blue-600">Ver</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>
@endsection
