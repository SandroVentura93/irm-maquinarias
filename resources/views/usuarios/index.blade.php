@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">Usuarios</h2>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo Usuario</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @foreach($usuarios as $u)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $u->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $u->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $u->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $u->tipo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('usuarios.edit', $u) }}" class="text-indigo-600 mr-2">Editar</a>
                            <form action="{{ route('usuarios.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar usuario?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection
