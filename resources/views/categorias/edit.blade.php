@extends('layouts.app')

@section('content')
<div class="p-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Editar Categoría</h2>
    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-input mt-1 block w-full" value="{{ old('descripcion', $categoria->descripcion) }}" required>
            @error('descripcion')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-end">
            <a href="{{ route('categorias.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
@endsection
