@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Generar Cotización
                </h3>
                <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
                    @csrf
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select id="cliente_id" name="cliente_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-3"></div>
                        <div class="sm:col-span-6">
                            <div class="mt-1 border border-gray-200 rounded-lg shadow-sm">
                                <div class="p-4">
                                    <table class="min-w-full divide-y mb-4" id="productosTable">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th>Código</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unitario</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="productosBody">
                                        </tbody>
                                    </table>
                                    <div class="flex gap-2">
                                        <select id="productoSelect" class="border rounded px-2 py-1">
                                            <option value="">Seleccione producto</option>
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" data-codigo="{{ $producto->codigo }}" data-precio="{{ $producto->precio_venta }}">{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" id="cantidadInput" class="border rounded px-2 py-1 w-24" placeholder="Cantidad" min="1">
                                        <input type="number" id="precioInput" class="border rounded px-2 py-1 w-24" placeholder="Precio" step="0.01" min="0">
                                        <button type="button" id="agregarProductoBtn" class="bg-blue-600 text-white px-3 py-1 rounded">Agregar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sm:col-span-6 mt-4">
                            <label class="block mb-1">Observaciones</label>
                            <textarea name="observaciones" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                        <div class="sm:col-span-6 mt-4">
                            <label class="block mb-1">Total</label>
                            <input type="number" step="0.01" name="total" id="totalInput" class="w-full border rounded px-3 py-2" value="0" readonly>
                        </div>
                        <div class="sm:col-span-6 mt-4">
                            <label class="block mb-1">Estado</label>
                            <select name="estado" class="w-full border rounded px-3 py-2">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobada">Aprobada</option>
                                <option value="rechazada">Rechazada</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="detalles" id="detallesInput">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">Guardar cotización</button>
                </form>
                <script>
                let productos = [];
                function renderProductos() {
                    const tbody = document.getElementById('productosBody');
                    tbody.innerHTML = '';
                    let total = 0;
                    productos.forEach((p, i) => {
                        total += p.subtotal;
                        tbody.innerHTML += `<tr>
                            <td>${p.codigo}</td>
                            <td>${p.nombre}</td>
                            <td>${p.cantidad}</td>
                            <td>S/ ${p.precio_unitario.toFixed(2)}</td>
                            <td>S/ ${p.subtotal.toFixed(2)}</td>
                            <td><button type='button' onclick='eliminarProducto(${i})' class='text-red-600'>Eliminar</button></td>
                        </tr>`;
                    });
                    document.getElementById('totalInput').value = total.toFixed(2);
                    document.getElementById('detallesInput').value = JSON.stringify(productos);
                }
                function eliminarProducto(idx) {
                    productos.splice(idx, 1);
                    renderProductos();
                }
                document.getElementById('agregarProductoBtn').onclick = function() {
                    const select = document.getElementById('productoSelect');
                    const cantidad = parseInt(document.getElementById('cantidadInput').value);
                    const precio = parseFloat(document.getElementById('precioInput').value);
                    if (!select.value || !cantidad || !precio) return;
                    const option = select.options[select.selectedIndex];
                    productos.push({
                        producto_id: parseInt(select.value),
                        codigo: option.getAttribute('data-codigo'),
                        nombre: option.text,
                        cantidad: cantidad,
                        precio_unitario: precio,
                        subtotal: cantidad * precio
                    });
                    renderProductos();
                    select.value = '';
                    document.getElementById('cantidadInput').value = '';
                    document.getElementById('precioInput').value = '';
                };
                </script>
                                                <option value="">Seleccione un producto</option>
                                                @foreach($productos as $producto)
                                                    <option value="{{ $producto->id }}" 
                                                            data-precio="{{ $producto->precio_unitario }}"
                                                            data-stock="{{ $producto->stock }}">
                                                        {{ $producto->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad</label>
                                            <input type="number" min="1" id="cantidad" name="cantidad" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                                            <input type="number" step="0.01" id="precio" name="precio" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label for="descuento" class="block text-sm font-medium text-gray-700">Descuento %</label>
                                            <input type="number" step="0.01" min="0" max="100" id="descuento" name="descuento" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-6 flex justify-end">
                                            <button type="button" id="agregarProducto"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Agregar al detalle
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descuento</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="detalleCotizacion" class="bg-white divide-y divide-gray-200">
                                    <!-- Los items se agregarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Total</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    S/ <span id="total">0.00</span>
                                    <input type="hidden" name="total" id="total_input" value="0">
                                </dd>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="history.back()" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Generar Cotización
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const productos = {
        @foreach($productos as $producto)
            {{ $producto->id }}: {
                descripcion: "{{ $producto->descripcion }}",
                precio: {{ $producto->precio_unitario }},
                stock: {{ $producto->stock }}
            },
        @endforeach
    };

    let items = [];
    let total = 0;

    document.getElementById('producto_id').addEventListener('change', function() {
        if (this.value) {
            const producto = productos[this.value];
            document.getElementById('precio').value = producto.precio;
        }
    });

    document.getElementById('agregarProducto').addEventListener('click', function() {
        const productoId = document.getElementById('producto_id').value;
        const cantidad = parseFloat(document.getElementById('cantidad').value);
        const precio = parseFloat(document.getElementById('precio').value);
        const descuento = parseFloat(document.getElementById('descuento').value) || 0;

        if (!productoId || !cantidad || !precio) {
            alert('Por favor complete todos los campos requeridos');
            return;
        }

        const producto = productos[productoId];
        const subtotal = (cantidad * precio) * (1 - (descuento / 100));

        items.push({
            producto_id: productoId,
            cantidad: cantidad,
            precio: precio,
            descuento: descuento,
            subtotal: subtotal
        });

        actualizarTabla();
        limpiarFormularioProducto();
    });

    function actualizarTabla() {
        const tbody = document.getElementById('detalleCotizacion');
        tbody.innerHTML = '';
        total = 0;

        items.forEach((item, index) => {
            const producto = productos[item.producto_id];
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${producto.descripcion}
                    <input type="hidden" name="items[${index}][producto_id]" value="${item.producto_id}">
                    <input type="hidden" name="items[${index}][cantidad]" value="${item.cantidad}">
                    <input type="hidden" name="items[${index}][precio]" value="${item.precio}">
                    <input type="hidden" name="items[${index}][descuento]" value="${item.descuento}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.cantidad}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${item.precio.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.descuento}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${item.subtotal.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" onclick="eliminarItem(${index})" 
                            class="text-red-600 hover:text-red-900">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
            total += item.subtotal;
        });

        document.getElementById('total').textContent = total.toFixed(2);
        document.getElementById('total_input').value = total;
    }

    function eliminarItem(index) {
        items.splice(index, 1);
        actualizarTabla();
    }

    function limpiarFormularioProducto() {
        document.getElementById('producto_id').value = '';
        document.getElementById('cantidad').value = '';
        document.getElementById('precio').value = '';
        document.getElementById('descuento').value = '';
    }
</script>
@endpush
@endsection