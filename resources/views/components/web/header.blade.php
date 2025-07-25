<header class="header_section">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="{{ route('tenant.web.home') }}">
                <span>
                    {{ $company->name ?? 'FactuPro Taxis' }}
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="d-flex ml-auto flex-column flex-lg-row align-items-center">
                    <ul class="navbar-nav  ">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('tenant.web.home') }}">Home <span
                                    class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tenant.web.nosotros') }}"> Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tenant.web.servicios') }}"> Servicios </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tenant.web.contacto') }}">Contacto</a>
                        </li>
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarUserDropdown">
                                    <a class="dropdown-item" href="{{ route('tenant.dashboard.index') }}">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </a>
                                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
