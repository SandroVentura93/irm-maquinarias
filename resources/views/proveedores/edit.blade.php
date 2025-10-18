@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Editar Proveedor</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm">Nombre / Razón Social</label>
                <input name="nombre_razon_social" value="{{ old('nombre_razon_social', $proveedor->nombre_razon_social) }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm">DNI / RUC</label>
                <input name="dni_ruc" value="{{ old('dni_ruc', $proveedor->dni_ruc) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Rubro</label>
                <input name="rubro" value="{{ old('rubro', $proveedor->rubro) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Lugar</label>
                <input name="lugar" value="{{ old('lugar', $proveedor->lugar) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Teléfono</label>
                <input name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Email</label>
                <input name="email" value="{{ old('email', $proveedor->email) }}" class="w-full border rounded p-2" type="email">
            </div>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
@endsection
