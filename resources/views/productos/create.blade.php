@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Crear Nuevo Producto</h2>
            <a href="{{ route('productos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('productos.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700">Código *</label>
                        <input type="text" name="codigo" id="codigo" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Parte -->
                    <div>
                        <label for="numero_parte" class="block text-sm font-medium text-gray-700">Número de Parte</label>
                        <input type="text" name="numero_parte" id="numero_parte" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('numero_parte') }}">
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción *</label>
                        <textarea name="descripcion" id="descripcion" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                        <input type="text" name="marca" id="marca" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('marca') }}">
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <input type="text" name="tipo" id="tipo" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('tipo') }}">
                    </div>

                    <!-- Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="modelo" id="modelo" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('modelo') }}">
                    </div>

                    <!-- Peso -->
                    <div>
                        <label for="peso" class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                        <input type="number" step="0.001" name="peso" id="peso" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('peso') }}">
                    </div>

                    <!-- Ubicación Física -->
                    <div>
                        <label for="ubicacion_fisica" class="block text-sm font-medium text-gray-700">Ubicación Física</label>
                        <input type="text" name="ubicacion_fisica" id="ubicacion_fisica" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('ubicacion_fisica') }}">
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="categoria_id" id="categoria_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Proveedor -->
                    <div>
                        <label for="proveedor_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
                        <select name="proveedor_id" id="proveedor_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre_razon_social }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Stock Actual -->
                    <div>
                        <label for="stock_actual" class="block text-sm font-medium text-gray-700">Stock Actual *</label>
                        <input type="number" step="0.001" name="stock_actual" id="stock_actual" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('stock_actual', 0) }}" required>
                        @error('stock_actual')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Mínimo -->
                    <div>
                        <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo *</label>
                        <input type="number" step="0.001" name="stock_minimo" id="stock_minimo" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('stock_minimo', 0) }}" required>
                        @error('stock_minimo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio Unitario -->
                    <div>
                        <label for="precio_unitario" class="block text-sm font-medium text-gray-700">Precio Unitario *</label>
                        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('precio_unitario', 0) }}" required>
                        @error('precio_unitario')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Importado -->
                    <div>
                        <label for="importado" class="block text-sm font-medium text-gray-700">Importado</label>
                        <select name="importado" id="importado" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="0" {{ old('importado') == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('importado') == 1 ? 'selected' : '' }}>Sí</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection