@extends('layouts.app')

@section('title','Registrar Venta')
@section('content')
<div class="max-w-5xl mx-auto mt-8 mb-8 p-6 bg-white rounded-lg shadow-lg relative z-10">
    <h1 class="text-2xl font-bold mb-4">Registrar Venta</h1>

    <form id="formVenta" action="{{ route('ventas.store') }}" method="POST">
        @csrf

        {{-- Cliente --}}
        <div class="mb-4 relative shadow-lg rounded-lg bg-white p-4">
            <label class="font-semibold block mb-2">Cliente</label>
            <div class="flex gap-2">
                <input type="text" id="buscarCliente" class="border rounded px-3 py-2 w-full focus:ring focus:border-blue-400" placeholder="Buscar cliente..." autocomplete="off">
                <input type="hidden" name="cliente_id" id="cliente_id">
                <button type="button" id="btnNuevoCliente" class="bg-green-600 text-white px-3 py-2 rounded flex items-center gap-1"><span>+ Nuevo</span></button>
            </div>
            <div id="resultadosClientes" class="absolute bg-white border w-full rounded mt-1 z-20 hidden max-h-56 overflow-auto"></div>
            <div id="infoCliente" class="mt-2 text-sm text-gray-700"></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2" id="camposExtraCliente">
                <div>
                    <label class="text-xs text-gray-500">Email</label>
                    <input type="email" id="cliente_email" class="border rounded px-2 py-1 w-full bg-gray-50" readonly>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Dirección</label>
                    <input type="text" id="cliente_direccion" class="border rounded px-2 py-1 w-full bg-gray-50" readonly>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Teléfono</label>
                    <input type="text" id="cliente_telefono" class="border rounded px-2 py-1 w-full bg-gray-50" readonly>
                </div>
            </div>
        </div>

        {{-- Datos extras --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="font-medium">Tipo venta</label>
                <select name="tipo_venta" class="border rounded w-full p-2">
                    <option value="contado">Contado</option><option value="credito">Crédito</option>
                </select>
            </div>
            <div>
                <label class="font-medium">Tipo comprobante</label>
                <select name="tipo_comprobante" class="border rounded w-full p-2">
                    <option value="BOLETA">Boleta</option><option value="FACTURA">Factura</option>
                </select>
            </div>
            <div>
                <label class="font-medium">Moneda</label>
                <select name="moneda_id" class="border rounded w-full p-2">
                    <option value="1">Soles</option><option value="2">Dólares</option>
                </select>
            </div>
            <div>
                <label class="font-medium">Fecha</label>
                <input type="date" name="fecha" class="border rounded w-full p-2" value="{{ date('Y-m-d') }}">
            </div>
        </div>

        {{-- Buscador producto --}}
        <div class="mb-2 relative">
            <input type="text" id="buscarProducto" class="border rounded px-3 py-2 w-full focus:ring focus:border-blue-400" placeholder="Buscar producto..." autocomplete="off">
            <div id="spinnerProducto" class="absolute right-2 top-2 hidden"><svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg></div>
            <div id="resultadosProductos" class="absolute bg-white border w-full rounded mt-1 z-20 hidden max-h-56 overflow-auto"></div>
        </div>

        {{-- Tabla detalle --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border rounded-lg shadow-lg bg-white" id="tabla-detalle">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Código</th>
                        <th class="p-2 border">Descripción</th>
                        <th class="p-2 border">Stock</th>
                        <th class="p-2 border">Cant.</th>
                        <th class="p-2 border">Precio</th>
                        <th class="p-2 border">Desc (%)</th>
                        <th class="p-2 border">Recargo</th>
                        <th class="p-2 border">Subtotal</th>
                        <th class="p-2 border">Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle-body">
                    <!-- filas agregadas dinámicamente -->
                </tbody>
            </table>
            <div class="flex justify-end mt-2">
                <button type="button" id="btnLimpiarProductos" class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300">Limpiar productos</button>
            </div>
        </div>

        {{-- Totales --}}
        <div class="mt-4 flex justify-end space-x-6 items-end">
            <div class="flex-1">
                <label class="font-medium">Observaciones</label>
                <textarea name="observaciones" rows="2" class="border rounded w-full p-2" placeholder="Notas internas, observaciones, etc..."></textarea>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Subtotal</div>
                <div id="subtotal_global" class="text-lg font-bold">0.00</div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Descuento total</div>
                <div id="descuento_global" class="text-lg font-bold">0.00</div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Recargo total</div>
                <div id="recargo_global" class="text-lg font-bold">0.00</div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Total</div>
                <div id="total_global" class="text-2xl font-extrabold">0.00</div>
                <input type="hidden" name="subtotal" id="input_subtotal">
                <input type="hidden" name="descuento_total" id="input_descuento_total">
                <input type="hidden" name="recargo_total" id="input_recargo_total">
                <input type="hidden" name="total" id="input_total">
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" id="btnGuardarVenta" class="bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Guardar Venta
            </button>
        </div>
    </form>
</div>

<!-- Modal nuevo cliente -->
<div id="modalCliente" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white w-[32rem] p-4 rounded shadow-lg">
        <h3 class="font-bold mb-2">Nuevo cliente</h3>
        <form id="formCliente">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                <input name="nombre" placeholder="Nombre" class="border rounded px-2 py-1" required>
                <input name="apellido" placeholder="Apellido" class="border rounded px-2 py-1">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                <input name="dni" placeholder="DNI" class="border rounded px-2 py-1">
                <input name="ruc" placeholder="RUC" class="border rounded px-2 py-1">
            </div>
            <input name="direccion" placeholder="Dirección" class="w-full border rounded px-2 py-1 mb-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                <select name="region_id" id="region_id" class="border rounded px-2 py-1">
                    <option value="">Seleccione región</option>
                    @foreach(App\Models\Region::all() as $region)
                        <option value="{{ $region->id }}">{{ $region->nombre }}</option>
                    @endforeach
                </select>
                <select name="provincia_id" id="provincia_id" class="border rounded px-2 py-1">
                    <option value="">Seleccione provincia</option>
                </select>
                <select name="distrito_id" id="distrito_id" class="border rounded px-2 py-1">
                    <option value="">Seleccione distrito</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                <input name="tipo_cliente" placeholder="Tipo de cliente" class="border rounded px-2 py-1">
                <input name="telefono" placeholder="Teléfono" class="border rounded px-2 py-1">
            </div>
            <input name="email" type="email" placeholder="Email" class="w-full border rounded px-2 py-1 mb-2">
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" id="cerrarModal" class="px-3 py-1 bg-gray-200 rounded">Cancelar</button>
                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // --- Selects dinámicos de región/provincia/distrito en modal cliente ---
    const regionSelect = document.getElementById('region_id');
    const provinciaSelect = document.getElementById('provincia_id');
    const distritoSelect = document.getElementById('distrito_id');

    // --- Cache local para provincias y distritos ---
    const cacheProvincias = {};
    const cacheDistritos = {};

    regionSelect?.addEventListener('change', async function() {
        provinciaSelect.innerHTML = '<option value="">Cargando...</option>';
        distritoSelect.innerHTML = '<option value="">Seleccione distrito</option>';
        if (!this.value) {
            provinciaSelect.innerHTML = '<option value="">Seleccione provincia</option>';
            return;
        }
        if (cacheProvincias[this.value]) {
            const provincias = cacheProvincias[this.value];
            provinciaSelect.innerHTML = '<option value="">Seleccione provincia</option>' + provincias.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('');
        } else {
            const res = await fetch(`/clientes/provincias/${this.value}`);
            const provincias = await res.json();
            cacheProvincias[this.value] = provincias;
            provinciaSelect.innerHTML = '<option value="">Seleccione provincia</option>' + provincias.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('');
        }
    });

    provinciaSelect?.addEventListener('change', async function() {
        distritoSelect.innerHTML = '<option value="">Cargando...</option>';
        if (!this.value) {
            distritoSelect.innerHTML = '<option value="">Seleccione distrito</option>';
            return;
        }
        if (cacheDistritos[this.value]) {
            const distritos = cacheDistritos[this.value];
            distritoSelect.innerHTML = '<option value="">Seleccione distrito</option>' + distritos.map(d => `<option value="${d.id}">${d.nombre}</option>`).join('');
        } else {
            const res = await fetch(`/clientes/distritos/${this.value}`);
            const distritos = await res.json();
            cacheDistritos[this.value] = distritos;
            distritoSelect.innerHTML = '<option value="">Seleccione distrito</option>' + distritos.map(d => `<option value="${d.id}">${d.nombre}</option>`).join('');
        }
    });
document.addEventListener('DOMContentLoaded', () => {
    const buscarCliente = document.getElementById('buscarCliente');
    const resultadosClientes = document.getElementById('resultadosClientes');
    const clienteInputHidden = document.getElementById('cliente_id');

    const buscarProducto = document.getElementById('buscarProducto');
    const resultadosProductos = document.getElementById('resultadosProductos');

    const detalleBody = document.getElementById('detalle-body');

    const modal = document.getElementById('modalCliente');
    const btnNuevoCliente = document.getElementById('btnNuevoCliente');
    const cerrarModal = document.getElementById('cerrarModal');
    const formCliente = document.getElementById('formCliente');

    // ---------- CLIENTES (autocomplete) ----------
    let clienteTimeout;
    function cargarClientes(q = '') {
        clearTimeout(clienteTimeout);
        clienteTimeout = setTimeout(async () => {
            const res = await fetch(`/clientes/buscar?q=${encodeURIComponent(q)}`);
            const data = await res.json();
            if (data.length === 0) {
                resultadosClientes.innerHTML = `<div class='p-2 text-gray-500'>No se encontraron clientes</div>`;
                resultadosClientes.classList.remove('hidden');
                clienteInputHidden.value = '';
                document.getElementById('infoCliente').textContent = '';
                return;
            }
            resultadosClientes.innerHTML = data.map(c => 
                `<div class="p-2 hover:bg-gray-100 cursor-pointer" data-id="${c.id}" data-nombre="${c.nombre}" data-documento="${c.documento ?? ''}">
                    ${c.nombre} ${c.documento ? ' - ' + c.documento : ''}
                </div>`
            ).join('');
            resultadosClientes.classList.remove('hidden');
        }, 300);
    }

    buscarCliente?.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 1) {
            cargarClientes('');
            clienteInputHidden.value = '';
            document.getElementById('infoCliente').textContent = '';
            return;
        }
        cargarClientes(q);
    });

    buscarCliente?.addEventListener('focus', function() {
        if (this.value.trim().length < 1) {
            cargarClientes('');
        }
    });

    resultadosClientes?.addEventListener('click', function(e) {
        const div = e.target.closest('div[data-id]');
        if (!div) return;
        clienteInputHidden.value = div.dataset.id;
        buscarCliente.value = div.dataset.nombre;
        document.getElementById('infoCliente').textContent = `Seleccionado: ${div.dataset.nombre} ${div.dataset.documento ? ' - ' + div.dataset.documento : ''}`;
        // Simulación de datos extra (en real, deberías obtenerlos por AJAX)
        document.getElementById('cliente_email').value = div.dataset.email || '';
        document.getElementById('cliente_direccion').value = div.dataset.direccion || '';
        document.getElementById('cliente_telefono').value = div.dataset.telefono || '';
        resultadosClientes.classList.add('hidden');
        validarGuardarVenta();
    });

    document.addEventListener('click', e => {
        if (!buscarCliente?.contains(e.target) && !resultadosClientes?.contains(e.target)) {
            resultadosClientes?.classList.add('hidden');
        }
    });

    // ---------- MODAL CLIENTE ----------
    btnNuevoCliente.onclick = () => modal.classList.remove('hidden');
    cerrarModal.onclick = () => modal.classList.add('hidden');

    formCliente.onsubmit = async function(e) {
        e.preventDefault();
        const f = new FormData(this);
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const res = await fetch('/clientes/crear', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token },
            body: f
        });
        if (!res.ok) return alert('Error creando cliente');
        const nuevo = await res.json();
        document.getElementById('cliente_id').value = nuevo.id;
        buscarCliente.value = nuevo.nombre;
        document.getElementById('infoCliente').textContent = `Seleccionado: ${nuevo.nombre} ${nuevo.documento ? ' - ' + nuevo.documento : ''}`;
        modal.classList.add('hidden');
        this.reset();
        toast('Cliente registrado correctamente', 'success');
        validarGuardarVenta();
    };

    // ---------- PRODUCTOS (autocomplete) ----------
    buscarProducto?.addEventListener('input', async function() {
        const q = this.value.trim();
        document.getElementById('spinnerProducto').classList.remove('hidden');
        if (q.length < 1) { resultadosProductos.classList.add('hidden'); document.getElementById('spinnerProducto').classList.add('hidden'); return; }
        const res = await fetch(`/productos/buscar?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (data.length === 0) {
            resultadosProductos.innerHTML = `<div class='p-2 text-gray-500'>No se encontraron productos</div>`;
            resultadosProductos.classList.remove('hidden');
            document.getElementById('spinnerProducto').classList.add('hidden');
            return;
        }
        resultadosProductos.innerHTML = data.map(p =>
            `<div class="p-2 hover:bg-gray-100 cursor-pointer flex justify-between items-center" 
                  data-id="${p.id}" data-codigo="${p.codigo_producto}" data-nombre="${p.descripcion}" 
                  data-precio="${p.precio_unitario}" data-stock="${p.stock_actual}">
                <div>${p.codigo_producto} - ${p.descripcion}</div>
                <div class="text-sm text-green-600">S/ ${parseFloat(p.precio_unitario).toLocaleString('es-PE', {minimumFractionDigits:2})}</div>
            </div>`
        ).join('');
        resultadosProductos.classList.remove('hidden');
        document.getElementById('spinnerProducto').classList.add('hidden');
    });

    resultadosProductos?.addEventListener('click', function(e) {
        const div = e.target.closest('div[data-id]');
        if (!div) return;
        const p = {
            id: div.dataset.id,
            codigo: div.dataset.codigo,
            nombre: div.dataset.nombre,
            precio: parseFloat(div.dataset.precio || 0),
            stock: parseFloat(div.dataset.stock || 0)
        };
        // Si el producto ya está en la tabla, suma cantidad
        let existe = false;
        document.querySelectorAll('#detalle-body tr').forEach(tr => {
            if (tr.querySelector('[name="producto_id[]"]').value == p.id) {
                let cantidadInput = tr.querySelector('[name="cantidad[]"]');
                cantidadInput.value = parseInt(cantidadInput.value) + 1;
                // Actualiza precio y stock automáticamente
                tr.querySelector('[name="precio_unitario[]"]').value = p.precio.toFixed(2);
                tr.querySelector('[name="stock[]"]').value = p.stock;
                tr.querySelector('[name="precio_unitario[]"]').setAttribute('readonly', true);
                tr.querySelector('[name="stock[]"]').setAttribute('readonly', true);
                existe = true;
                filaActualizar(cantidadInput);
            }
        });
        if (!existe) {
            agregarFilaProducto(p);
            // Bloquear edición manual de precio y stock en la nueva fila
            const filas = document.querySelectorAll('#detalle-body tr');
            const ultima = filas[filas.length-1];
            ultima.querySelector('[name="precio_unitario[]"]').setAttribute('readonly', true);
            ultima.querySelector('[name="stock[]"]').setAttribute('readonly', true);
        }
        buscarProducto.value = '';
        resultadosProductos.classList.add('hidden');
        validarGuardarVenta();
    });

    document.addEventListener('click', e => {
        if (!buscarProducto?.contains(e.target) && !resultadosProductos?.contains(e.target)) {
            resultadosProductos?.classList.add('hidden');
        }
    });

    // ---------- FUNCIONES DE FILAS Y CÁLCULOS ----------
    function agregarFilaProducto(p) {
        const tr = document.createElement('tr');
        tr.classList.add('bg-gray-50','hover:bg-blue-50');
        tr.innerHTML = `
            <td class="p-2 border"><input name="codigo_producto[]" value="${p.codigo}" readonly class="w-full bg-transparent"></td>
            <td class="p-2 border"><input name="descripcion_detalle[]" value="${p.nombre}" readonly class="w-full bg-transparent"></td>
            <td class="p-2 border text-center">
                <input name="stock[]" value="${p.stock}" readonly class="w-16 text-center bg-transparent">
                <div class="text-xs text-gray-500">Stock disponible</div>
            </td>
            <td class="p-2 border text-center">
                <input name="cantidad[]" type="number" value="1" min="1" max="${p.stock}" class="w-20 text-center" onchange="filaActualizar(this)">
                <div class="text-xs text-red-500 hidden" id="alertaStock">Cantidad supera stock</div>
            </td>
            <td class="p-2 border text-center"><input name="precio_unitario[]" type="number" step="0.01" value="${p.precio.toFixed(2)}" class="w-24 text-center" onchange="filaActualizar(this)"></td>
            <td class="p-2 border text-center"><input name="descuento_pct[]" type="number" min="0" max="100" value="0" class="w-20 text-center" onchange="filaActualizar(this)"></td>
            <td class="p-2 border text-center"><input name="recargo[]" type="number" step="0.01" value="0" class="w-20 text-center" onchange="filaActualizar(this)"></td>
            <td class="p-2 border text-center"><input name="subtotal[]" value="${p.precio.toFixed(2)}" readonly class="w-28 text-right bg-transparent"></td>
            <td class="p-2 border text-center">
                <button type="button" class="text-red-500" onclick="this.closest('tr').remove(); actualizarGlobal(); validarGuardarVenta();">✖</button>
            </td>
            <input type="hidden" name="producto_id[]" value="${p.id}">
            <input type="hidden" name="numero_parte[]" value="">
        `;
        detalleBody.appendChild(tr);
        actualizarGlobal();
        validarGuardarVenta();
    }

    // función global para recalcular todos los totales
    window.filaActualizar = function(el) {
        const tr = el.closest('tr');
        const cantidadInput = tr.querySelector('[name="cantidad[]"]');
        const cantidad = parseFloat(cantidadInput.value || 0);
        const stock = parseFloat(tr.querySelector('[name="stock[]"]').value || 0);
        const alerta = tr.querySelector('#alertaStock');
        if (cantidad > stock) {
            cantidadInput.classList.add('border-red-500');
            alerta.classList.remove('hidden');
        } else {
            cantidadInput.classList.remove('border-red-500');
            alerta.classList.add('hidden');
        }
        const precio = parseFloat(tr.querySelector('[name="precio_unitario[]"]').value || 0);
        const descuento_pct = parseFloat(tr.querySelector('[name="descuento_pct[]"]').value || 0);
        const recargo = parseFloat(tr.querySelector('[name="recargo[]"]').value || 0);

        const descuento_monto = (precio * (descuento_pct/100)) * cantidad;
        const subtotal = (precio * cantidad) - descuento_monto + recargo;
        tr.querySelector('[name="subtotal[]"]').value = subtotal.toFixed(2);

        actualizarGlobal();
    };

    function actualizarGlobal() {
        let subtotal = 0, descuento = 0, recargo = 0, errorStock = false;
        document.querySelectorAll('#detalle-body tr').forEach(tr => {
            const cant = parseFloat(tr.querySelector('[name="cantidad[]"]').value || 0);
            const precio = parseFloat(tr.querySelector('[name="precio_unitario[]"]').value || 0);
            const descuento_pct = parseFloat(tr.querySelector('[name="descuento_pct[]"]').value || 0);
            const rec = parseFloat(tr.querySelector('[name="recargo[]"]').value || 0);
            const stock = parseFloat(tr.querySelector('[name="stock[]"]').value || 0);

            if (cant > stock) {
                tr.querySelector('[name="cantidad[]"]').classList.add('border-red-500');
                errorStock = true;
            } else {
                tr.querySelector('[name="cantidad[]"]').classList.remove('border-red-500');
            }

            const desc_monto = (precio * (descuento_pct/100)) * cant;
            const line_subtotal = (precio * cant);

            subtotal += line_subtotal;
            descuento += desc_monto;
            recargo += rec;
        });

        const total = subtotal - descuento + recargo;

        document.getElementById('subtotal_global').textContent = subtotal.toLocaleString('es-PE', {minimumFractionDigits:2});
        document.getElementById('descuento_global').textContent = descuento.toLocaleString('es-PE', {minimumFractionDigits:2});
        document.getElementById('recargo_global').textContent = recargo.toLocaleString('es-PE', {minimumFractionDigits:2});
        document.getElementById('total_global').textContent = total.toLocaleString('es-PE', {minimumFractionDigits:2});

        // hidden inputs para enviar al servidor
        document.getElementById('input_subtotal').value = subtotal.toFixed(2);
        document.getElementById('input_descuento_total').value = descuento.toFixed(2);
        document.getElementById('input_recargo_total').value = recargo.toFixed(2);
        document.getElementById('input_total').value = total.toFixed(2);

        // Deshabilitar guardar si hay error de stock
        validarGuardarVenta(errorStock);
    }

    // Limpiar productos
    document.getElementById('btnLimpiarProductos').onclick = () => {
        detalleBody.innerHTML = '';
        actualizarGlobal();
        validarGuardarVenta();
    };

    // Validar botón guardar
    function validarGuardarVenta(errorStock = false) {
        const btn = document.getElementById('btnGuardarVenta');
        const clienteId = document.getElementById('cliente_id').value;
        const productos = document.querySelectorAll('#detalle-body tr').length;
        btn.disabled = !clienteId || productos === 0 || errorStock;
    }

    // Toast de éxito
    window.toast = function(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = `fixed top-4 right-4 px-4 py-2 rounded shadow-lg z-50 text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 2500);
    }

    // Si necesitas, puedes agregar una fila inicial vacía
    // agregarFilaProducto({id:'',codigo:'',nombre:'',precio:0,stock:0});
});
</script>
@endpush
