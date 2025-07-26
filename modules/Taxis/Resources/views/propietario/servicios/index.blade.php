@extends('taxis::layouts.master')

@section('title', 'Mis Servicios de Vehículos - Propietario')

@section('content')
    @php
        function formatearNombreServicio($nombre)
        {
            // Mapeo de nombres comunes de servicios
            $serviciosFormateados = [
                'soat' => 'SOAT',
                'afocat' => 'AFOCAT',
                'revision_tecnica' => 'Revisión Técnica',
                'mantenimiento' => 'Mantenimiento',
                'poliza_seguro' => 'Póliza de Seguro',
                'licencia_conductor' => 'Licencia de Conductor',
                'citv' => 'CITV',
                'inspeccion_tecnica' => 'Inspección Técnica',
                'seguro_obligatorio' => 'Seguro Obligatorio',
                'tarjeta_propiedad' => 'Tarjeta de Propiedad',
            ];

            $nombreLower = strtolower(trim($nombre));

            // Si existe en el mapeo, usar el formato correcto
            if (isset($serviciosFormateados[$nombreLower])) {
                return $serviciosFormateados[$nombreLower];
            }

            // Si no existe en el mapeo, aplicar formato general
            return ucwords(str_replace(['_', '-'], ' ', $nombreLower));
        }
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Servicios de Vehículos</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona los servicios y vencimientos de tus vehículos</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                        {{ $servicios->total() }} servicio{{ $servicios->total() != 1 ? 's' : '' }}
                        @if (request()->hasAny(['estado', 'tipo_servicio', 'vehiculo_id', 'search']))
                            filtrado{{ $servicios->total() != 1 ? 's' : '' }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back to Dashboard button -->
                <a href="{{ route('taxis.propietario.dashboard') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" transform="rotate(180 8 8)" />
                    </svg>
                    <span class="max-xs:sr-only">Volver al Dashboard</span>
                </a>
            </div>

        </div>

        <!-- Filters -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <form method="GET" action="{{ route('taxis.propietario.servicios') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                            <select name="estado" class="form-select text-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="">Todos los estados</option>
                                <option value="vencido" {{ request('estado') == 'vencido' ? 'selected' : '' }}>Vencidos
                                </option>
                                <option value="proximo_vencer"
                                    {{ request('estado') == 'proximo_vencer' ? 'selected' : '' }}>Próximos a vencer (30
                                    días)</option>
                                <option value="vigente" {{ request('estado') == 'vigente' ? 'selected' : '' }}>Vigentes
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de
                                Servicio</label>
                            <select name="tipo_servicio" class="form-select text-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="">Todos los tipos</option>
                                @foreach ($tiposServicios as $tipoValue => $tipoLabel)
                                    <option value="{{ $tipoValue }}"
                                        {{ request('tipo_servicio') == $tipoValue ? 'selected' : '' }}>{{ $tipoLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vehículo</label>
                            <select name="vehiculo_id" class="form-select text-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="">Todos los vehículos</option>
                                @foreach ($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}"
                                        {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                        {{ $vehiculo->placa }} - {{ $vehiculo->marca->nombre ?? '' }}
                                        {{ $vehiculo->modelo->nombre ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                            <input type="text" name="search" class="form-input text-sm w-full"
                                placeholder="Buscar servicio..." value="{{ request('search') }}">
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                                <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                                    <path
                                        d="m15.707 14.293-1.414 1.414a1 1 0 0 1-1.414 0L9.586 12.414A6 6 0 1 1 12.414 9.586l3.293 3.293a1 1 0 0 1 0 1.414ZM6 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                </svg>
                                <span class="ml-1">Buscar</span>
                            </button>
                            @if (request()->hasAny(['estado', 'tipo_servicio', 'vehiculo_id', 'search']))
                                <a href="{{ route('taxis.propietario.servicios') }}"
                                    class="btn border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600">Limpiar</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if (request()->hasAny(['estado', 'tipo_servicio', 'vehiculo_id', 'search']))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                            </svg>
                            <span class="text-blue-800 dark:text-blue-200 font-medium">Filtros activos</span>
                        </div>
                        <a href="{{ route('taxis.propietario.servicios') }}"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Servicios Grid -->
        @if ($servicios->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach ($servicios as $servicio)
                    @php
                        $diasRestantes = $servicio->diasHastaVencimiento();
                        $estaVencido = $servicio->estaVencido();
                        $proximoVencer = $servicio->estaProximoAVencer(30);

                        // Calcular porcentaje para barra de progreso (asumiendo 365 días como ciclo completo)
                        $diasTotales = 365;
                        $porcentaje =
                            $diasRestantes !== null
                                ? max(0, min(100, (($diasTotales - abs($diasRestantes)) / $diasTotales) * 100))
                                : 0;

                        // Clases de estado
                        if ($estaVencido) {
                            $estadoClass = 'bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400';
                            $barraClass = 'bg-red-500';
                            $estadoTexto = 'Vencido';
                        } elseif ($proximoVencer) {
                            $estadoClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-400/10 dark:text-yellow-400';
                            $barraClass = 'bg-yellow-500';
                            $estadoTexto = 'Próximo a vencer';
                        } else {
                            $estadoClass = 'bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400';
                            $barraClass = 'bg-green-500';
                            $estadoTexto = 'Vigente';
                        }
                    @endphp
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/60 hover:shadow-lg transition-shadow duration-200">

                        <!-- Servicio Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ formatearNombreServicio($servicio->name) }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $servicio->vehiculo->placa ?? 'Sin placa' }} -
                                    {{ $servicio->vehiculo->marca->nombre ?? '' }}
                                    {{ $servicio->vehiculo->modelo->nombre ?? '' }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoClass }}">
                                {{ $estadoTexto }}
                            </span>
                        </div>

                        <!-- Detalles del Servicio -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Fecha de vencimiento:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $servicio->expires_date ? $servicio->expires_date->format('d/m/Y') : 'No definida' }}
                                </span>
                            </div>

                            @if ($diasRestantes !== null)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Días restantes:</span>
                                    <span
                                        class="font-medium {{ $estaVencido ? 'text-red-600 dark:text-red-400' : ($proximoVencer ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                        @if ($estaVencido)
                                            Vencido hace {{ abs($diasRestantes) }}
                                            día{{ abs($diasRestantes) != 1 ? 's' : '' }}
                                        @else
                                            {{ $diasRestantes }} día{{ $diasRestantes != 1 ? 's' : '' }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Barra de progreso -->
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400">
                                        <span>Progreso del ciclo</span>
                                        <span>{{ number_format($porcentaje, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $barraClass }}"
                                            style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            @endif

                            @if ($servicio->description)
                                <div class="text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Descripción:</span>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $servicio->description }}</p>
                                </div>
                            @endif

                            @if ($servicio->mobile_phone)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Teléfono contacto:</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $servicio->mobile_phone }}</span>
                                </div>
                            @endif

                            @if ($servicio->email)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Email contacto:</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $servicio->email }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Estado de notificación -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-2">
                                @if ($servicio->event_sent)
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-green-600 dark:text-green-400">Notificación enviada</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                    </svg>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sin notificar</span>
                                @endif
                            </div>

                            @if ($estaVencido || $proximoVencer)
                                <div
                                    class="flex items-center text-xs {{ $estaVencido ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $estaVencido ? 'Requiere renovación' : 'Requiere atención' }}
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($servicios->hasPages())
                <div class="mt-8">
                    <div class="flex justify-center">
                        {{ $servicios->links('taxis::components.pagination') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if (request()->hasAny(['estado', 'tipo_servicio', 'vehiculo_id', 'search']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron servicios
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay servicios que coincidan con los filtros aplicados. Intenta ajustar los criterios de
                            búsqueda.
                        </p>
                        <a href="{{ route('taxis.propietario.servicios') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 dark:bg-blue-400/10 dark:text-blue-400 dark:hover:bg-blue-400/20">
                            Ver todos los servicios
                        </a>
                    @else
                        <!-- Default empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No hay servicios registrados
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Aún no tienes servicios registrados para tus vehículos. Los servicios te ayudan a gestionar los
                            vencimientos y mantenimientos.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection

@push('styles')
    <style>
        .form-input,
        .form-select {
            @apply block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }

        .btn {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
    </style>
@endpush
