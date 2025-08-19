@extends('tenant.layouts.guest')

@section('contenido')
    <!-- Hero Section -->
    <section class="about-hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            Acerca de <span class="company-highlight">{{ $company->trade_name ?? $company->name }}</span>
                        </h1>
                        <p class="hero-subtitle">
                            Conoce nuestra historia y compromiso
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hero-icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Main Section -->
    <section class="about_section layout_padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="about-content">
                        <div class="section-header">
                            <span class="section-badge">
                                <i class="fas fa-users"></i> {{ $web_page->about['subtitle'] ?? 'Nuestra Historia' }}
                            </span>
                            <h2 class="section-title">
                                {!! $web_page->about['title'] ?? 'Líderes en Gestión de <br><span class="text-primary">Empresas de Taxis</span>' !!}
                            </h2>
                        </div>

                        <div class="about-text">
                            <p class="main-description">
                                {{ $web_page->about['text'] ?? 'Somos una empresa comprometida con la innovación y la excelencia en el desarrollo de sistemas de gestión integral para empresas de transporte público.' }}
                            </p>
                        </div>

                        <div class="about-features">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Experiencia Comprobada</h5>
                                    <p>Años de experiencia desarrollando soluciones tecnológicas</p>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Seguridad Garantizada</h5>
                                    <p>Protección total de datos y transacciones seguras</p>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Soporte 24/7</h5>
                                    <p>Asistencia técnica disponible cuando la necesites</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="about-image-container">
                        <div class="image-wrapper">
                            <img src="{{ isset($web_page->about['image']) && $web_page->about['image'] ? asset('storage/uploads/about/' . $web_page->about['image']) : asset('tenant/images/about-img.png') }}"
                                alt="Acerca de {{ $company->trade_name ?? $company->name }}" class="about-main-image">
                            <div class="image-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-taxi"></i>
                                    <span>Sistema Integral</span>
                                </div>
                            </div>
                        </div>

                        <div class="floating-stats">
                            <div class="stat-card">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Satisfacción</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Soporte</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-badge">
                    <i class="fas fa-heart"></i> Nuestros Valores
                </span>
                <h2 class="section-title">
                    Lo que nos define como empresa
                </h2>
                <p class="section-description">
                    Principios que guían nuestro trabajo diario y compromiso con nuestros clientes
                </p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4>Innovación</h4>
                        <p>Utilizamos las últimas tecnologías para crear soluciones modernas y eficientes.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Confianza</h4>
                        <p>Construimos relaciones sólidas basadas en la transparencia y honestidad.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h4>Excelencia</h4>
                        <p>Buscamos la perfección en cada proyecto y superamos las expectativas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="cta-content">
                        <h3>¿Listo para modernizar tu empresa de taxis?</h3>
                        <p>Únete a las empresas que ya confían en nuestro sistema de gestión integral</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cta-actions">
                        <a href="{{ route('login') }}" class="cta-btn primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Acceder al Sistema
                        </a>
                        <a href="#" class="cta-btn secondary">
                            <i class="fas fa-phone"></i>
                            Contactar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
