@extends('taxis::layouts.master')

@section('title', 'Mi Vehículo')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mi Vehículo</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Información detallada de tu vehículo asignado
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('taxis.conductor.dashboard') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Información Principal del Vehículo -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Información General
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Placa -->
                    <div class="flex flex-col">
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Placa</span>
                        <span class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $vehiculo->placa }}</span>
                    </div>

                    <!-- Número Interno -->
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">N°
                            Interno</span>
                        <span
                            class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ $vehiculo->numero_interno }}</span>
                    </div>

                    <!-- Estado -->
                    <div class="flex flex-col">
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Estado</span>
                        <span class="mt-1">
                            @switch($vehiculo->estado)
                                @case('ACTIVO')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $vehiculo->estado }}
                                    </span>
                                @break

                                @case('DE BAJA')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $vehiculo->estado }}
                                    </span>
                                @break

                                @default
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        {{ $vehiculo->estado }}
                                    </span>
                            @endswitch
                        </span>
                    </div>

                    <!-- Marca -->
                    @if ($vehiculo->marca)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Marca</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->marca->nombre }}</span>
                        </div>
                    @endif

                    <!-- Modelo -->
                    @if ($vehiculo->modelo)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Modelo</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->modelo->nombre }}</span>
                        </div>
                    @endif

                    <!-- Año -->
                    @if ($vehiculo->year)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Año</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->year }}</span>
                        </div>
                    @endif

                    <!-- Color -->
                    @if ($vehiculo->color)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Color</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->color }}</span>
                        </div>
                    @endif

                    <!-- Categoría -->
                    @if ($vehiculo->categoria)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Categoría</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->categoria }}</span>
                        </div>
                    @endif

                    <!-- Asientos -->
                    @if ($vehiculo->asientos)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Asientos</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->asientos }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información del Propietario -->
        @if ($vehiculo->propietario)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Información del Propietario
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nombre</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->propietario->name }}</span>
                        </div>

                        <!-- Documento -->
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Documento</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->propietario->number }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Detalles Técnicos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Detalles Técnicos
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Número Motor -->
                    @if ($vehiculo->numero_motor)
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">N°
                                Motor</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->numero_motor }}</span>
                        </div>
                    @endif

                    <!-- CCN -->
                    @if ($vehiculo->ccn)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">CCN</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->ccn }}</span>
                        </div>
                    @endif

                    <!-- Ejes -->
                    @if ($vehiculo->ejes)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Ejes</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->ejes }}</span>
                        </div>
                    @endif

                    <!-- Peso -->
                    @if ($vehiculo->peso)
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Peso
                                (kg)</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ number_format($vehiculo->peso, 2) }}</span>
                        </div>
                    @endif

                    <!-- Carga Útil -->
                    @if ($vehiculo->carga_util)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Carga
                                Útil (kg)</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ number_format($vehiculo->carga_util, 2) }}</span>
                        </div>
                    @endif

                    <!-- Fecha Ingreso -->
                    @if ($vehiculo->fecha_ingreso)
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Fecha
                                Ingreso</span>
                            <span
                                class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $vehiculo->fecha_ingreso->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Acciones Relacionadas</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Ver Permisos -->
                    <a href="{{ route('taxis.conductor.permisos') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-green-500 dark:hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Ver Permisos</span>
                    </a>

                    <!-- Ver Pagos -->
                    <a href="{{ route('taxis.conductor.pagos') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-yellow-500 dark:hover:border-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800 transition-colors">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Ver Pagos</span>
                    </a>

                    <!-- Ver Servicios -->
                    <a href="{{ route('taxis.conductor.servicios') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Ver Servicios</span>
                    </a>

                    <!-- Mi Perfil -->
                    <a href="{{ route('taxis.conductor.perfil') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-gray-500 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-gray-100 dark:bg-gray-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-gray-200 dark:group-hover:bg-gray-800 transition-colors">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mi Perfil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
