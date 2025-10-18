@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Pedidos (productos con stock bajo)</h2>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Stock actual</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @foreach($productos as $p)
                    <tr>
                        <td class="px-6 py-4">{{ $p->codigo }}</td>
                        <td class="px-6 py-4">{{ $p->nombre }}</td>
                        <td class="px-6 py-4">{{ $p->stock_actual }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('pedidos.generar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $p->id }}">
                                <button class="btn btn-primary">Generar pedido</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
