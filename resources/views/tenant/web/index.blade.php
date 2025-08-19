@extends('tenant.layouts.guest')

@section('contenido')

    <!-- service section -->
    <section class="service_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    Nuestros<br>
                    Servicios
                </h2>
                <p class="section-subtitle">
                    @if ($web_page->title_services)
                        {{ $web_page->title_services }}
                    @else
                        Soluciones integrales para la gestión de empresas de transporte y servicios de taxi
                    @endif

                </p>
            </div>
            <div class="service_container">
                @if (isset($web_page) && $web_page->services && count($web_page->services) > 0)
                    @foreach ($web_page->services as $service)
                        <div class="box">
                            <div class="img-box">
                                <img src="{{ $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png') }}"
                                    alt="{{ $service['name'] }}" class="service-image">
                            </div>
                            <div class="detail-box">
                                <h5>{{ $service['name'] }}</h5>
                                <p>{{ $service['description'] }}</p>
                                <div class="service-features">
                                    <span class="feature-badge">
                                        <i class="fas fa-check"></i> Profesional
                                    </span>
                                    <span class="feature-badge">
                                        <i class="fas fa-check"></i> Confiable
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="box">
                        <div class="img-box">
                            <img src="{{ asset('tenant/images/delivery-man.png') }}" alt="Gestión de Flota">
                        </div>
                        <div class="detail-box">
                            <h5>Gestión de Flota</h5>
                            <p>Control completo de vehículos, conductores y operaciones diarias de tu empresa de taxis.</p>
                            <div class="service-features">
                                <span class="feature-badge">
                                    <i class="fas fa-check"></i> Automatizado
                                </span>
                                <span class="feature-badge">
                                    <i class="fas fa-check"></i> Eficiente
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="img-box">
                            <img src="{{ asset('tenant/images/delivery-man.png') }}" alt="Facturación">
                        </div>
                        <div class="detail-box">
                            <h5>Sistema de Facturación</h5>
                            <p>Facturación electrónica completa, reportes financieros y control de pagos en tiempo real.</p>
                            <div class="service-features">
                                <span class="feature-badge">
                                    <i class="fas fa-check"></i> SUNAT
                                </span>
                                <span class="feature-badge">
                                    <i class="fas fa-check"></i> Reportes
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="img-box">
                            <img src="{{ asset('tenant/images/delivery-man.png') }}" alt="Gestión de Pagos">
                        </div>
                        <div class="detail-box">
                            <h5>Gestión de Pagos</h5>
                            <p>Administración de pagos de conductores, propietarios y control financiero integral.</p>
                            <div class="service-features">
                                <span class="feature-badge">
                                    <i class="fas fa-check"></i> Seguro
                                </span>
                                <span class="feature-badge">
                                    <i class="fas fa-check"></i> Trazable
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- end service section -->

    <!-- why section -->
    <section class="why_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    Por qué<br>
                    Elegirnos
                </h2>
                <p class="section-subtitle">
                    Somos la solución tecnológica que tu empresa de taxis necesita para crecer y modernizarse
                </p>
            </div>
            <div class="why_container">
                <div class="box">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/delivery-man-white.png') }}" alt="" class="img-1">
                        <img src="{{ asset('tenant/images/delivery-man-black.png') }}" alt="" class="img-2">
                    </div>
                    <div class="detail-box">
                        <h5>
                            {{ isset($web_page->why_choose[0]['title']) ? $web_page->why_choose[0]['title'] : 'Tecnología Avanzada' }}
                        </h5>
                        <p>
                            {{ isset($web_page->why_choose[0]['description']) ? $web_page->why_choose[0]['description'] : 'Utilizamos las últimas tecnologías para brindarte un sistema moderno, seguro y eficiente para la gestión de tu empresa de taxis.' }}
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/shield-white.png') }}" alt="" class="img-1">
                        <img src="{{ asset('tenant/images/shield-black.png') }}" alt="" class="img-2">
                    </div>
                    <div class="detail-box">
                        <h5>
                            {{ isset($web_page->why_choose[1]['title']) ? $web_page->why_choose[1]['title'] : 'Seguridad Garantizada' }}
                        </h5>
                        <p>
                            {{ isset($web_page->why_choose[1]['description']) ? $web_page->why_choose[1]['description'] : 'Protegemos tu información con los más altos estándares de seguridad. Todos tus datos están encriptados y respaldados.' }}
                        </p>

                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/repairing-service-white.png') }}" alt="" class="img-1">
                        <img src="{{ asset('tenant/images/repairing-service-black.png') }}" alt="" class="img-2">
                    </div>
                    <div class="detail-box">
                        <h5>
                            {{ isset($web_page->why_choose[2]['title']) ? $web_page->why_choose[2]['title'] : 'Soporte Especializado' }}
                        </h5>
                        <p>
                            {{ isset($web_page->why_choose[2]['description']) ? $web_page->why_choose[2]['description'] : 'Nuestro equipo de soporte está disponible para ayudarte en todo momento. Capacitación, mantenimiento y asistencia técnica.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end why section -->

    <!-- client section -->
    @if (isset($web_page) && count($web_page->client_says ?? []) > 0)
        <section class="client_section layout_padding-bottom">
            <div class="container">
                <div class="heading_container">
                    <h2>
                        Testimonios de<br>
                        Nuestros Clientes
                    </h2>
                    <p class="section-subtitle">
                        Conoce la experiencia de empresas que ya confían en nuestro servicios
                    </p>
                </div>
                <div class="client_container">
                    <div class="carousel-wrap">
                        <div class="owl-carousel">
                            @foreach ($web_page->client_says as $item)
                                <div class="item">
                                    <div class="box">
                                        <div class="client-header">
                                            <div class="img-box">
                                                <img src="{{ $item['image'] ? asset('storage/uploads/client/' . $item['image']) : asset('tenant/images/client-1.png') }}"
                                                    alt="{{ $item['name'] }}">
                                            </div>
                                            <div class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                        <div class="detail-box">
                                            <h3>{{ $item['name'] }}</h3>
                                            <p class="testimonial-text">
                                                "{{ $item['text'] }}"
                                            </p>
                                            <img src="{{ asset('tenant/images/quote.png') }}" alt=""
                                                class="quote-icon">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- end client section -->


@stop
