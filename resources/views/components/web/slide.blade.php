<section class="slider_section">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6">
                <div class="box">
                    <div class="detail-box">
                        <h4>
                            Bienvenido a
                        </h4>
                        <h1>
                            {{ $company->name }}
                        </h1>
                        <p class="description">
                            @if ($web_page->title_services)
                                {{ $web_page->title_services }}
                            @else
                                Sistema integral de gestión para empresas de taxis. Administra vehículos, conductores,
                                propietarios y facturación de manera eficiente y profesional.
                            @endif
                        </p>
                    </div>

                    @if (isset($web_page) && $web_page->services && count($web_page->services) > 0)
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($web_page->services as $index => $service)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}"
                                        class="{{ $index === 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($web_page->services as $index => $service)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="img-box">
                                            <img src="{{ $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png') }}"
                                                alt="{{ $service['name'] }}">
                                        </div>
                                        <div class="service-info">
                                            <h5>{{ $service['name'] }}</h5>
                                            <p>{{ $service['description'] ?? 'Servicio profesional de calidad' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="default-services">
                            <div class="service-highlight">
                                <div class="icon-box">
                                    <i class="fas fa-taxi"></i>
                                </div>
                                <h5>Gestión de Flota</h5>
                                <p>Control completo de vehículos y operaciones</p>
                            </div>
                        </div>
                    @endif

                    <div class="btn-box">
                        <a href="{{ route('tenant.web.servicios') }}" class="btn-1">
                            <i class="fas fa-arrow-right me-2"></i>
                            Conocer Servicios
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="slider_form">
                    {{-- <div class="form-header">
                        <i class="fas fa-shield-alt"></i>
                        <h4>Acceso al Sistema</h4>
                        <p>Plataforma de gestión empresarial</p>
                    </div> --}}
                    <div class="system-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Plataforma segura y confiable</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Reportes en tiempo real</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-mobile-alt"></i>
                                <span>Acceso multiplataforma</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Soporte 24/7</span>
                            </div>
                        </div>
                    </div>
                    <div class="btn-login-box" style="margin-top: 15px; text-align: center;">
                        <a href="{{ route('taxis.login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Ingresar al Sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
</section>
