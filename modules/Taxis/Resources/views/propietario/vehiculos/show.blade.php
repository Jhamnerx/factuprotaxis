@extends('taxis::layouts.master')

@section('title', 'Detalle del Vehículo - Propietario')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Detalle del Vehículo</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $vehiculo->marca->nombre ?? 'Marca' }} {{ $vehiculo->modelo->nombre ?? 'Modelo' }} -
                    {{ $vehiculo->placa }}
                </p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back button -->
                <a href="{{ route('taxis.propietario.vehiculos') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" transform="rotate(180 8 8)" />
                    </svg>
                    <span class="max-xs:sr-only">Volver a Vehículos</span>
                </a>
            </div>

        </div>

        <!-- Vehicle Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/60">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Información Básica</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Marca y Modelo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Marca y Modelo
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->marca->nombre ?? 'Sin marca' }}
                                    {{ $vehiculo->modelo->nombre ?? 'Sin modelo' }}
                                </p>
                            </div>

                            <!-- Placa -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Placa
                                </label>
                                <p
                                    class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                    {{ $vehiculo->placa }}
                                </p>
                            </div>

                            <!-- Año -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Año
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->year ?? 'No especificado' }}
                                </p>
                            </div>

                            <!-- Color -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Color
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->color ?? 'No especificado' }}
                                </p>
                            </div>

                            <!-- Número Interno -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Número Interno
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->numero_interno ?? 'No asignado' }}
                                </p>
                            </div>

                            <!-- Flota -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Flota
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->flota ?? 'No asignada' }}
                                </p>
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Categoría
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->categoria ?? 'No especificada' }}
                                </p>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Estado
                                </label>
                                <div class="mt-1">
                                    @switch($vehiculo->estado)
                                        @case('ACTIVO')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-400/10 dark:text-emerald-400">
                                                Activo
                                            </span>
                                        @break

                                        @case('DE BAJA')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                                De Baja
                                            </span>
                                        @break

                                        @case('DE BAJA POR PAGO')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400">
                                                De Baja por Pago
                                            </span>
                                        @break

                                        @case('SUSPECION POR TRABAJO')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-400/10 dark:text-yellow-400">
                                                Suspención por Trabajo
                                            </span>
                                        @break

                                        @case('RETIRO')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-400/10 dark:text-orange-400">
                                                Retiro
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                                {{ ucfirst($vehiculo->estado) }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Specifications Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/60">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Especificaciones Técnicas</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Dimensiones -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Largo (m)
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->largo ? number_format($vehiculo->largo, 2) : 'No especificado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ancho (m)
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->ancho ? number_format($vehiculo->ancho, 2) : 'No especificado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Alto (m)
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->alto ? number_format($vehiculo->alto, 2) : 'No especificado' }}
                                </p>
                            </div>

                            <!-- Peso y Carga -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Peso (kg)
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->peso ? number_format($vehiculo->peso, 2) : 'No especificado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Carga Útil (kg)
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->carga_util ? number_format($vehiculo->carga_util, 2) : 'No especificado' }}
                                </p>
                            </div>

                            <!-- Otros -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Asientos
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->asientos ?? 'No especificado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ejes
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->ejes ?? 'No especificado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Número de Motor
                                </label>
                                <p
                                    class="text-xs text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                    {{ $vehiculo->numero_motor ?? 'No especificado' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    CCN
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->ccn ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Driver Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/60">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Conductor Asignado</h2>
                    </div>
                    <div class="p-6">
                        @if ($vehiculo->conductor)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-violet-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">
                                            {{ strtoupper(substr($vehiculo->conductor->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $vehiculo->conductor->name }}
                                    </p>
                                    @if ($vehiculo->conductor->number)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            DNI: {{ $vehiculo->conductor->number }}
                                        </p>
                                    @endif
                                    @if ($vehiculo->conductor->telephone_1)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            Tel: {{ $vehiculo->conductor->telephone_1 }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Sin conductor asignado
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/60">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Estado y Fechas</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($vehiculo->fecha_ingreso)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Fecha de Ingreso
                                </label>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $vehiculo->fecha_ingreso->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fecha de Registro
                            </label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $vehiculo->created_at ? $vehiculo->created_at->format('d/m/Y H:i') : 'No disponible' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Última Actualización
                            </label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $vehiculo->updated_at ? $vehiculo->updated_at->format('d/m/Y H:i') : 'No disponible' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/60">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Acciones Rápidas</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            Las acciones de gestión de vehículos están disponibles solo para el administrador del sistema.
                        </p>

                        <div class="text-center py-4">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Solo visualización
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
