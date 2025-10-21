@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Productos con stock bajo</h2>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Stock actual</th>
                    <th class="px-6 py-3">Stock mínimo</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse($productos as $p)
                    <tr>
                        <td class="px-6 py-4">{{ $p->codigo }}</td>
                        <td class="px-6 py-4">{{ $p->nombre }}</td>
                        <td class="px-6 py-4">{{ $p->stock_actual }}</td>
                        <td class="px-6 py-4">{{ $p->stock_minimo }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay productos con stock bajo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
