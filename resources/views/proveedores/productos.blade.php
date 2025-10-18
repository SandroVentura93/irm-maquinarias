@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Productos de {{ $proveedor->nombre_razon_social }}</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-2">Asignados</h3>
            <ul>
                @foreach($productos as $prod)
                    <li>{{ $prod->nombre ?? $prod->codigo }} - {{ $prod->stock_actual ?? '-' }}</li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-2">Disponibles</h3>
            <form action="{{ route('proveedores.asignarProductos', $proveedor) }}" method="POST">
                @csrf
                <ul class="space-y-2">
                    @foreach($disponibles as $d)
                        <li>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="productos[]" value="{{ $d->id }}" class="mr-2">
                                {{ $d->nombre ?? $d->codigo }}
                            </label>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 text-right">
                    <button class="btn btn-primary">Asignar seleccionados</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
