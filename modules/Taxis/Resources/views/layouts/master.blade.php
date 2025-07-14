<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

            <main class="grow">
                @yield('content')
            </main>
        </div>

    </div>


    <script src="{{ asset('tenant-taxis/js/vendors/alpinejs.min.js') }}" defer></script>

    <script src="{{ asset('tenant-taxis/js/main.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/chart.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/moment.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/chartjs-adapter-moment.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/dashboard-charts.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/vendors/flatpickr.js') }}"></script>
    <script src="{{ asset('tenant-taxis/js/flatpickr-init.js') }}"></script>

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/taxis.js') }}"></script> --}}
</body>

</html>
