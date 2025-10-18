@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Realizar Venta
                </h3>
                <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
                    @csrf
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select id="cliente_id" name="cliente_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="tipo_pago" class="block text-sm font-medium text-gray-700">Tipo de Pago</label>
                            <select id="tipo_pago" name="tipo_pago" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="contado">Contado</option>
                                <option value="credito">Crédito</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="metodo_pago" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <select id="metodo_pago" name="metodo_pago" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="efectivo">Efectivo</option>
                                <option value="yape">Yape</option>
                                <option value="plin">Plin</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="mt-1 border border-gray-200 rounded-lg shadow-sm">
                                <div class="p-4">
                                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label for="producto_id" class="block text-sm font-medium text-gray-700">Producto</label>
                                            <select id="producto_id" name="producto_id" 
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
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
                                            <label for="stock_disponible" class="block text-sm font-medium text-gray-700">Stock</label>
                                            <input type="text" id="stock_disponible" readonly
                                                   class="mt-1 block w-full bg-gray-50 border-gray-300 rounded-md shadow-sm sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-6 flex justify-end">
                                            <button type="button" id="agregarProducto"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Agregar al carrito
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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="detalleVenta" class="bg-white divide-y divide-gray-200">
                                    <!-- Los items se agregarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    S/ <span id="subtotal">0.00</span>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">IGV (18%)</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    S/ <span id="igv">0.00</span>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Total</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    S/ <span id="total">0.00</span>
                                    <input type="hidden" name="total" id="total_input" value="0">
                                    <input type="hidden" name="igv" id="igv_input" value="0">
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
                            Emitir Comprobante
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
    let subtotal = 0;
    let igv = 0;
    let total = 0;
    const IGV_RATE = 0.18;

    document.getElementById('producto_id').addEventListener('change', function() {
        if (this.value) {
            const producto = productos[this.value];
            document.getElementById('precio').value = producto.precio;
            document.getElementById('stock_disponible').value = producto.stock;
        }
    });

    document.getElementById('agregarProducto').addEventListener('click', async function() {
        const productoId = document.getElementById('producto_id').value;
        const cantidad = parseFloat(document.getElementById('cantidad').value);
        const precio = parseFloat(document.getElementById('precio').value);

        if (!productoId || !cantidad || !precio) {
            alert('Por favor complete todos los campos requeridos');
            return;
        }

        // Validar stock
        try {
            const response = await fetch('/ventas/validar-stock', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    producto_id: productoId,
                    cantidad: cantidad
                })
            });

            const data = await response.json();
            if (!data.success) {
                alert(data.message);
                return;
            }

            const producto = productos[productoId];
            const subtotalItem = cantidad * precio;

            items.push({
                producto_id: productoId,
                cantidad: cantidad,
                precio: precio,
                subtotal: subtotalItem
            });

            actualizarTabla();
            limpiarFormularioProducto();

        } catch (error) {
            alert('Error al validar el stock');
            console.error('Error:', error);
        }
    });

    function actualizarTabla() {
        const tbody = document.getElementById('detalleVenta');
        tbody.innerHTML = '';
        subtotal = 0;

        items.forEach((item, index) => {
            const producto = productos[item.producto_id];
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${producto.descripcion}
                    <input type="hidden" name="items[${index}][producto_id]" value="${item.producto_id}">
                    <input type="hidden" name="items[${index}][cantidad]" value="${item.cantidad}">
                    <input type="hidden" name="items[${index}][precio]" value="${item.precio}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.cantidad}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${item.precio.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${item.subtotal.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" onclick="eliminarItem(${index})"
                            class="text-red-600 hover:text-red-900">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
            subtotal += item.subtotal;
        });

        igv = subtotal * IGV_RATE;
        total = subtotal + igv;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('igv').textContent = igv.toFixed(2);
        document.getElementById('total').textContent = total.toFixed(2);
        document.getElementById('total_input').value = total;
        document.getElementById('igv_input').value = igv;
    }

    function eliminarItem(index) {
        items.splice(index, 1);
        actualizarTabla();
    }

    function limpiarFormularioProducto() {
        document.getElementById('producto_id').value = '';
        document.getElementById('cantidad').value = '';
        document.getElementById('precio').value = '';
        document.getElementById('stock_disponible').value = '';
    }

    // Mostrar/ocultar campos de crédito
    document.getElementById('tipo_pago').addEventListener('change', function() {
        const creditoFields = document.getElementById('credito_fields');
        if (this.value === 'credito') {
            creditoFields.classList.remove('hidden');
        } else {
            creditoFields.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection