<header class="header_section">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
            <a class="navbar-brand" href="{{ route('tenant.web.home') }}">
                @if ($company->logo)
                    <img src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="{{ $company->name }}"
                        class="logo-light" style="{{ $company->logo_dark ? '' : '--show-light-logo: block;' }}" />
                @else
                    <img src="{{ asset('logo/tulogo.png') }}" alt="Logo" />
                @endif

                @if ($company->logo_dark)
                    <img src="{{ asset('storage/uploads/logos/' . $company->logo_dark) }}" alt="{{ $company->name }}"
                        class="logo-dark" />
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <i class="fas fa-bars"></i>
                </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="d-flex ml-auto flex-column flex-lg-row align-items-center">
                    <ul class="navbar-nav">
                        <li class="nav-item {{ request()->routeIs('tenant.web.home') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.web.home') }}">
                                <i class="fas fa-home me-1"></i>
                                Inicio
                                @if (request()->routeIs('tenant.web.home'))
                                    <span class="sr-only">(current)</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('tenant.web.nosotros') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.web.nosotros') }}">
                                <i class="fas fa-users me-1"></i>
                                Nosotros
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('tenant.web.servicios') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.web.servicios') }}">
                                <i class="fas fa-cogs me-1"></i>
                                Servicios
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('tenant.web.contacto') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.web.contacto') }}">
                                <i class="fas fa-envelope me-1"></i>
                                Contacto
                            </a>
                        </li>
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-menu" href="#" id="navbarUserDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="user-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
                                    <div class="dropdown-header">
                                        <strong>{{ Auth::user()->name }}</strong>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('tenant.dashboard.index') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Panel de Control
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user-edit me-2"></i>Mi Perfil
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item logout-item" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </a>
                                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link login-btn" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    Iniciar Sesión
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
