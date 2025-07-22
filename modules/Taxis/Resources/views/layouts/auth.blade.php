<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Taxis - Autenticación</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom styles for authentication --}}
    <style>
        .auth-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .auth-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .auth-card {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid rgba(75, 85, 99, 0.2);
        }

        .taxi-icon {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }
    </style>

    <script>
        // Dark mode initialization
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.querySelector('html').classList.add('dark');
        } else {
            document.querySelector('html').classList.remove('dark');
        }
    </script>
</head>

<body class="antialiased">
    <div class="auth-container min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto h-24 w-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="taxi-icon h-12 w-12 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M16 12h.01" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-bold text-white">Sistema de Taxis</h1>
                <p class="text-white opacity-80">Gestión de Propietarios y Conductores</p>
            </div>

            <div class="auth-card rounded-xl shadow-2xl p-8">
                @yield('content')
            </div>

            <div class="text-center">
                <p class="text-sm text-white opacity-80">
                    © {{ date('Y') }} Sistema de Taxis. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>

    {{-- Dark mode toggle button --}}
    <button onclick="toggleDarkMode()"
        class="fixed top-4 right-4 p-2 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-shadow duration-200"
        aria-label="Toggle dark mode">
        <svg class="h-5 w-5 text-gray-600 dark:text-gray-300 hidden dark:block" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg class="h-5 w-5 text-gray-600 dark:text-gray-300 block dark:hidden" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>

    <script>
        function toggleDarkMode() {
            const html = document.querySelector('html');
            const isDark = html.classList.contains('dark');

            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('dark-mode', 'false');
            } else {
                html.classList.add('dark');
                localStorage.setItem('dark-mode', 'true');
            }
        }

        // Check if user is authenticated and redirect
        document.addEventListener('DOMContentLoaded', function() {
            // This will be handled by Laravel's redirect logic after successful login
            @auth('propietarios')
                // Show a success message before redirect
                if (window.location.pathname.includes('login')) {
                    const successDiv = document.createElement('div');
                    successDiv.className =
                        'fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    successDiv.innerHTML = '✓ Acceso autorizado como Propietario. Redirigiendo...';
                    document.body.appendChild(successDiv);

                    setTimeout(() => {
                        window.location.href = "{{ route('taxis.dashboard') }}";
                    }, 1500);
                }
            @endauth

            @auth('conductores')
                // Show a success message before redirect
                if (window.location.pathname.includes('login')) {
                    const successDiv = document.createElement('div');
                    successDiv.className =
                        'fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    successDiv.innerHTML = '✓ Acceso autorizado como Conductor. Redirigiendo...';
                    document.body.appendChild(successDiv);

                    setTimeout(() => {
                        window.location.href = "{{ route('taxis.dashboard') }}";
                    }, 1500);
                }
            @endauth
        });
    </script>
</body>

</html>
