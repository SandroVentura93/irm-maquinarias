@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Agregar Detalle de Venta</h1>
    <form action="{{ route('ventadetalles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="venta_id">Venta ID</label>
            <input type="number" name="venta_id" id="venta_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="producto_id">Producto</label>
            <select name="producto_id" id="producto_id" class="form-control" required>
                <option value="">Seleccione...</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="precio_unitario">Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('ventadetalles.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
