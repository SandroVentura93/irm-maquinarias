@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Gestión de Inventario</h2>
            <div class="flex space-x-4">
                <a href="{{ route('productos.stock-bajo') }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Productos Bajos
                </a>
                <a href="{{ route('productos.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Registrar Producto
                </a>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           placeholder="Buscar por código, nombre o descripción del producto..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           id="searchInput">
                </div>
                <div class="w-full md:w-auto">
                    <select id="categoriaFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas las categorías</option>
                        @if(isset($categorias) && $categorias)
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->descripcion ?? $categoria->id }}">{{ $categoria->descripcion ?? $categoria }}</option>
                            @endforeach
                        @else
                            {{-- fallback: build categories from products list --}}
                            @php
                                $cats = $productos->pluck('categoria')->unique()->filter();
                            @endphp
                            @foreach($cats as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="w-full md:w-auto">
                    <select id="stockFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Nivel de Stock</option>
                        <option value="bajo">Stock Crítico</option>
                        <option value="normal">Stock Regular</option>
                        <option value="alto">Stock Óptimo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Existencias</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Opciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($productos as $producto)
                        <tr data-categoria="{{ $producto->categoria }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $producto->codigo }}</div>
                                <div class="text-sm text-gray-500">N° Parte: {{ $producto->numero_parte }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $producto->nombre ?? $producto->descripcion }}</div>
                                <div class="text-sm text-gray-500">
                                    Marca: {{ $producto->marca ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                    {{ number_format($producto->stock_actual, 3) }} unid.
                                </div>
                                <div class="text-xs text-gray-500">
                                    Mínimo: {{ number_format($producto->stock_minimo, 3) }} unid.
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    S/. {{ number_format($producto->precio_venta ?? $producto->precio_compra, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $producto->categoria ?? 'No categorizado' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $producto->activo ? 'Disponible' : 'No Disponible' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('productos.edit', $producto) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Modificar</a>
                                <form action="{{ route('productos.destroy', $producto) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('¿Está seguro que desea eliminar este producto del inventario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $productos->links() }}
        </div>

        <!-- Sin resultados -->
        <div id="noResultados" class="hidden mt-4 text-center py-4">
            <p class="text-gray-500">No se encontraron productos que coincidan con los criterios de búsqueda</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoriaFilter = document.getElementById('categoriaFilter');
    const stockFilter = document.getElementById('stockFilter');
    const noResultados = document.getElementById('noResultados');

    // Función para aplicar los filtros
    function aplicarFiltros() {
        const busqueda = searchInput.value.toLowerCase();
        const categoria = categoriaFilter.value;
        const stock = stockFilter.value;

        const rows = document.querySelectorAll('tbody tr');
        let productosVisibles = 0;

        rows.forEach(row => {
            let mostrar = true;

            // Filtro de búsqueda
            const texto = row.innerText.toLowerCase();
            if (busqueda && !texto.includes(busqueda)) {
                mostrar = false;
            }

            // Filtro de categoría (comparamos con data-categoria de la fila)
            if (categoria && row.getAttribute('data-categoria') !== categoria) {
                mostrar = false;
            }

            // Filtro de stock
            const stockActual = parseFloat(row.querySelector('td:nth-child(3)').innerText);
            const stockMinimo = parseFloat(row.querySelector('td:nth-child(3) .text-xs').innerText.replace('Mínimo: ', '').replace(' unid.', ''));
            
            if (stock === 'bajo' && stockActual > stockMinimo) {
                mostrar = false;
            } else if (stock === 'normal' && (stockActual <= stockMinimo || stockActual > stockMinimo * 2)) {
                mostrar = false;
            } else if (stock === 'alto' && stockActual <= stockMinimo * 2) {
                mostrar = false;
            }

            row.style.display = mostrar ? '' : 'none';
            if (mostrar) productosVisibles++;
        });

        // Mostrar mensaje de no resultados si es necesario
        noResultados.style.display = productosVisibles === 0 ? 'block' : 'none';
    }

    // Eventos
    searchInput.addEventListener('input', aplicarFiltros);
    categoriaFilter.addEventListener('change', aplicarFiltros);
    stockFilter.addEventListener('change', aplicarFiltros);
});
</script>
@endpush