@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Información General -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                <form action="{{ route('configuracion.actualizar-general') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4">
                        <div>
                            <label for="razon_social" class="block text-sm font-medium text-gray-700">Razón Social</label>
                            <input type="text" name="razon_social" id="razon_social" 
                                   value="{{ $configuraciones['razon_social']->valor ?? old('razon_social') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="ruc" class="block text-sm font-medium text-gray-700">RUC</label>
                            <input type="text" name="ruc" id="ruc" 
                                   value="{{ $configuraciones['ruc']->valor ?? old('ruc') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" name="direccion" id="direccion" 
                                   value="{{ $configuraciones['direccion']->valor ?? old('direccion') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" 
                                   value="{{ $configuraciones['telefono']->valor ?? old('telefono') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ $configuraciones['email']->valor ?? old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        @if($configuraciones['logo']->valor ?? false)
                            <div>
                                <img src="{{ Storage::url($configuraciones['logo']->valor) }}" alt="Logo actual" class="h-20 w-auto">
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documentos -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Documentos</h3>
                <form action="{{ route('configuracion.actualizar-documentos') }}" method="POST">
                    @csrf
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4">
                        <div>
                            <label for="serie_boleta" class="block text-sm font-medium text-gray-700">Serie Boleta</label>
                            <input type="text" name="serie_boleta" id="serie_boleta" 
                                   value="{{ $configuraciones['serie_boleta']->valor ?? old('serie_boleta') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="serie_factura" class="block text-sm font-medium text-gray-700">Serie Factura</label>
                            <input type="text" name="serie_factura" id="serie_factura" 
                                   value="{{ $configuraciones['serie_factura']->valor ?? old('serie_factura') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="igv" class="block text-sm font-medium text-gray-700">IGV (%)</label>
                            <input type="number" name="igv" id="igv" step="0.01" min="0" max="1"
                                   value="{{ $configuraciones['igv']->valor ?? old('igv') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inventario -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Inventario</h3>
                <form action="{{ route('configuracion.actualizar-inventario') }}" method="POST">
                    @csrf
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4">
                        <div>
                            <label for="alerta_stock" class="block text-sm font-medium text-gray-700">Alertas de Stock</label>
                            <select name="alerta_stock" id="alerta_stock" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="1" {{ ($configuraciones['alerta_stock']->valor ?? false) ? 'selected' : '' }}>Activado</option>
                                <option value="0" {{ !($configuraciones['alerta_stock']->valor ?? true) ? 'selected' : '' }}>Desactivado</option>
                            </select>
                        </div>

                        <div>
                            <label for="alerta_stock_dias" class="block text-sm font-medium text-gray-700">Días de anticipación</label>
                            <input type="number" name="alerta_stock_dias" id="alerta_stock_dias" min="1" max="90"
                                   value="{{ $configuraciones['alerta_stock_dias']->valor ?? old('alerta_stock_dias') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Respaldo y Restauración -->
    <div class="mt-8 bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900">Respaldo y Restauración</h3>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-base font-medium text-gray-700">Crear respaldo</h4>
                    <p class="mt-1 text-sm text-gray-500">Genera un respaldo de la base de datos actual.</p>
                    <form action="{{ route('configuracion.backup') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Generar respaldo
                        </button>
                    </form>
                </div>

                <div>
                    <h4 class="text-base font-medium text-gray-700">Restaurar respaldo</h4>
                    <p class="mt-1 text-sm text-gray-500">Restaura la base de datos desde un archivo de respaldo.</p>
                    <form action="{{ route('configuracion.restore') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <input type="file" name="backup_file" accept=".zip" required
                                   class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Restaurar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection