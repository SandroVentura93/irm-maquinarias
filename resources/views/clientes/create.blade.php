@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Nuevo Cliente</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm">Nombre</label>
                <input name="nombre" value="{{ old('nombre') }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm">Apellido</label>
                <input name="apellido" value="{{ old('apellido') }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">DNI</label>
                <input name="dni" value="{{ old('dni') }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">RUC</label>
                <input name="ruc" value="{{ old('ruc') }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Teléfono</label>
                <input name="telefono" value="{{ old('telefono') }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm">Email</label>
                <input name="email" value="{{ old('email') }}" class="w-full border rounded p-2" type="email">
            </div>
            <div>
                <label class="block text-sm">Región</label>
                <select id="regionSelect" name="region_id" class="w-full border rounded p-2">
                    <option value="">Seleccione una región</option>
                    @foreach($regiones as $r)
                        <option value="{{ $r->id }}">{{ $r->nombre ?? $r->descripcion ?? $r->nombre_reg }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">Provincia</label>
                <select id="provinciaSelect" name="provincia_id" class="w-full border rounded p-2">
                    <option value="">Seleccione provincia</option>
                </select>
            </div>
            <div>
                <label class="block text-sm">Distrito</label>
                <select id="distritoSelect" name="distrito_id" class="w-full border rounded p-2">
                    <option value="">Seleccione distrito</option>
                </select>
            </div>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const region = document.getElementById('regionSelect');
    const provincia = document.getElementById('provinciaSelect');
    const distrito = document.getElementById('distritoSelect');

    region && region.addEventListener('change', function() {
        const id = this.value;
        provincia.innerHTML = '<option>Cargando...</option>';
        fetch('/clientes/provincias/' + id)
            .then(r => r.json())
            .then(data => {
                provincia.innerHTML = '<option value="">Seleccione provincia</option>';
                data.forEach(p => provincia.innerHTML += `<option value="${p.id}">${p.nombre || p.descripcion || p.nombre_prov}</option>`);
            });
    });

    provincia && provincia.addEventListener('change', function() {
        const id = this.value;
        distrito.innerHTML = '<option>Cargando...</option>';
        fetch('/clientes/distritos/' + id)
            .then(r => r.json())
            .then(data => {
                distrito.innerHTML = '<option value="">Seleccione distrito</option>';
                data.forEach(d => distrito.innerHTML += `<option value="${d.id}">${d.nombre || d.descripcion || d.nombre_dist}</option>`);
            });
    });
});
</script>
@endpush
