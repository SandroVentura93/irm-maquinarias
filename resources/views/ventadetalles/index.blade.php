@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Detalles de Venta</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td>{{ $detalle->id }}</td>
                <td>{{ $detalle->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                <td>
                    <a href="{{ route('ventadetalles.show', $detalle->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('ventadetalles.edit', $detalle->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('ventadetalles.destroy', $detalle->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $detalles->links() }}
    <a href="{{ route('ventadetalles.create') }}" class="btn btn-primary">Agregar Detalle</a>
</div>
@endsection
