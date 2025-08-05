@extends('taxis::layouts.master')

@section('title', 'Mis Permisos - Propietario')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Mis Permisos</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona los permisos de todos tus vehículos</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-400/10 dark:text-purple-400">
                        {{ $permisos->total() }} permiso{{ $permisos->total() != 1 ? 's' : '' }}
                        @if (request()->hasAny(['estado', 'tipo_permiso', 'vehiculo_id', 'search']))
                            filtrado{{ $permisos->total() != 1 ? 's' : '' }}
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
                <form method="GET" action="{{ route('taxis.propietario.permisos') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Motivo, tipo de permiso..." class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                            <select name="estado" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado
                                </option>
                                <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado
                                </option>
                                <option value="vencido" {{ request('estado') == 'vencido' ? 'selected' : '' }}>Vencido
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de
                                Permiso</label>
                            <input type="text" name="tipo_permiso" value="{{ request('tipo_permiso') }}"
                                placeholder="Tipo de permiso" class="form-input w-full">
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
                    </div>
                    <div class="flex items-center space-x-2 mt-4">
                        <button type="submit"
                            class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                            <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                                <path d="m11.7 4.3-1.4-1.4L6 7.2l-1.3-1.3-1.4 1.4 2.7 2.7z" />
                            </svg>
                            <span>Filtrar</span>
                        </button>
                        @if (request()->hasAny(['search', 'estado', 'tipo_permiso', 'vehiculo_id']))
                            <a href="{{ route('taxis.propietario.permisos') }}"
                                class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if (request()->hasAny(['estado', 'tipo_permiso', 'vehiculo_id', 'search']))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z">
                                </path>
                            </svg>
                            <span class="text-sm text-blue-700 dark:text-blue-300">
                                <span class="font-medium">Filtros activos:</span>
                                @if (request('search'))
                                    Búsqueda: "{{ request('search') }}"
                                @endif
                                @if (request('estado'))
                                    Estado: {{ ucfirst(request('estado')) }}
                                @endif
                                @if (request('tipo_permiso'))
                                    Tipo: "{{ request('tipo_permiso') }}"
                                @endif
                                @if (request('vehiculo_id'))
                                    @php $selectedVehiculo = $vehiculos->find(request('vehiculo_id')); @endphp
                                    @if ($selectedVehiculo)
                                        Vehículo: {{ $selectedVehiculo->placa }}
                                    @endif
                                @endif
                            </span>
                        </div>
                        <a href="{{ route('taxis.propietario.permisos') }}"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Permisos Grid -->
        @if ($permisos->count() > 0)

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach ($permisos as $permiso)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/60 hover:shadow-lg transition-shadow duration-200">

                        <!-- Permiso Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0V4a2 2 0 014 0v2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $permiso->tipo_permiso }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Permiso #{{ $permiso->id }}</p>
                                </div>
                            </div>
                            @php
                                $estadoClasses = [
                                    'pendiente' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
                                    'aprobado' =>
                                        'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400',
                                    'rechazado' => 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
                                    'vencido' => 'bg-gray-100 text-gray-800 dark:bg-gray-500/20 dark:text-gray-400',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $estadoClasses[$permiso->estado] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-500/20 dark:text-gray-400' }}">
                                {{ ucfirst($permiso->estado) }}
                            </span>
                        </div>

                        <!-- Vehicle Info -->
                        @if ($permiso->datosVehiculo)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Vehículo</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $permiso->datosVehiculo->placa }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $permiso->datosVehiculo->marca->nombre ?? 'N/A' }}
                                    {{ $permiso->datosVehiculo->modelo->nombre ?? '' }}
                                </div>
                            </div>
                        @endif

                        <!-- Permiso Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Vigencia:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}
                                </span>
                            </div>

                            @if ($permiso->motivo)
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Motivo:</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $permiso->motivo }}</p>
                                </div>
                            @endif

                            @if ($permiso->personas_autorizadas && count($permiso->personas_autorizadas) > 0)
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Personas autorizadas:</span>
                                    <div class="mt-2 space-y-1">
                                        @foreach ($permiso->personas_autorizadas as $persona)
                                            <div
                                                class="flex items-center text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600/50 rounded px-2 py-1">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $persona['nombre'] ?? 'N/A' }} - {{ $persona['documento'] ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div
                                class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 pt-2 border-t border-gray-200 dark:border-gray-600">
                                <span>Emitido:
                                    {{ \Carbon\Carbon::parse($permiso->created_at)->format('d/m/Y H:i') }}</span>
                                @if ($permiso->creator)
                                    <span>Por: {{ $permiso->creator->name ?? 'Sistema' }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('taxis.propietario.pdf.permiso', $permiso->id) }}" target="_blank"
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Descargar PDF
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($permisos->hasPages())
                <div class="mt-8">
                    <div class="flex justify-center">
                        {{ $permisos->links('taxis::components.pagination') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if (request()->hasAny(['estado', 'tipo_permiso', 'vehiculo_id', 'search']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron permisos
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay permisos que coincidan con los filtros seleccionados.
                        </p>
                        <a href="{{ route('taxis.propietario.permisos') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Limpiar filtros
                        </a>
                    @else
                        <!-- Default empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0V4a2 2 0 014 0v2">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No hay permisos registrados
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Aún no tienes permisos registrados para tus vehículos.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
