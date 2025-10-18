@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900">Configuración de Monedas y Tipo de Cambio</h3>
            
            <!-- Tipo de Cambio Actual -->
            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                <h4 class="text-base font-medium text-gray-700">Tipo de Cambio Actual</h4>
                <div class="mt-2 grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Valor:</span>
                        <span class="ml-2 text-lg font-semibold">{{ $configuraciones['tipo_cambio']->valor ?? '0.00' }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Última actualización:</span>
                        <span class="ml-2">{{ $configuraciones['ultima_actualizacion_tc']->valor ?? 'No disponible' }}</span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Actualización -->
            <form action="{{ route('configuracion.actualizar-tipo-cambio') }}" method="POST" class="mt-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="moneda_origen" class="block text-sm font-medium text-gray-700">Moneda Origen</label>
                        <select name="moneda_origen" id="moneda_origen" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}" 
                                    {{ $configuraciones['moneda_principal']->valor == $moneda->codigo ? 'selected' : '' }}>
                                    {{ $moneda->nombre }} ({{ $moneda->codigo }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="moneda_destino" class="block text-sm font-medium text-gray-700">Moneda Destino</label>
                        <select name="moneda_destino" id="moneda_destino" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}"
                                    {{ $configuraciones['moneda_secundaria']->valor == $moneda->codigo ? 'selected' : '' }}>
                                    {{ $moneda->nombre }} ({{ $moneda->codigo }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tipo_cambio" class="block text-sm font-medium text-gray-700">Tipo de Cambio</label>
                        <input type="number" name="tipo_cambio" id="tipo_cambio" step="0.0001" min="0" required
                               value="{{ old('tipo_cambio') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="fecha" id="fecha" required
                               value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Actualizar Tipo de Cambio
                    </button>
                </div>
            </form>

            <!-- Historial de Tipos de Cambio -->
            <div class="mt-8">
                <h4 class="text-base font-medium text-gray-700">Historial de Tipos de Cambio</h4>
                <div class="mt-4 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Fecha</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Moneda Origen</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Moneda Destino</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipo de Cambio</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($tiposCambio as $tc)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    {{ $tc->fecha->format('d/m/Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $tc->monedaOrigen->codigo }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $tc->monedaDestino->codigo }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ number_format($tc->tipo_cambio, 4) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection