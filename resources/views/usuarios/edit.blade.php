@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Editar Usuario</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm">Nombre</label>
                <input name="name" value="{{ old('name', $usuario->name) }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm">Email</label>
                <input name="email" value="{{ old('email', $usuario->email) }}" class="w-full border rounded p-2" type="email" required>
            </div>
            <div>
                <label class="block text-sm">Nueva contraseña (dejar vacío para mantener)</label>
                <input name="password" class="w-full border rounded p-2" type="password">
            </div>
            <div>
                <label class="block text-sm">Confirmar contraseña</label>
                <input name="password_confirmation" class="w-full border rounded p-2" type="password">
            </div>
            <div>
                <label class="block text-sm">Tipo</label>
                <select name="tipo" class="w-full border rounded p-2" required>
                    <option value="administrador" {{ $usuario->tipo == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="gerente" {{ $usuario->tipo == 'gerente' ? 'selected' : '' }}>Gerente</option>
                    <option value="vendedor" {{ $usuario->tipo == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                </select>
            </div>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
@endsection
