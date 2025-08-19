<section class="info_section layout_padding-top layout_padding2-bottom">
    <div class="container">
        <div class="info-content">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="info-block">
                        <div class="info-header">
                            <i class="fas fa-sitemap"></i>
                            <h5>Navegación</h5>
                        </div>
                        <div class="info_links">
                            <ul>
                                <li class="{{ request()->routeIs('tenant.web.home') ? 'active' : '' }}">
                                    <a href="{{ route('tenant.web.home') }}">
                                        <i class="fas fa-home"></i>
                                        <span>Inicio</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('tenant.web.nosotros') ? 'active' : '' }}">
                                    <a href="{{ route('tenant.web.nosotros') }}">
                                        <i class="fas fa-users"></i>
                                        <span>Nosotros</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('tenant.web.servicios') ? 'active' : '' }}">
                                    <a href="{{ route('tenant.web.servicios') }}">
                                        <i class="fas fa-cogs"></i>
                                        <span>Servicios</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('tenant.web.contacto') ? 'active' : '' }}">
                                    <a href="{{ route('tenant.web.contacto') }}">
                                        <i class="fas fa-envelope"></i>
                                        <span>Contacto</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('login') }}" class="login-link">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Acceder al Sistema</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="info-block">
                        <div class="info-header">
                            <i class="fas fa-taxi"></i>
                            <h5>Módulos del Sistema</h5>
                        </div>
                        <div class="system-features">
                            <div class="feature-item">
                                <i class="fas fa-car"></i>
                                <span>Gestión de Unidades y Flota</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-users"></i>
                                <span>Control de Conductores</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-user-tie"></i>
                                <span>Registro de Propietarios</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Calendario de Pagos</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-file-contract"></i>
                                <span>Contratos y Permisos</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Solicitudes y Constancias</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="info-block">
                        <div class="info-header">
                            <i class="fas fa-tools"></i>
                            <h5>Funcionalidades</h5>
                        </div>
                        @if ($login->show_socials && ($login->facebook || $login->twitter || $login->linkedin || $login->instagram))
                            <div class="info_social">
                                @if ($login->facebook)
                                    <a href="{{ $login->facebook }}" class="social-link facebook" target="_blank"
                                        rel="noopener">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                @endif
                                @if ($login->twitter)
                                    <a href="{{ $login->twitter }}" class="social-link twitter" target="_blank"
                                        rel="noopener">
                                        <i class="fab fa-twitter"></i>
                                        <span>Twitter</span>
                                    </a>
                                @endif
                                @if ($login->linkedin)
                                    <a href="{{ $login->linkedin }}" class="social-link linkedin" target="_blank"
                                        rel="noopener">
                                        <i class="fab fa-linkedin-in"></i>
                                        <span>LinkedIn</span>
                                    </a>
                                @endif
                                @if ($login->instagram)
                                    <a href="{{ $login->instagram }}" class="social-link instagram" target="_blank"
                                        rel="noopener">
                                        <i class="fab fa-instagram"></i>
                                        <span>Instagram</span>
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="taxi-features">
                                <div class="feature-item">
                                    <i class="fas fa-wrench"></i>
                                    <span>Servicios de Vehículos (SOAT)</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-bell"></i>
                                    <span>Notificaciones Yape</span>
                                </div>

                                <div class="feature-item">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Generación de PDF</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Reportes Especializados</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-headset"></i>
                                    <span>Soporte Técnico 24/7</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
