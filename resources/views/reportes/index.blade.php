@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Reportes</h1>
            <p class="mt-2 text-sm text-gray-700">
                Genere reportes personalizados según sus necesidades.
            </p>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <div class="shadow-sm ring-1 ring-black ring-opacity-5">
                    <form action="{{ route('reportes.exportar') }}" method="POST" class="divide-y divide-gray-300">
                        @csrf
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Reporte</label>
                                    <select id="tipo" name="tipo" required
                                            class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Seleccione un tipo</option>
                                        <option value="ingresos">Ingresos</option>
                                        <option value="egresos">Egresos</option>
                                        <option value="inventario">Inventario</option>
                                        <option value="ventas">Ventas</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="periodo" class="block text-sm font-medium text-gray-700">Periodo</label>
                                    <select id="periodo" name="periodo" required
                                            class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Seleccione un periodo</option>
                                        <option value="diario">Diario</option>
                                        <option value="semanal">Semanal</option>
                                        <option value="mensual">Mensual</option>
                                        <option value="trimestral">Trimestral</option>
                                        <option value="anual">Anual</option>
                                        <option value="personalizado">Personalizado</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="formato" class="block text-sm font-medium text-gray-700">Formato</label>
                                    <select id="formato" name="formato" required
                                            class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-3" id="fecha_inicio_container">
                                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div class="sm:col-span-3" id="fecha_fin_container">
                                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Generar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($resultados))
    <div class="mt-8">
        <div class="flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Fecha</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descripción</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($resultados as $resultado)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        {{ $resultado->id }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $resultado->fecha->format('d/m/Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $resultado->descripcion }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        S/ {{ number_format($resultado->monto, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="py-4 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-6">Total</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-900">
                                        S/ {{ number_format($resultados->sum('monto'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    const periodoSelect = document.getElementById('periodo');
    const fechaInicioContainer = document.getElementById('fecha_inicio_container');
    const fechaFinContainer = document.getElementById('fecha_fin_container');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    function toggleFechas() {
        const showFechas = periodoSelect.value === 'personalizado';
        fechaInicioContainer.style.display = showFechas ? 'block' : 'none';
        fechaFinContainer.style.display = showFechas ? 'block' : 'none';
        fechaInicio.required = showFechas;
        fechaFin.required = showFechas;
    }

    periodoSelect.addEventListener('change', toggleFechas);
    toggleFechas();

    // Establecer fechas por defecto para periodos predefinidos
    periodoSelect.addEventListener('change', function() {
        const today = new Date();
        let start = new Date();
        let end = new Date();

        switch(this.value) {
            case 'diario':
                // Hoy
                break;
            case 'semanal':
                start.setDate(today.getDate() - 7);
                break;
            case 'mensual':
                start.setMonth(today.getMonth() - 1);
                break;
            case 'trimestral':
                start.setMonth(today.getMonth() - 3);
                break;
            case 'anual':
                start.setFullYear(today.getFullYear() - 1);
                break;
        }

        if (this.value !== 'personalizado') {
            fechaInicio.value = start.toISOString().split('T')[0];
            fechaFin.value = end.toISOString().split('T')[0];
        }
    });
</script>
@endpush
@endsection