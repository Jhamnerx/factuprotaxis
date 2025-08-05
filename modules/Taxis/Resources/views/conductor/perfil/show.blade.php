@extends('taxis::layouts.master')

@section('title', 'Mi Perfil - Conductor')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mi Perfil</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Edita tu información personal como conductor</p>
                </div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('taxis.conductor.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Mi
                                    Perfil</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Summary Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-center">
                            <!-- Profile Image -->
                            <div class="mb-4">
                                <div
                                    class="mx-auto h-24 w-24 rounded-full bg-gradient-to-r from-blue-500 to-green-600 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Profile Info -->
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $conductor['name'] ?? 'Sin nombre' }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Conductor
                                </span>
                            </p>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ isset($conductor['licencia']) && !empty($conductor['licencia']) ? '1' : '0' }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Licencia</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $conductor['enabled'] ?? false ? 'Activo' : 'Inactivo' }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Estado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Display -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información Personal
                        </h3>
                    </div>

                    <div class="p-6">

                        <!-- Información Básica -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información Básica
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre Completo
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $conductor['name'] ?? 'Sin nombre' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Documento de Identidad
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $conductor['number'] ?? 'Sin documento' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha de Nacimiento
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span class="text-gray-900 dark:text-gray-100">
                                            @if (isset($conductor['fecha_nacimiento']) && $conductor['fecha_nacimiento'])
                                                {{ $conductor['fecha_nacimiento'] instanceof \Carbon\Carbon ? $conductor['fecha_nacimiento']->format('d/m/Y') : \Carbon\Carbon::parse($conductor['fecha_nacimiento'])->format('d/m/Y') }}
                                            @else
                                                Sin fecha
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                Información de Contacto
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono Principal
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $conductor['telephone_1'] ?? 'Sin teléfono' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono Secundario
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $conductor['telephone_2'] ?? 'Sin teléfono secundario' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Correo Electrónico
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $conductor['email'] ?? 'Sin correo' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Dirección
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg min-h-[76px]">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $conductor['address'] ?? 'Sin dirección' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Licencia -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Información de Licencia
                            </h4>
                            @php
                                $licenciaData = is_string($conductor['licencia'])
                                    ? json_decode($conductor['licencia'], true)
                                    : $conductor['licencia'];
                                $licenciaEstado = $licenciaData['estado'] ?? '';
                                $licenciaNumero = $licenciaData['numero'] ?? '';
                                $licenciaCategoria = $licenciaData['categoria'] ?? '';
                                $licenciaRestricciones = $licenciaData['restricciones'] ?? '';
                                $licenciaExpedicion = $licenciaData['fecha_expedicion'] ?? '';
                                $licenciaVencimiento = $licenciaData['fecha_vencimiento'] ?? '';
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Número de Licencia
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $licenciaNumero ?: 'Sin número de licencia' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estado de Licencia
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span class="text-gray-900 dark:text-gray-100">
                                            {{ $licenciaEstado ?: 'Sin estado' }}
                                            @if ($licenciaEstado)
                                                @if (strtoupper($licenciaEstado) === 'VIGENTE')
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Vigente
                                                    </span>
                                                @elseif(strtoupper($licenciaEstado) === 'VENCIDA')
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Vencida
                                                    </span>
                                                @elseif(strtoupper($licenciaEstado) === 'SUSPENDIDA')
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Suspendida
                                                    </span>
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Categoría
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ strtoupper($licenciaCategoria) ?: 'Sin categoría' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Restricciones
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $licenciaRestricciones ?: 'Sin restricciones' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha de Expedición
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span class="text-gray-900 dark:text-gray-100">
                                            @if ($licenciaExpedicion)
                                                @if ($licenciaExpedicion instanceof \Carbon\Carbon)
                                                    {{ $licenciaExpedicion->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($licenciaExpedicion)->format('d/m/Y') }}
                                                @endif
                                            @else
                                                Sin fecha de expedición
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha de Vencimiento
                                    </label>
                                    <div
                                        class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <span class="text-gray-900 dark:text-gray-100">
                                            @if ($licenciaVencimiento)
                                                @if ($licenciaVencimiento instanceof \Carbon\Carbon)
                                                    {{ $licenciaVencimiento->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($licenciaVencimiento)->format('d/m/Y') }}
                                                @endif
                                                @php
                                                    $fechaVencimiento =
                                                        $licenciaVencimiento instanceof \Carbon\Carbon
                                                            ? $licenciaVencimiento
                                                            : \Carbon\Carbon::parse($licenciaVencimiento);
                                                    $diasRestantes = now()->diffInDays($fechaVencimiento, false);
                                                @endphp
                                                @if ($diasRestantes < 0)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Vencida
                                                    </span>
                                                @elseif($diasRestantes <= 30)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Por vencer
                                                    </span>
                                                @else
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Vigente
                                                    </span>
                                                @endif
                                            @else
                                                Sin fecha de vencimiento
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Estilos para campos de solo lectura */
        .readonly-field {
            @apply px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg;
        }

        .readonly-field span {
            @apply text-gray-900 dark:text-gray-100;
        }
    </style>
@endpush
