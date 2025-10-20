
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <form action="{{ route('productos.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700">Código *</label>
                <input type="text" name="codigo" id="codigo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('codigo') }}" required>
                @error('codigo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                <input type="text" name="nombre" id="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nombre') }}" required>
                @error('nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción *</label>
                <textarea name="descripcion" id="descripcion" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('descripcion') }}</textarea>
                @error('descripcion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" name="marca" id="marca" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('marca') }}">
            </div>
            <div>
                <label for="unidad_medida" class="block text-sm font-medium text-gray-700">Unidad de Medida *</label>
                <select name="unidad_medida" id="unidad_medida" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Seleccione unidad</option>
                    <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                    <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kg</option>
                    <option value="lt" {{ old('unidad_medida') == 'lt' ? 'selected' : '' }}>Litro</option>
                    <option value="m" {{ old('unidad_medida') == 'm' ? 'selected' : '' }}>Metro</option>
                    <option value="otro" {{ old('unidad_medida') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            <div>
                <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="ubicacion" id="ubicacion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('ubicacion') }}">
            </div>
            <div>
                <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio Compra *</label>
                <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('precio_compra') }}" required>
            </div>
            <div>
                <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio Venta *</label>
                <input type="number" step="0.01" name="precio_venta" id="precio_venta" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('precio_venta') }}" required>
            </div>
            <div>
                <label for="stock_actual" class="block text-sm font-medium text-gray-700">Stock Actual *</label>
                <input type="number" name="stock_actual" id="stock_actual" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('stock_actual') }}" required>
            </div>
            <div>
                <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo *</label>
                <input type="number" name="stock_minimo" id="stock_minimo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('stock_minimo') }}" required>
            </div>
            <div>
                <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría *</label>
                <select name="categoria_id" id="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="activo" class="block text-sm font-medium text-gray-700">Activo</label>
                <select name="activo" id="activo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <a href="{{ route('productos.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
@endsection