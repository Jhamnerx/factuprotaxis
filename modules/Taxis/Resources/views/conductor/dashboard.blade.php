@extends('taxis::layouts.master')

@section('title', 'Dashboard - Conductor')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard del Conductor</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Bienvenido a tu panel de control</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $conductor['name'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Vehículo Asignado -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Vehículo
                            Asignado</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $stats['vehiculo_asignado'] ? 'Sí' : 'No' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 17l4 4 4-4m-4-5v9"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permisos Vigentes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Permisos
                            Vigentes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['permisos_vigentes'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagos Pendientes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Pagos
                            Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['pagos_pendientes'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servicios Programados -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Servicios
                            Programados</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $stats['servicios_programados'] }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Vehículo Asignado -->
        @if ($vehiculo)
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 17l4 4 4-4m-4-5v9"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Mi Vehículo Asignado
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Placa:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white font-semibold">{{ $vehiculo->placa }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Número
                                        Interno:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->numero_interno }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Marca:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->marca->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Modelo:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->modelo->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Año:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->anio ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Color:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->color ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vehiculo->estado == 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400' }}">
                                        {{ ucfirst($vehiculo->estado) }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Propietario:</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->propietario->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <a href="{{ route('taxis.conductor.vehiculo') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Ver Detalles Completos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-8 text-center">
                        <div class="mb-4">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No tienes un vehículo asignado
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            Contacta con el administrador para que te asigne un vehículo.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Accesos Rápidos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Accesos Rápidos</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('taxis.conductor.permisos') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-green-500 dark:hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mis Permisos</span>
                    </a>

                    <a href="{{ route('taxis.conductor.pagos') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-yellow-500 dark:hover:border-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800 transition-colors">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mis Pagos</span>
                    </a>

                    <a href="{{ route('taxis.conductor.servicios') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mis Servicios</span>
                    </a>

                    <a href="{{ route('taxis.conductor.perfil') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-gray-500 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mi Perfil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Vehículo Asignado -->
    @if ($vehiculo)
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Mi Vehículo Asignado
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Placa:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white font-semibold">{{ $vehiculo->placa }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Número Interno:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->numero_interno }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Marca:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->marca->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Modelo:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->modelo->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Año:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->anio ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Color:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->color ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vehiculo->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($vehiculo->estado) }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Propietario:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $vehiculo->propietario->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('taxis.conductor.vehiculo') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ver Detalles Completos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-8 text-center">
                    <div class="mb-4">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No tienes un vehículo asignado</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Contacta con el administrador para que te asigne un vehículo.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Accesos Rápidos -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Accesos Rápidos</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('taxis.conductor.permisos') }}"
                    class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all duration-200">
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mis Permisos</span>
                </a>

                <a href="{{ route('taxis.conductor.pagos') }}"
                    class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition-all duration-200">
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mis Pagos</span>
                </a>

                <a href="{{ route('taxis.conductor.servicios') }}"
                    class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all duration-200">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mis Servicios</span>
                </a>

                <a href="{{ route('taxis.conductor.perfil') }}"
                    class="group flex flex-col items-center justify-center p-6 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-gray-500 hover:bg-gray-50 transition-all duration-200">
                    <div
                        class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:bg-gray-200 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Mi Perfil</span>
                </a>
            </div>
        </div>
    </div>
    </div>
@endsection
