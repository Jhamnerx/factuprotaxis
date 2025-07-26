@extends('taxis::layouts.master')

@section('title', 'Mis Solicitudes - Propietario')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Mis Solicitudes</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona las solicitudes de tus vehículos</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-400/10 dark:text-violet-400">
                        {{ $solicitudes->total() }} solicitud{{ $solicitudes->total() != 1 ? 'es' : '' }}
                        @if (request()->hasAny(['estado', 'tipo', 'vehiculo_id', 'search']))
                            filtrada{{ $solicitudes->total() != 1 ? 's' : '' }}
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
                <form method="GET" action="{{ route('taxis.propietario.solicitudes') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Descripción, motivo, placa..."
                                class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                            <select name="estado" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="aceptada" {{ request('estado') == 'aceptada' ? 'selected' : '' }}>Aceptada
                                </option>
                                <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                            <select name="tipo" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los tipos</option>
                                <option value="registro" {{ request('tipo') == 'registro' ? 'selected' : '' }}>Registro de
                                    Unidad</option>
                                <option value="baja" {{ request('tipo') == 'baja' ? 'selected' : '' }}>Baja de Unidad
                                </option>
                                <option value="cambio_propietario"
                                    {{ request('tipo') == 'cambio_propietario' ? 'selected' : '' }}>Cambio de Propietario
                                </option>
                                <option value="emision" {{ request('tipo') == 'emision' ? 'selected' : '' }}>Emisión de
                                    Documentos</option>
                                <option value="correccion_datos"
                                    {{ request('tipo') == 'correccion_datos' ? 'selected' : '' }}>Corrección de Datos
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
                            <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                                <svg class="fill-current shrink-0 xs:hidden" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m12.7 8.7 3.6-3.6a1 1 0 0 0-1.4-1.4L11.3 7.3A7 7 0 1 0 12.7 8.7ZM7 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z" />
                                </svg>
                                <span class="max-xs:sr-only">Filtrar</span>
                            </button>
                            @if (request()->hasAny(['estado', 'tipo', 'vehiculo_id', 'search']))
                                <a href="{{ route('taxis.propietario.solicitudes') }}"
                                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                                    <span class="max-xs:sr-only">Limpiar</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if (request()->hasAny(['estado', 'tipo', 'vehiculo_id', 'search']))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Filtros activos</span>
                            @if (request('search'))
                                <span
                                    class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded">
                                    Búsqueda: "{{ request('search') }}"
                                </span>
                            @endif
                            @if (request('estado'))
                                <span
                                    class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded">
                                    Estado: {{ ucfirst(request('estado')) }}
                                </span>
                            @endif
                            @if (request('tipo'))
                                <span
                                    class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded">
                                    Tipo: {{ ucfirst(str_replace('_', ' ', request('tipo'))) }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('taxis.propietario.solicitudes') }}"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Solicitudes Grid -->
        @if ($solicitudes->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach ($solicitudes as $solicitud)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/60 hover:shadow-lg transition-shadow duration-200">

                        <!-- Solicitud Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Solicitud #{{ $solicitud->id }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @php
                                        $tipoTexto = [
                                            'registro' => 'Registro de Unidad',
                                            'baja' => 'Baja de Unidad',
                                            'cambio_propietario' => 'Cambio de Propietario',
                                            'emision' => 'Emisión de Documentos',
                                            'correccion_datos' => 'Corrección de Datos',
                                        ];
                                    @endphp
                                    {{ $tipoTexto[$solicitud->tipo] ?? $solicitud->tipo }}
                                </p>
                            </div>
                            <div>
                                @php
                                    if ($solicitud->estado == 'pendiente') {
                                        $estadoBadge =
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-400/10 dark:text-yellow-400';
                                    } elseif ($solicitud->estado == 'aceptada') {
                                        $estadoBadge =
                                            'bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400';
                                    } elseif ($solicitud->estado == 'rechazada') {
                                        $estadoBadge = 'bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400';
                                    } else {
                                        $estadoBadge =
                                            'bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoBadge }}">
                                    {{ ucfirst($solicitud->estado) }}
                                </span>
                            </div>
                        </div>

                        <!-- Solicitud Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0a4 4 0 100 8h8a4 4 0 100-8"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">{{ $solicitud->descripcion }}</span>
                            </div>

                            @if ($solicitud->motivo)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $solicitud->motivo }}</span>
                                </div>
                            @endif

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0a4 4 0 100 8h8a4 4 0 100-8"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">
                                    {{ $solicitud->detalle->count() }}
                                    vehículo{{ $solicitud->detalle->count() != 1 ? 's' : '' }}
                                </span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">
                                    {{ $solicitud->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>

                            @if ($solicitud->documentos_adjuntos && count($solicitud->documentos_adjuntos) > 0)
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        {{ count($solicitud->documentos_adjuntos) }}
                                        documento{{ count($solicitud->documentos_adjuntos) != 1 ? 's' : '' }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Vehículos relacionados -->
                        @if ($solicitud->detalle->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehículos:</h4>
                                <div class="space-y-1">
                                    @foreach ($solicitud->detalle->take(3) as $detalle)
                                        @if ($detalle->infoVehiculo)
                                            <div class="text-xs bg-gray-50 dark:bg-gray-700 rounded px-2 py-1">
                                                <span class="font-medium">{{ $detalle->infoVehiculo->placa }}</span>
                                                @if ($detalle->infoVehiculo->marca)
                                                    - {{ $detalle->infoVehiculo->marca->nombre }}
                                                @endif
                                                @if ($detalle->infoVehiculo->modelo)
                                                    {{ $detalle->infoVehiculo->modelo->nombre }}
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                    @if ($solicitud->detalle->count() > 3)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            y {{ $solicitud->detalle->count() - 3 }} más...
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('taxis.propietario.pdf.solicitud', $solicitud->id) }}"
                                class="flex-1 btn bg-violet-500 hover:bg-violet-600 text-white text-center text-sm">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Descargar PDF
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($solicitudes->hasPages())
                <div class="mt-8">
                    <div class="flex justify-center">
                        {{ $solicitudes->links('taxis::components.pagination') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if (request()->hasAny(['estado', 'tipo', 'vehiculo_id', 'search']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 48 48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 24c4.21 0 7.813 2.602 9.288 6.286">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron solicitudes
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay solicitudes que coincidan con los filtros aplicados.
                        </p>
                        <a href="{{ route('taxis.propietario.solicitudes') }}"
                            class="btn bg-violet-500 hover:bg-violet-600 text-white">
                            Ver todas las solicitudes
                        </a>
                    @else
                        <!-- Default empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 48 48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 24c4.21 0 7.813 2.602 9.288 6.286">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No tienes solicitudes
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Aún no hay solicitudes registradas para tus vehículos.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
