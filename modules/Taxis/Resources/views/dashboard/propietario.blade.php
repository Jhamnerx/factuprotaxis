@extends('taxis::layouts.master')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Bienvenido, {{ $user->name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Panel de Control - Propietario
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
                        <span class="font-medium text-gray-700 dark:text-gray-300">Documento:</span>
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
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Acciones Rápidas
                </h2>
                <div class="space-y-3">
                    <a href="#"
                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Ver mis Vehículos
                    </a>
                    <a href="#"
                        class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Ver Contratos
                    </a>
                    <a href="#"
                        class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Gestionar Conductores
                    </a>
                    <a href="#"
                        class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md text-center transition duration-150">
                        Ver Reportes
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                Resumen
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold">0</div>
                    <div class="text-sm opacity-90">Vehículos</div>
                </div>
                <div class="bg-green-500 text-white p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold">0</div>
                    <div class="text-sm opacity-90">Conductores</div>
                </div>
                <div class="bg-yellow-500 text-white p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold">0</div>
                    <div class="text-sm opacity-90">Contratos Activos</div>
                </div>
                <div class="bg-purple-500 text-white p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold">S/ 0.00</div>
                    <div class="text-sm opacity-90">Ingresos del Mes</div>
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
