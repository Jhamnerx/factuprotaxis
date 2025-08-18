<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Module Taxis</title>

    {{-- Laravel Mix - CSS File --}}
    <link rel="stylesheet" href="{{ asset('tenant-taxis/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('tenant-taxis/css/vendors/flatpickr.min.css') }}">
    <script>
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>
</head>

<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400"
    :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <div class="flex h-[100dvh] overflow-hidden">

        @include('taxis::components.sidebar')
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            @include('taxis::components.header')

            <!-- Alerts -->
            @if (session('success'))
                <div class="mx-4 mt-4 mb-2">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between"
                        x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false"
                            class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mx-4 mt-4 mb-2">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between"
                        x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-red-600 dark:text-red-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false"
                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mx-4 mt-4 mb-2">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between"
                        x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-yellow-600 dark:text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="font-medium">{{ session('warning') }}</span>
                        </div>
                        <button @click="show = false"
                            class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="mx-4 mt-4 mb-2">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between"
                        x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ session('info') }}</span>
                        </div>
                        <button @click="show = false"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <main class="grow">
                @yield('content')
            </main>
        </div>

    </div>


    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <script src="{{ asset('tenant-taxis/js/main.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/chart.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/moment.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/chartjs-adapter-moment.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/dashboard-charts.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/flatpickr.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/flatpickr-init.js') }}"></script>
    @stack('scripts')
    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/taxis.js') }}"></script> --}}
</body>

</html>
