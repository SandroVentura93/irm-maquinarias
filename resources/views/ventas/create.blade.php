@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 lg:px-8 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="px-6 py-6 border-b flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-cash-register text-blue-600"></i> Nueva Venta
                </h2>
                <button type="button" onclick="history.back()" class="text-gray-500 hover:text-blue-600 text-sm flex items-center gap-1"><i class="fas fa-arrow-left"></i> Volver</button>
            </div>
            <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm" class="px-6 py-6 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label for="cliente_id" class="block text-sm font-semibold text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <select id="cliente_id" name="cliente_id" required class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="btnAgregarCliente" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow transition"><i class="fas fa-user-plus mr-1"></i> Nuevo</button>
                        </div>
                    </div>

                            <!-- Modal agregar cliente -->
                            <div id="modalCliente" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-0 relative animate-fade-in">
                                    <div class="flex justify-between items-center px-6 py-4 border-b">
                                        <h3 class="text-xl font-semibold text-gray-800">Nuevo Cliente</h3>
                                        <button type="button" id="cerrarModalCliente" class="text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                    </div>
                                    <form id="formNuevoCliente" class="px-6 py-4">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="nuevo_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                                            <input type="text" id="nuevo_nombre" name="nombre" required class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 transition">
                                        </div>
                                        <div class="mb-4 grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="nuevo_dni" class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                                                <input type="text" id="nuevo_dni" name="dni" maxlength="8" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 transition">
                                            </div>
                                            <div>
                                                <label for="nuevo_ruc" class="block text-sm font-medium text-gray-700 mb-1">RUC</label>
                                                <input type="text" id="nuevo_ruc" name="ruc" maxlength="11" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 transition">
                                            </div>
                                        </div>
                                        <div class="mb-4 grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="nuevo_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                <input type="email" id="nuevo_email" name="email" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 transition">
                                            </div>
                                            <div>
                                                <label for="nuevo_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                                <input type="text" id="nuevo_telefono" name="telefono" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 transition">
                                            </div>
                                        </div>
                                        <div class="flex justify-end mt-6">
                                            <button type="button" id="guardarCliente" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow transition">
                                                <i class="fas fa-save"></i> Guardar
                                            </button>
                                        </div>
                                        <div id="clienteError" class="text-red-600 text-sm mt-2 hidden"></div>
                                    </form>
                                </div>
                            </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal cliente UX
    const modal = document.getElementById('modalCliente');
    const btnAgregarCliente = document.getElementById('btnAgregarCliente');
    const cerrarModalCliente = document.getElementById('cerrarModalCliente');
    const guardarCliente = document.getElementById('guardarCliente');
    const clienteError = document.getElementById('clienteError');
    const clienteSelect = document.getElementById('cliente_id');
    btnAgregarCliente.onclick = function() {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('animate-fade-in'), 10);
        clienteError.classList.add('hidden');
    }
    cerrarModalCliente.onclick = function() {
        modal.classList.remove('animate-fade-in');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
    guardarCliente.onclick = function(e) {
        e.preventDefault();
        let form = document.getElementById('formNuevoCliente');
        let data = new FormData(form);
        guardarCliente.disabled = true;
        guardarCliente.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        fetch("{{ route('clientes.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(response => response.json())
        .then(json => {
            guardarCliente.disabled = false;
            guardarCliente.innerHTML = '<i class="fas fa-save"></i> Guardar';
            if(json.errors) {
                let msg = Object.values(json.errors).map(arr => arr.join(' ')).join(' ');
                clienteError.innerText = msg;
                clienteError.classList.remove('hidden');
            } else if(json.id) {
                let option = document.createElement('option');
                option.value = json.id;
                option.text = json.nombre;
                option.selected = true;
                clienteSelect.appendChild(option);
                modal.classList.remove('animate-fade-in');
                setTimeout(() => modal.classList.add('hidden'), 200);
                form.reset();
                clienteError.classList.add('hidden');
                // Feedback visual
                Toast('Cliente agregado correctamente', 'success');
            }
        })
        .catch(() => {
            guardarCliente.disabled = false;
            guardarCliente.innerHTML = '<i class="fas fa-save"></i> Guardar';
            clienteError.innerText = 'Error al guardar cliente.';
            clienteError.classList.remove('hidden');
        });
    }

    // Toast feedback
    window.Toast = function(msg, type = 'info') {
        let toast = document.createElement('div');
        toast.className = 'fixed top-6 right-6 z-50 px-4 py-2 rounded shadow-lg text-white text-sm font-semibold ' + (type === 'success' ? 'bg-green-600' : 'bg-blue-600');
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => { toast.classList.add('opacity-0'); }, 1800);
        setTimeout(() => { toast.remove(); }, 2200);
    }

    // ...existing code...
});
</script>
<style>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(40px) scale(0.98); }
  to { opacity: 1; transform: none; }
}
.animate-fade-in {
  animation: fade-in 0.25s cubic-bezier(.4,0,.2,1);
}
</style>
@endpush
                        </div>


                    <div>
                        <label for="tipo_pago" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Pago</label>
                        <select id="tipo_pago" name="tipo_pago" required class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                            <option value="contado">Contado</option>
                            <option value="credito">Crédito</option>
                        </select>
                    </div>


                    <div>
                        <label for="metodo_pago" class="block text-sm font-semibold text-gray-700 mb-1">Método de Pago</label>
                        <select id="metodo_pago" name="metodo_pago" required class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                            <option value="efectivo">Efectivo</option>
                            <option value="yape">Yape</option>
                            <option value="plin">Plin</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="pos">POS</option>
                        </select>
                    </div>

                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl shadow-inner p-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="producto_id" class="block text-sm font-semibold text-gray-700 mb-1">Producto</label>
                            <select id="producto_id" name="producto_id" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                                <option value="">Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_unitario }}" data-stock="{{ $producto->stock }}">{{ $producto->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="cantidad" class="block text-sm font-semibold text-gray-700 mb-1">Cantidad</label>
                            <input type="number" min="1" id="cantidad" name="cantidad" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                        </div>
                        <div>
                            <label for="precio" class="block text-sm font-semibold text-gray-700 mb-1">Precio</label>
                            <input type="number" step="0.01" id="precio" name="precio" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="descuento" class="block text-sm font-semibold text-gray-700 mb-1">Descuento</label>
                            <input type="number" step="0.01" min="0" id="descuento" name="descuento" value="0" class="block w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3">
                        </div>
                        <div>
                            <label for="stock_disponible" class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                            <input type="text" id="stock_disponible" readonly class="block w-full bg-gray-100 border border-gray-300 rounded-lg py-2 px-3">
                        </div>
                        <div>
                            <button type="button" id="agregarProducto" class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition"><i class="fas fa-cart-plus"></i> Agregar</button>
                        </div>
                    </div>
                </div>

                        <!-- Campos innecesarios eliminados para simplificar el formulario -->


                <div>
                    <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden mt-4">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Producto</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Cantidad</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Precio</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Subtotal</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody id="detalleVenta" class="bg-white divide-y divide-gray-200">
                            <!-- Los items se agregarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-blue-50 rounded-xl p-4 flex flex-col items-center">
                        <span class="text-xs text-blue-700 font-semibold">Subtotal</span>
                        <span class="text-lg font-bold text-blue-900">S/ <span id="subtotal">0.00</span></span>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 flex flex-col items-center">
                        <span class="text-xs text-blue-700 font-semibold">IGV (18%)</span>
                        <span class="text-lg font-bold text-blue-900">S/ <span id="igv">0.00</span></span>
                    </div>
                    <div class="bg-blue-100 rounded-xl p-4 flex flex-col items-center border-2 border-blue-400">
                        <span class="text-xs text-blue-900 font-bold">Total</span>
                        <span class="text-2xl font-extrabold text-blue-900">S/ <span id="total">0.00</span></span>
                        <input type="hidden" name="total" id="total_input" value="0">
                        <input type="hidden" name="igv" id="igv_input" value="0">
                    </div>
                </div>
                    </div>


                <div class="flex justify-end mt-8">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition text-lg">
                        <i class="fas fa-file-invoice"></i> Emitir Comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let items = [];
    let subtotal = 0;
    let igv = 0;
    let total = 0;
    const IGV_RATE = 0.18;

    const productoId = document.getElementById('producto_id');
    const cantidad = document.getElementById('cantidad');
    const precio = document.getElementById('precio');
    const descuento = document.getElementById('descuento');
    const stockDisp = document.getElementById('stock_disponible');
    const agregarProducto = document.getElementById('agregarProducto');

    productoId.addEventListener('change', function() {
        const selected = productoId.options[productoId.selectedIndex];
        if (selected && selected.value) {
            // Corrige el nombre del atributo si es necesario
            let precioData = selected.getAttribute('data-precio');
            if (!precioData) {
                precioData = selected.getAttribute('data-precio_unitario');
            }
            const stockData = selected.getAttribute('data-stock');
            console.log('Producto seleccionado:', selected.value, 'Precio:', precioData, 'Stock:', stockData);
            precio.value = precioData || '';
            stockDisp.value = stockData || '';
            descuento.value = 0;
        } else {
            precio.value = '';
            stockDisp.value = '';
            descuento.value = 0;
        }
    });

    // Campo oculto para los items serializados
    let itemsInput = document.createElement('input');
    itemsInput.type = 'hidden';
    itemsInput.name = 'items_json';
    document.getElementById('ventaForm').appendChild(itemsInput);

    agregarProducto.addEventListener('click', async function() {
        if (!productoId.value || !cantidad.value || !precio.value) {
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
                    producto_id: productoId.value,
                    cantidad: cantidad.value
                })
            });
            const data = await response.json();
            if (!data.success) {
                alert(data.message);
                return;
            }
            const subtotalItem = (parseFloat(cantidad.value) * parseFloat(precio.value)) - (parseFloat(descuento.value) || 0);
            // Obtener la descripción del producto seleccionado
            const selected = productoId.options[productoId.selectedIndex];
            const descripcion = selected ? selected.text : '';
            items.push({
                producto_id: productoId.value,
                descripcion: descripcion,
                cantidad: cantidad.value,
                precio: precio.value,
                descuento: descuento.value,
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
        // Actualizar el campo oculto con los items serializados
        itemsInput.value = JSON.stringify(items);
        const tbody = document.getElementById('detalleVenta');
        tbody.innerHTML = '';
        subtotal = 0;
        items.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${item.descripcion}
                    <input type="hidden" name="items[${index}][producto_id]" value="${item.producto_id}">
                    <input type="hidden" name="items[${index}][cantidad]" value="${item.cantidad}">
                    <input type="hidden" name="items[${index}][precio]" value="${item.precio}">
                    <input type="hidden" name="items[${index}][descuento]" value="${item.descuento}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.cantidad}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${parseFloat(item.precio).toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${item.descuento ? ('-' + parseFloat(item.descuento).toFixed(2)) : '0.00'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${parseFloat(item.subtotal).toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" onclick="eliminarItem(${index})"
                            class="text-red-600 hover:text-red-900">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
            subtotal += parseFloat(item.subtotal);
        });
        igv = subtotal * IGV_RATE;
        total = subtotal + igv;
        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('igv').textContent = igv.toFixed(2);
        document.getElementById('total').textContent = total.toFixed(2);
        document.getElementById('total_input').value = total;
        document.getElementById('igv_input').value = igv;
    }

    window.eliminarItem = function(index) {
        items.splice(index, 1);
        actualizarTabla();
    }

    function limpiarFormularioProducto() {
        productoId.value = '';
        cantidad.value = '';
        precio.value = '';
        descuento.value = 0;
        stockDisp.value = '';
    }

    // Interceptar submit para validar y serializar items
    document.getElementById('ventaForm').addEventListener('submit', function(e) {
        if (items.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la venta.');
            return false;
        }
        itemsInput.value = JSON.stringify(items);
    });

    // Mostrar/ocultar campos de crédito
    document.getElementById('tipo_pago').addEventListener('change', function() {
        const creditoFields = document.getElementById('credito_fields');
        if (this.value === 'credito') {
            creditoFields.classList.remove('hidden');
        } else {
            creditoFields.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection