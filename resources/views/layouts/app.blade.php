<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IRM Maquinarias') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @auth
            <!-- Sidebar -->
            <aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform bg-white border-r border-gray-200">
                <div class="h-full px-3 py-4 overflow-y-auto">
                    <div class="flex items-center mb-5 pl-2.5">
                        <span class="self-center text-xl font-semibold whitespace-nowrap text-gray-800">IRM Maquinarias</span>
                    </div>
                    <ul class="space-y-2 font-medium">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('dashboard') ? 'bg-gray-100' : '' }}">
                                <i class="fas fa-tachometer-alt w-5 h-5 text-gray-500"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                        </li>

                        <!-- Inventario -->
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="fas fa-boxes w-5 h-5 text-gray-500"></i>
                                <span class="flex-1 ml-3 text-left">Inventario</span>
                                <i class="fas fa-chevron-down w-3 h-3 text-gray-500 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                           
                            <ul x-show="open" class="pl-4 mt-2 space-y-2">
                                <li>
                                    <a href="{{ route('productos.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-box w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Productos</span>
                                    </a>
                                </li>
                                @if(Route::has('categorias.index'))
                                <li>
                                    <a href="{{ route('categorias.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-tags w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Categorías</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>

                        <!-- Ventas -->
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="fas fa-shopping-cart w-5 h-5 text-gray-500"></i>
                                <span class="flex-1 ml-3 text-left">Ventas</span>
                                <i class="fas fa-chevron-down w-3 h-3 text-gray-500 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                            </button>
                            <ul x-show="open" class="pl-4 mt-2 space-y-2">
                                <li>
                                    <a href="{{ route('ventas.create') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-plus w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Nueva Venta</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('ventas.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-list w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Lista de Ventas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cotizaciones.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-file-invoice w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Cotizaciones</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Clientes -->
                        <li>
                            <a href="{{ route('clientes.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="fas fa-users w-5 h-5 text-gray-500"></i>
                                <span class="ml-3">Clientes</span>
                            </a>
                        </li>

                        <!-- Proveedores -->
                        <li>
                            <a href="{{ route('proveedores.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="fas fa-truck w-5 h-5 text-gray-500"></i>
                                <span class="ml-3">Proveedores</span>
                            </a>
                        </li>

                        <!-- Reportes -->
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="fas fa-chart-bar w-5 h-5 text-gray-500"></i>
                                <span class="flex-1 ml-3 text-left">Reportes</span>
                                <i class="fas fa-chevron-down w-3 h-3 text-gray-500 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                           
                            <ul x-show="open" class="pl-4 mt-2 space-y-2">
                                <li>
                                    <a href="{{ route('reportes.ventas') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-chart-line w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Reporte de Ventas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reportes.productos') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-boxes w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Reporte de Inventario</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reportes.clientes') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-user-chart w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Reporte de Clientes</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Configuración -->
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="fas fa-cog w-5 h-5 text-gray-500"></i>
                                <span class="flex-1 ml-3 text-left">Configuración</span>
                                <i class="fas fa-chevron-down w-3 h-3 text-gray-500 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                            </button>
                            <ul x-show="open" class="pl-4 mt-2 space-y-2">
                                <li>
                                    <a href="{{ route('usuarios.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-users-cog w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Usuarios</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('monedas.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-money-bill w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Monedas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('configuracion.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                        <i class="fas fa-sliders-h w-5 h-5 text-gray-500"></i>
                                        <span class="ml-3">Configuración General</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Top Navigation -->
            <nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200 ml-64">
                <div class="px-3 py-3 lg:px-5 lg:pl-3">
                    <div class="flex items-center justify-end">
                        <div class="flex items-center">
                            <div class="relative ml-3">
                                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="relative w-8 h-8 overflow-hidden bg-gray-100 rounded-full">
                                        <i class="fas fa-user absolute w-10 h-10 text-gray-400 -left-1"></i>
                                    </div>
                                </button>
                                <div class="hidden absolute right-0 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
                                    <div class="px-4 py-3">
                                        <p class="text-sm text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <ul class="py-1">
                                        <li>
                                            <a href="{{ route('logout') }}" 
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Cerrar Sesión
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-4 sm:ml-64 mt-14">
                <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                    @yield('content')
                </div>
            </div>
        @else
            <!-- Guest Layout -->
            <main>
                @yield('content')
            </main>
        @endauth
    </div>

    {{-- Low-stock floating alert modal (global) --}}
    {{-- Mostrar la alerta solo en el dashboard y excluir la vista de login --}}
    @if(Auth::check() && request()->is('dashboard') && !request()->is('login') && isset($alertaStockEnabled) && $alertaStockEnabled && isset($productosStockBajo) && $productosStockBajo->count() > 0)
        <div x-data="{ open: true }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end px-4 py-6 pointer-events-none sm:items-start sm:p-6">
            <div class="w-full max-w-sm mx-auto bg-white rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">Productos con stock bajo</p>
                            <p class="mt-1 text-sm text-gray-500">Se encontraron {{ $productosStockBajo->count() }} producto(s) por debajo del stock mínimo.</p>

                            <div class="mt-3">
                                <ul class="divide-y divide-gray-100 max-h-48 overflow-y-auto">
                                    @foreach($productosStockBajo as $producto)
                                        <li class="py-2">
                                            <p class="text-sm font-semibold">{{ $producto->nombre }}</p>
                                            <p class="text-xs text-gray-500">Código: {{ $producto->codigo ?? 'N/A' }} · Stock actual: {{ $producto->stock_actual }} (mínimo: {{ $producto->stock_minimo }})</p>
                                            <a href="{{ route('pedidos.create', ['producto_id' => $producto->id]) }}" class="inline-flex items-center px-2 py-1 mt-2 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">Realizar pedido</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <a href="{{ Route::has('productos.stock-bajo') ? route('productos.stock-bajo') : route('productos.index') }}" class="mr-2 inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Ver todos</a>
                    <button @click="open = false" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cerrar</button>
                </div>
            </div>
        </div>
    @endif

    {{-- ...existing code... --}}

    @php
        Log::info('Variables en layout:', [
            'alertaStockEnabled' => $alertaStockEnabled ?? null,
            'productosStockBajo' => isset($productosStockBajo) ? $productosStockBajo->count() : null
        ]);
    @endphp

    @php
        Log::info('Ruta actual:', ['ruta' => request()->route()->getName()]);
    @endphp

    <script>
        // Toggle user dropdown
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('dropdown-user');
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
