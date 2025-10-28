@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Detalle de Venta #{{ $venta->id }}</h2>
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div><dt class="font-semibold">ID</dt><dd>{{ $venta->id }}</dd></div>
            <div><dt class="font-semibold">Fecha</dt><dd>{{ $venta->fecha->format('d/m/Y H:i') }}</dd></div>
            <div><dt class="font-semibold">Cliente</dt><dd>{{ $venta->cliente->nombre ?? '-' }}</dd></div>
            <div><dt class="font-semibold">Usuario</dt><dd>{{ $venta->usuario->name ?? '-' }}</dd></div>
            <div><dt class="font-semibold">Descripción</dt><dd>{{ $venta->descripcion }}</dd></div>
            <div><dt class="font-semibold">Tipo de venta</dt><dd>{{ ucfirst($venta->tipo_venta) }}</dd></div>
            <div><dt class="font-semibold">Tipo de comprobante</dt><dd>{{ ucfirst($venta->tipo_comprobante) }}</dd></div>
            <div><dt class="font-semibold">Serie</dt><dd>{{ $venta->serie }}</dd></div>
            <div><dt class="font-semibold">Correlativo</dt><dd>{{ $venta->correlativo }}</dd></div>
            <div><dt class="font-semibold">Moneda</dt><dd>{{ $venta->moneda->nombre ?? '-' }}</dd></div>
            <div><dt class="font-semibold">Tipo de cambio usado</dt><dd>{{ $venta->tc_usado }}</dd></div>
            <div><dt class="font-semibold">Subtotal</dt><dd>S/ {{ number_format($venta->subtotal, 2) }}</dd></div>
            <div><dt class="font-semibold">Descuento total</dt><dd>S/ {{ number_format($venta->descuento_total, 2) }}</dd></div>
            <div><dt class="font-semibold">Recargo total</dt><dd>S/ {{ number_format($venta->recargo_total, 2) }}</dd></div>
            <div><dt class="font-semibold">Total</dt><dd class="text-green-700 font-bold">S/ {{ number_format($venta->total, 2) }}</dd></div>
            <div><dt class="font-semibold">Estado</dt><dd>{{ ucfirst($venta->estado) }}</dd></div>
            <div><dt class="font-semibold">Omitir FE</dt><dd>{{ $venta->omitir_fe ? 'Sí' : 'No' }}</dd></div>
            <div><dt class="font-semibold">Observaciones</dt><dd>{{ $venta->observaciones }}</dd></div>
            <div><dt class="font-semibold">Creado</dt><dd>{{ $venta->created_at->format('d/m/Y H:i') }}</dd></div>
            <div><dt class="font-semibold">Actualizado</dt><dd>{{ $venta->updated_at->format('d/m/Y H:i') }}</dd></div>
            @if($venta->deleted_at)
            <div><dt class="font-semibold">Eliminado</dt><dd>{{ $venta->deleted_at->format('d/m/Y H:i') }}</dd></div>
            @endif
        </dl>
    </div>
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-xl font-bold mb-4">Productos</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-4 py-2">ID Producto</th>
                    <th class="px-4 py-2">Descripción</th>
                    <th class="px-4 py-2 text-right">Cantidad</th>
                    <th class="px-4 py-2 text-right">Precio Unitario</th>
                    <th class="px-4 py-2 text-right">Descuento</th>
                    <th class="px-4 py-2 text-right">Subtotal</th>
                    <th class="px-4 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $d)
                    <tr>
                        <td>{{ $d->producto->id ?? '-' }}</td>
                        <td>{{ $d->producto->descripcion ?? '-' }}</td>
                        <td class="text-right">{{ number_format($d->cantidad, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($d->precio_unitario, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($d->descuento, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($d->subtotal, 2) }}</td>
                        <td class="text-right">
                            @if(Route::has('ventadetalles.show'))
                                <a href="{{ route('ventadetalles.show', $d->id) }}" class="text-blue-600 hover:underline mr-2">Ver</a>
                            @else
                                <span class="text-gray-400">Ver</span>
                            @endif
                            @if(Route::has('ventadetalles.edit'))
                                <a href="{{ route('ventadetalles.edit', $d->id) }}" class="text-yellow-600 hover:underline mr-2">Editar</a>
                            @else
                                <span class="text-gray-400">Editar</span>
                            @endif
                            @if(Route::has('ventadetalles.destroy'))
                                <form action="{{ route('ventadetalles.destroy', $d->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar este detalle?')">Eliminar</button>
                                </form>
                            @else
                                <span class="text-gray-400">Eliminar</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php $hasCreate = Route::has('ventadetalles.create'); @endphp
        <div class="mt-2">
            @if($hasCreate)
                <a href="{{ route('ventadetalles.create') }}?venta_id={{ $venta->id }}" class="btn btn-success">Agregar Detalle</a>
            @else
                <span class="btn btn-secondary disabled">Agregar Detalle</span>
            @endif
        </div>

        @php $hasImprimir = Route::has('ventas.imprimir'); @endphp
        <div class="mt-4">
            @if($hasImprimir)
                <a href="{{ route('ventas.imprimir', $venta->id) }}" class="btn btn-info">Imprimir</a>
            @else
                <span class="btn btn-secondary disabled">Imprimir</span>
            @endif
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
