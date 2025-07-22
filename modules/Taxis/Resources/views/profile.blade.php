@extends('taxis::layouts.master')

@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
                Mi Perfil
            </h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <p class="text-gray-600 dark:text-gray-400">
                    Aquí podrás editar tu información personal y preferencias.
                </p>

                <div class="mt-6">
                    <a href="{{ route('taxis.dashboard') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150">
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
