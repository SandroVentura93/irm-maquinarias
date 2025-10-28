@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Editar Detalle de Venta</h1>
    <form action="{{ route('ventadetalles.update', $detalle->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="producto_id">Producto</label>
            <select name="producto_id" id="producto_id" class="form-control" required>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="{{ $detalle->cantidad }}" required>
        </div>
        <div class="form-group">
            <label for="precio_unitario">Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" class="form-control" min="0" value="{{ $detalle->precio_unitario }}" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('ventadetalles.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
