@extends('taxis::layouts.master')

@section('title', 'Servicios de Mi Vehículo - Conductor')

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
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Servicios de Mi Vehículo</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona los servicios y vencimientos de tu vehículo</p>
                    @if (isset($vehiculo))
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                            Vehículo: {{ $vehiculo->placa }}
                        </span>
                    @endif
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400">
                        {{ $servicios->total() }} servicio{{ $servicios->total() != 1 ? 's' : '' }}
                        @if (request()->hasAny(['estado', 'tipo_servicio', 'search']))
                            filtrado{{ $servicios->total() != 1 ? 's' : '' }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back to Dashboard button -->
                <a href="{{ route('taxis.conductor.dashboard') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
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
                <form method="GET" action="{{ route('taxis.conductor.servicios') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                                        {{ request('tipo_servicio') == $tipoValue ? 'selected' : '' }}>
                                        {{ $tipoLabel }}
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
                                        d="m6.564 0.75 1.644 1.644L10.852 0L12 1.148L9.356 3.792l1.644 1.644-1.148 1.148L7.208 3.94 5.564 5.584 4.416 4.436z" />
                                </svg>
                                <span class="ml-1">Buscar</span>
                            </button>
                            @if (request()->hasAny(['estado', 'tipo_servicio', 'search']))
                                <a href="{{ route('taxis.conductor.servicios') }}"
                                    class="btn border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600">Limpiar</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if (request()->hasAny(['estado', 'tipo_servicio', 'search']))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="text-blue-800 dark:text-blue-200 font-medium">Filtros activos</span>
                        </div>
                        <a href="{{ route('taxis.conductor.servicios') }}"
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
                        $diasRestantes = null;
                        $estaVencido = false;
                        $proximoVencer = false;

                        if ($servicio->expires_date) {
                            $fechaVencimiento = \Carbon\Carbon::parse($servicio->expires_date);
                            $diasRestantes = $fechaVencimiento->diffInDays(now(), false);
                            $estaVencido = $fechaVencimiento->isPast();
                            $proximoVencer = !$estaVencido && $fechaVencimiento->isBefore(now()->addDays(30));
                        }

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
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoClass }}">
                                {{ $estadoTexto }}
                            </span>
                        </div>

                        <!-- Detalles del Servicio -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Fecha de vencimiento:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $servicio->expires_date ? \Carbon\Carbon::parse($servicio->expires_date)->format('d/m/Y') : 'No definida' }}
                                </span>
                            </div>

                            @if ($diasRestantes !== null)
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ $estaVencido ? 'Vencido hace:' : 'Días restantes:' }}
                                        </span>
                                        <span
                                            class="font-medium {{ $estaVencido ? 'text-red-600 dark:text-red-400' : ($proximoVencer ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                            {{ abs($diasRestantes) }} {{ abs($diasRestantes) == 1 ? 'día' : 'días' }}
                                        </span>
                                    </div>
                                    <!-- Barra de progreso -->
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="{{ $barraClass }} h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            @endif

                            @if ($servicio->description)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Descripción:</span>
                                    <span class="text-gray-900 dark:text-gray-100 text-right max-w-xs truncate">
                                        {{ $servicio->description }}
                                    </span>
                                </div>
                            @endif

                            @if ($servicio->mobile_phone)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Teléfono:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $servicio->mobile_phone }}</span>
                                </div>
                            @endif

                            @if ($servicio->email)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                    <span
                                        class="text-gray-900 dark:text-gray-100 text-right max-w-xs truncate">{{ $servicio->email }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Estado de notificación -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-2">
                                @if ($servicio->is_notified)
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-green-600 dark:text-green-400">Notificado</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sin notificar</span>
                                @endif
                            </div>

                            @if ($estaVencido || $proximoVencer)
                                <div class="flex items-center text-xs text-yellow-600 dark:text-yellow-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Requiere atención
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
                    @if (request()->hasAny(['estado', 'tipo_servicio', 'search']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron servicios
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay servicios que coincidan con los filtros aplicados. Intenta ajustar los criterios de
                            búsqueda.
                        </p>
                        <a href="{{ route('taxis.conductor.servicios') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 dark:bg-blue-400/10 dark:text-blue-400 dark:hover:bg-blue-400/20">
                            Ver todos los servicios
                        </a>
                    @else
                        <!-- Default empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No hay servicios registrados
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Aún no tienes servicios registrados para tu vehículo. Los servicios te ayudan a gestionar los
                            vencimientos y mantenimientos.
                        </p>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <p>Los servicios son gestionados por el propietario del vehículo.</p>
                        </div>
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
