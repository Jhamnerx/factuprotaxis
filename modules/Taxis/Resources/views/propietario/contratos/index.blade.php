@extends('taxis::layouts.master')

@section('title', 'Mis Contratos - Propietario')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Mis Contratos</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona los contratos de tus vehículos</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-400/10 dark:text-violet-400">
                        {{ $contratos->total() }} contrato{{ $contratos->total() != 1 ? 's' : '' }}
                        @if (request()->hasAny(['estado', 'vehiculo_id', 'search']))
                            filtrado{{ $contratos->total() != 1 ? 's' : '' }}
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
                <form method="GET" action="{{ route('taxis.propietario.contratos') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Placa o número interno..." class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                            <select name="estado" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>
                                    Finalizado</option>
                                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehículo</label>
                            <select name="vehiculo_id" class="form-select w-full" onchange="this.form.submit()">
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
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white flex-1">
                                Buscar
                            </button>
                            @if (request()->hasAny(['estado', 'vehiculo_id', 'search']))
                                <a href="{{ route('taxis.propietario.contratos') }}"
                                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if (request()->hasAny(['estado', 'vehiculo_id', 'search']))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                            </svg>
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-100">Filtros aplicados:</span>
                            <div class="flex items-center space-x-2">
                                @if (request('search'))
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                        Búsqueda: "{{ request('search') }}"
                                    </span>
                                @endif
                                @if (request('estado'))
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                        Estado: {{ ucfirst(request('estado')) }}
                                    </span>
                                @endif
                                @if (request('vehiculo_id') && isset($vehiculos))
                                    @php
                                        $vehiculoSeleccionado = $vehiculos->find(request('vehiculo_id'));
                                    @endphp
                                    @if ($vehiculoSeleccionado)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                            Vehículo: {{ $vehiculoSeleccionado->placa }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('taxis.propietario.contratos') }}"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contratos Grid -->
        @if ($contratos->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach ($contratos as $contrato)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/60 hover:shadow-lg transition-shadow duration-200">

                        <!-- Contract Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    Contrato #{{ $contrato->id }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Creado: {{ $contrato->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @switch($contrato->estado)
                                    @case('activo')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-400/10 dark:text-emerald-400">
                                            Activo
                                        </span>
                                    @break

                                    @case('finalizado')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                            Finalizado
                                        </span>
                                    @break

                                    @case('cancelado')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400">
                                            Cancelado
                                        </span>
                                    @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                            {{ ucfirst($contrato->estado) }}
                                        </span>
                                @endswitch
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        @if ($contrato->vehiculo)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-3">Información del
                                    Vehículo</h4>

                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Placa:</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-100 ml-1 font-medium">{{ $contrato->vehiculo['placa'] }}</span>
                                    </div>

                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Marca:</span>
                                        <span class="text-gray-800 dark:text-gray-100 ml-1">
                                            {{ $contrato->vehiculo['marca']['nombre'] }}
                                        </span>
                                    </div>

                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Modelo:</span>
                                        <span class="text-gray-800 dark:text-gray-100 ml-1">
                                            {{ $contrato->vehiculo['modelo']['nombre'] ?? 'N/A' }}
                                        </span>
                                    </div>

                                    @if ($contrato->vehiculo['numero_interno'])
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            <span class="text-gray-600 dark:text-gray-400">N° Interno:</span>
                                            <span class="text-gray-800 dark:text-gray-100 ml-1">
                                                {{ $contrato->vehiculo['numero_interno'] ?? 'N/A' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Contract Details -->
                        <div class="space-y-3 mb-6">
                            @if ($contrato->fecha_inicio)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">Fecha Inicio:</span>
                                    <span class="text-gray-800 dark:text-gray-100 ml-1">
                                        {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif

                            @if ($contrato->fecha_fin)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">Fecha Fin:</span>
                                    <span class="text-gray-800 dark:text-gray-100 ml-1">
                                        {{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif

                            @if ($contrato->monto_tributo)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">Monto Tributo:</span>
                                    <span class="text-gray-800 dark:text-gray-100 ml-1 font-medium">
                                        S/ {{ number_format($contrato->monto_tributo, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            @if ($contrato->vehiculo)
                                <a href="{{ route('taxis.propietario.pdf.contrato', $contrato) }}" target="_blank"
                                    class="flex-1 btn-sm bg-violet-500 hover:bg-violet-600 text-white text-center">
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Descargar PDF
                                </a>
                            @endif
                            <button
                                class="btn-sm border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($contratos->hasPages())
                <div class="mt-8">
                    <div class="flex justify-center">
                        {{ $contratos->links('taxis::components.pagination') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if (request()->hasAny(['estado', 'vehiculo_id', 'search']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron contratos
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay contratos que coincidan con los filtros aplicados. Intenta ajustar los criterios de
                            búsqueda.
                        </p>
                        <a href="{{ route('taxis.propietario.contratos') }}"
                            class="inline-flex items-center px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar filtros
                        </a>
                    @else
                        <!-- Default empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No tienes contratos registrados
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Actualmente no tienes contratos para tus vehículos. Los contratos son gestionados por el
                            administrador del sistema.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
