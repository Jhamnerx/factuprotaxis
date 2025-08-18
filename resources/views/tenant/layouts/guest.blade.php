<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('imagenes/favicon2023.png') }}">
    @stack('css')

    <!-- slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('tenant/css/bootstrap.css') }}" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('tenant/css/style.css') }}" rel="stylesheet" />
    <!-- responsive style -->
    <link href="{{ asset('tenant/css/responsive.css') }}" rel="stylesheet" />
    <!-- custom taxis styles -->
    <link href="{{ asset('tenant/css/custom-taxis.css') }}" rel="stylesheet" />
    @php
        use App\Models\System\Configuration as SystemConfiguration;
        use App\Models\Tenant\Company;
        use App\Models\Tenant\Configuration;

        $config = SystemConfiguration::first();
        if (!$config->use_login_global) {
            $config = Configuration::first();
        }
        $useLoginGlobal = $config->use_login_global;
        $login = $config->login;
        $company = Company::first();
    @endphp
</head>

<body class="{{ !request()->routeIs('tenant.web.home') ? 'sub_page' : '' }}">

    <div class="hero_area">
        <!-- header section strats -->
        @include('components.web.header')
        <!-- end header section -->

        <!-- slider section -->
        @if (request()->routeIs('tenant.web.home'))
            @include('components.web.slide', ['web_page' => $web_page ?? null])
        @endif
        <!-- end slider section -->
    </div>


    @yield('contenido')



    <!-- info section -->
    @include('components.web.info')
    <!-- end info section -->


    <!-- footer section -->

    @include('components.web.footer')
    <!-- footer section -->

    <script type="text/javascript" src="{{ asset('tenant/js/jquery-3.4.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tenant/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tenant/js/owl.carousel.min.js') }}"></script>


    <!-- owl carousel script -->
    <script type="text/javascript">
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 20,
            navText: [],
            autoplay: true,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1000: {
                    items: 2
                }
            }
        });
    </script>
    <!-- end owl carousel script -->

</body>

</html>
