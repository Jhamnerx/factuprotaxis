@extends('taxis::layouts.master')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Bienvenido, {{ $user->name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Panel de Control - Conductor
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Información Personal -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Mi Información
                </h2>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Nombre:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $user->name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">DNI:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $user->number }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Email:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Teléfono 1:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $user->telephone_1 }}</span>
                    </div>
                    @if ($user->telephone_2)
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Teléfono 2:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $user->telephone_2 }}</span>
                        </div>
                    @endif
                    @if ($user->telephone_3)
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Teléfono 3:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $user->telephone_3 }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Dirección:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $user->address }}</span>
                    </div>
                    @if ($user->fecha_nacimiento)
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Fecha de Nacimiento:</span>
                            <span
                                class="text-gray-900 dark:text-white ml-2">{{ $user->fecha_nacimiento->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información de Licencia -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Mi Licencia de Conducir
                </h2>
                @if ($user->licencia)
                    <div class="space-y-3">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Número:</span>
                            <span
                                class="text-gray-900 dark:text-white ml-2">{{ $user->licencia['numero'] ?? 'No especificado' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Categoría:</span>
                            <span
                                class="text-gray-900 dark:text-white ml-2">{{ $user->licencia['categoria'] ?? 'No especificada' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Estado:</span>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2 
                            {{ ($user->licencia['estado'] ?? '') == 'VIGENTE' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->licencia['estado'] ?? 'No especificado' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Fecha de Expedición:</span>
                            <span
                                class="text-gray-900 dark:text-white ml-2">{{ $user->licencia['fecha_expedicion'] ?? 'No especificada' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Fecha de Vencimiento:</span>
                            <span
                                class="text-gray-900 dark:text-white ml-2">{{ $user->licencia['fecha_vencimiento'] ?? 'No especificada' }}</span>
                        </div>
                        @if (isset($user->licencia['restricciones']) && $user->licencia['restricciones'])
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Restricciones:</span>
                                <span
                                    class="text-gray-900 dark:text-white ml-2">{{ $user->licencia['restricciones'] }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No hay información de licencia registrada.</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Acciones Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Acciones Rápidas
                </h2>
                <div class="space-y-3">
                    <a href="#"
                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Ver mi Vehículo Asignado
                    </a>
                    <a href="#"
                        class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Ver Horarios
                    </a>
                    <a href="#"
                        class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Reportar Incidencia
                    </a>
                    <a href="#"
                        class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Ver mis Ganancias
                    </a>
                </div>
            </div>

            <!-- Estado de Licencia -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Estado de Habilitación
                </h2>
                <div class="text-center">
                    @if ($user->hasValidLicense())
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <svg class="w-12 h-12 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold">Licencia Vigente</h3>
                            <p class="text-sm">Habilitado para conducir</p>
                        </div>
                    @else
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <svg class="w-12 h-12 mx-auto mb-2 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold">Licencia No Vigente</h3>
                            <p class="text-sm">Contacte con el administrador</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón de Logout -->
        <div class="mt-8 text-center">
            <form method="POST" action="{{ route('taxis.logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md transition duration-150">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
@endsection
