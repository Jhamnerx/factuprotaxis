@extends('taxis::layouts.master')

@section('title', 'Mis Conductores - Propietario')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Mis Conductores</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona los conductores de tus vehículos</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-400/10 dark:text-violet-400">
                        {{ $conductores->total() }} conductor{{ $conductores->total() != 1 ? 'es' : '' }}
                        @if (request()->hasAny(['estado', 'search']))
                            filtrado{{ $conductores->total() != 1 ? 's' : '' }}
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
                <form method="GET" action="{{ route('taxis.propietario.conductores') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nombre, teléfono o documento..." class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado del
                                Vehículo</label>
                            <select name="estado" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="ACTIVO" {{ request('estado') == 'ACTIVO' ? 'selected' : '' }}>Vehículo Activo
                                </option>
                                <option value="DE BAJA" {{ request('estado') == 'DE BAJA' ? 'selected' : '' }}>Vehículo de
                                    Baja</option>
                                <option value="DE BAJA POR PAGO"
                                    {{ request('estado') == 'DE BAJA POR PAGO' ? 'selected' : '' }}>Vehículo de Baja por
                                    Pago</option>
                                <option value="SUSPECION POR TRABAJO"
                                    {{ request('estado') == 'SUSPECION POR TRABAJO' ? 'selected' : '' }}>Vehículo Suspendido
                                </option>
                                <option value="RETIRO" {{ request('estado') == 'RETIRO' ? 'selected' : '' }}>Vehículo
                                    Retirado</option>
                                <option value="sin_vehiculo" {{ request('estado') == 'sin_vehiculo' ? 'selected' : '' }}>
                                    Sin Vehículo</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white flex-1">
                                Buscar
                            </button>
                            @if (request()->hasAny(['estado', 'search']))
                                <a href="{{ route('taxis.propietario.conductores') }}"
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
        @if (request()->hasAny(['estado', 'search']))
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
                                        Estado:
                                        {{ request('estado') == 'sin_vehiculo' ? 'Sin Vehículo' : (request('estado') == 'ACTIVO' ? 'Vehículo Activo' : (request('estado') == 'DE BAJA' ? 'Vehículo de Baja' : (request('estado') == 'DE BAJA POR PAGO' ? 'Vehículo de Baja por Pago' : (request('estado') == 'SUSPECION POR TRABAJO' ? 'Vehículo Suspendido' : 'Vehículo Retirado')))) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('taxis.propietario.conductores') }}"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Conductores Grid -->
        @if ($conductores->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($conductores as $conductor)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/60 hover:shadow-lg transition-shadow duration-200">

                        <!-- Conductor Header -->
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-violet-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">
                                        {{ strtoupper(substr($conductor->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 truncate">
                                    {{ $conductor->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $conductor->numero_documento ?? 'N/A' }}
                                </p>
                                @if ($conductor->telefono)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        📞 {{ $conductor->telefono }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Vehículo Info -->
                        @if ($conductor->vehiculo)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">Vehículo Asignado</h4>
                                    @switch($conductor->vehiculo->estado)
                                        @case('ACTIVO')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-400/10 dark:text-emerald-400">
                                                Activo
                                            </span>
                                        @break

                                        @case('DE BAJA')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                                De Baja
                                            </span>
                                        @break

                                        @case('DE BAJA POR PAGO')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400">
                                                De Baja por Pago
                                            </span>
                                        @break

                                        @case('SUSPECION POR TRABAJO')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-400/10 dark:text-yellow-400">
                                                Suspendido
                                            </span>
                                        @break

                                        @case('RETIRO')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-400/10 dark:text-orange-400">
                                                Retirado
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                                {{ ucfirst($conductor->vehiculo->estado) }}
                                            </span>
                                    @endswitch
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Placa:</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-100 ml-1 font-medium">{{ $conductor->vehiculo->placa }}</span>
                                    </div>

                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Marca:</span>
                                        <span class="text-gray-800 dark:text-gray-100 ml-1">
                                            {{ $conductor->vehiculo->marca->nombre ?? 'N/A' }}
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
                                            {{ $conductor->vehiculo->modelo->nombre ?? 'N/A' }}
                                        </span>
                                    </div>

                                    @if ($conductor->vehiculo->numero_interno)
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            <span class="text-gray-600 dark:text-gray-400">N° Interno:</span>
                                            <span class="text-gray-800 dark:text-gray-100 ml-1">
                                                {{ $conductor->vehiculo->numero_interno }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div
                                class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="text-sm font-medium text-amber-800 dark:text-amber-200">Sin vehículo
                                        asignado</span>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            @if ($conductor->vehiculo)
                                <a href="{{ route('taxis.propietario.vehiculos.show', $conductor->vehiculo->id) }}"
                                    class="flex-1 btn-sm bg-violet-500 hover:bg-violet-600 text-white text-center">
                                    Ver Vehículo
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
            @if ($conductores->hasPages())
                <div class="mt-8">
                    <div class="flex justify-center">
                        {{ $conductores->links('taxis::components.pagination') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if (request()->hasAny(['estado', 'search']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron conductores
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay conductores que coincidan con los filtros aplicados. Intenta ajustar los criterios de
                            búsqueda.
                        </p>
                        <a href="{{ route('taxis.propietario.conductores') }}"
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
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No tienes conductores registrados
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Actualmente no tienes conductores asignados a tus vehículos. Los conductores son gestionados por
                            el administrador del sistema.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
