@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Editar Producto</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm">Código</label>
                <input name="codigo" value="{{ old('codigo', $producto->codigo) }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm">Nombre</label>
                <input name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="w-full border rounded p-2" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm">Descripción</label>
                <textarea name="descripcion" class="w-full border rounded p-2">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>
            <div>
                <label class="block text-sm">Categoría</label>
                <select name="categoria_id" class="w-full border rounded p-2" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->descripcion }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">Precio compra</label>
                <input name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Precio venta</label>
                <input name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Stock actual</label>
                <input name="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" class="w-full border rounded p-2" type="number">
            </div>
            <div>
                <label class="block text-sm">Stock mínimo</label>
                <input name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" class="w-full border rounded p-2" type="number">
            </div>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('productos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
@endsection
