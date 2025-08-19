@extends('tenant.layouts.guest')

@section('contenido')
    <!-- Hero Section -->
    <section class="services-hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            Nuestros <span class="text-highlight">Servicios</span>
                        </h1>
                        <p class="hero-subtitle">
                            Descubre todas las soluciones que ofrecemos para optimizar tu empresa
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hero-icon">
                        <i class="fas fa-taxi"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="service_section layout_padding">
        <div class="container">
            <div class="heading_container text-center">
                <span class="section-badge">
                    <i class="fas fa-cogs"></i> Servicios Profesionales
                </span>
                <h2>
                    {{ $web_page->title_services ?? 'Soluciones Integrales para tu Empresa' }}
                </h2>
                <p class="section-description">
                    Ofrecemos un conjunto completo de herramientas tecnológicas diseñadas específicamente para empresas
                </p>
            </div>

            <div class="services-grid">
                @if (isset($web_page) && $web_page->services && count($web_page->services) > 0)
                    @foreach ($web_page->services as $index => $service)
                        <div class="service-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="service-image">
                                <img src="{{ $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png') }}"
                                    alt="{{ $service['name'] }}" class="service-img">
                                <div class="image-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <h4 class="service-title">{{ $service['name'] }}</h4>
                                    <div class="service-number">{{ sprintf('%02d', $index + 1) }}</div>
                                </div>
                                <p class="service-description">{{ $service['description'] }}</p>
                                <div class="service-features">
                                    <span class="feature-tag">
                                        <i class="fas fa-check"></i> Profesional
                                    </span>
                                    <span class="feature-tag">
                                        <i class="fas fa-shield-alt"></i> Seguro
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Servicios por defecto -->
                    <div class="service-card">
                        <div class="service-image">
                            <img src="{{ asset('tenant/images/delivery-man.png') }}" alt="Gestión de Flota"
                                class="service-img">
                            <div class="image-overlay">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <div class="service-content">
                            <div class="service-header">
                                <h4 class="service-title">Gestión de Flota</h4>
                                <div class="service-number">01</div>
                            </div>
                            <p class="service-description">Control completo de vehículos, conductores y operaciones diarias.
                            </p>
                            <div class="service-features">
                                <span class="feature-tag">
                                    <i class="fas fa-check"></i> Automatizado
                                </span>
                                <span class="feature-tag">
                                    <i class="fas fa-shield-alt"></i> Eficiente
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Más servicios por defecto... -->
                @endif
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="services-cta-section">
        <div class="container">
            <div class="cta-content text-center">
                <h3>¿Necesitas una solución personalizada?</h3>
                <p>Contáctanos para conocer cómo podemos adaptar nuestros servicios a las necesidades específicas de tu
                    empresa</p>
                <div class="cta-buttons">
                    <a href="{{ route('login') }}" class="cta-btn primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Acceder al Sistema
                    </a>
                    <a href="#" class="cta-btn secondary">
                        <i class="fas fa-phone"></i>
                        Solicitar Información
                    </a>
                </div>
            </div>
        </div>
    </section>

@stop
