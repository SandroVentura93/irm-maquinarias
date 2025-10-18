@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Nuevo Pedido</h2>

    <div class="bg-white shadow rounded p-4">
        <form action="{{ route('pedidos.store') }}" method="POST" id="pedido-form">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                <select name="proveedor_id" class="w-full border rounded p-2">
                    <option value="">Seleccione proveedor</option>
                    @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}">{{ $prov->nombre_razon_social ?? $prov->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                <textarea name="observaciones" class="w-full border rounded p-2" rows="3"></textarea>
            </div>

            <div>
                <h3 class="font-medium mb-2">Líneas de pedido</h3>
                <div id="lineas">
                    <template id="linea-template">
                        <div class="grid grid-cols-12 gap-2 items-center mb-2">
                            <div class="col-span-4">
                                <select name="lineas[__i__][producto_id]" class="w-full border rounded p-2 producto-select">
                                    <option value="">(Producto opcional)</option>
                                    @foreach($productos as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->nombre }} ({{ $prod->codigo }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-4">
                                <input name="lineas[__i__][descripcion]" placeholder="Descripción" class="w-full border rounded p-2" required />
                            </div>
                            <div class="col-span-2">
                                <input name="lineas[__i__][cantidad]" type="number" step="0.001" placeholder="Cantidad" class="w-full border rounded p-2" required />
                            </div>
                            <div class="col-span-2">
                                <input name="lineas[__i__][precio_unitario]" type="number" step="0.01" placeholder="Precio" class="w-full border rounded p-2" />
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-2">
                    <button type="button" id="add-line" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded">Agregar línea</button>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded">Crear pedido</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function(){
        let idx = 0;
        const template = document.getElementById('linea-template').innerHTML;
        const container = document.getElementById('lineas');
        const addBtn = document.getElementById('add-line');

        function addLine(prefill) {
            let html = template.replace(/__i__/g, idx);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;
            container.appendChild(wrapper);
            idx++;
            if(prefill) {
                const select = wrapper.querySelector('.producto-select');
                if(select && prefill.id) select.value = prefill.id;
                const desc = wrapper.querySelector('input[name="lineas['+(idx-1)+'][descripcion]"]');
                if(desc) desc.value = prefill.descripcion || (prefill.nombre ? prefill.nombre : '');
                const cantidad = wrapper.querySelector('input[name="lineas['+(idx-1)+'][cantidad]"]');
                if(cantidad) cantidad.value = prefill.cantidad || 1;
                const precio = wrapper.querySelector('input[name="lineas['+(idx-1)+'][precio_unitario]"]');
                if(precio) precio.value = prefill.precio || '';
            }
        }

        addBtn.addEventListener('click', () => addLine());

        // If productoSeleccionado passed, add one line prefilled
        @if(isset($productoSeleccionado) && $productoSeleccionado)
            addLine({ id: {{ $productoSeleccionado->id }}, nombre: '{{ addslashes($productoSeleccionado->nombre) }}', cantidad: 1 });
        @endif

    })();
</script>

@endsection
