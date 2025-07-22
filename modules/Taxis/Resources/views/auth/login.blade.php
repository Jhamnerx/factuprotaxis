@extends('taxis::layouts.auth')

@section('content')
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            Iniciar Sesión
        </h2>
        <p class="mt-1 text-gray-600 dark:text-gray-400">
            Acceda a su cuenta de {{ request()->get('type', 'propietario/conductor') }}
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <form method="POST" action="{{ route('taxis.login.post') }}" class="space-y-6">
        @csrf

        <!-- Tipo de Usuario -->
        <div>
            <label for="user_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipo de Usuario
            </label>
            <select name="user_type" id="user_type" required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @if ($errors->has('user_type')) border-red-500 @endif">
                <option value="">Seleccione...</option>
                <option value="propietario" {{ old('user_type') == 'propietario' ? 'selected' : '' }}>
                    Propietario
                </option>
                <option value="conductor" {{ old('user_type') == 'conductor' ? 'selected' : '' }}>
                    Conductor
                </option>
            </select>
            @if ($errors->has('user_type'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('user_type') }}</p>
            @endif
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Correo Electrónico
            </label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                placeholder="Ingrese su correo electrónico"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @if ($errors->has('email')) border-red-500 @endif">
            @if ($errors->has('email'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Contraseña
            </label>
            <input type="password" name="password" id="password" required autocomplete="current-password"
                placeholder="Ingrese su contraseña"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @if ($errors->has('password')) border-red-500 @endif">
            @if ($errors->has('password'))
                <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                Recordarme
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" id="loginBtn"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-105">
                <svg class="w-5 h-5 mr-2 hidden" id="loadingIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <svg class="w-5 h-5 mr-2" id="loginIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                <span id="loginText">Iniciar Sesión</span>
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            ¿No tienes credenciales?
            <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">
                Contacta con el administrador
            </a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.getElementById('user_type');
            const emailInput = document.getElementById('email');
            const loginForm = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');
            const loginIcon = document.getElementById('loginIcon');
            const loadingIcon = document.getElementById('loadingIcon');
            const loginText = document.getElementById('loginText');

            // Dynamic placeholder based on user type
            userTypeSelect.addEventListener('change', function() {
                const userType = this.value;
                if (userType === 'propietario') {
                    emailInput.placeholder = 'Ingrese el correo del propietario';
                } else if (userType === 'conductor') {
                    emailInput.placeholder = 'Ingrese el correo del conductor';
                } else {
                    emailInput.placeholder = 'Ingrese su correo electrónico';
                }
            });

            // Form submission with loading state
            loginForm.addEventListener('submit', function() {
                loginBtn.disabled = true;
                loginIcon.classList.add('hidden');
                loadingIcon.classList.remove('hidden');
                loadingIcon.classList.add('animate-spin');
                loginText.textContent = 'Iniciando sesión...';
            });
        });
    </script>
@endsection
