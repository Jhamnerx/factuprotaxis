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
                    Soluciones integrales para la gestión de empresas de transporte y servicios de taxi
                </p>
            </div>
            <div class="service_container">
                @if (isset($web_page) && $web_page->services && count($web_page->services) > 0)
                    @foreach ($web_page->services as $service)
                        <div class="box">
                            <div class="img-box">
                                <img src="{{ $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png') }}"
                                    alt="{{ $service['name'] }}">
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
                        <div class="benefits">
                            <span class="benefit-item">
                                <i class="fas fa-mobile-alt"></i> Multiplataforma
                            </span>
                            <span class="benefit-item">
                                <i class="fas fa-cloud"></i> En la nube
                            </span>
                        </div>
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
                        <div class="benefits">
                            <span class="benefit-item">
                                <i class="fas fa-lock"></i> Encriptado
                            </span>
                            <span class="benefit-item">
                                <i class="fas fa-backup"></i> Respaldos
                            </span>
                        </div>
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
                        <div class="benefits">
                            <span class="benefit-item">
                                <i class="fas fa-headset"></i> 24/7
                            </span>
                            <span class="benefit-item">
                                <i class="fas fa-graduation-cap"></i> Capacitación
                            </span>
                        </div>
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
                        Conoce la experiencia de empresas que ya confían en nuestro sistema de gestión
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
                                                <img src="{{ asset('tenant/images/client-1.png') }}"
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
                                            <p class="client-role">Empresa de Taxis</p>
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

    <!-- contact section -->
    <section class="contact_section layout_padding-bottom">
        <div class="container">
            <div class="heading_container">
                <h2>
                    ¿Listo para modernizar<br>
                    tu empresa?
                </h2>
                <p class="section-subtitle">
                    Contáctanos para una demostración personalizada del sistema de gestión para taxis
                </p>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="contact_form">
                        <div class="form-header">
                            <i class="fas fa-rocket"></i>
                            <h4>Solicita una Demo</h4>
                            <p>Te mostramos cómo funciona nuestro sistema</p>
                        </div>
                        <form action="" class="contact-form-content">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <input type="text" placeholder="Nombre de tu empresa" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" placeholder="Tu nombre" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input type="tel" placeholder="Número de teléfono" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-taxi"></i>
                                    </div>
                                    <select required>
                                        <option value="">¿Cuántos vehículos tienes?</option>
                                        <option value="1-5">1 - 5 vehículos</option>
                                        <option value="6-15">6 - 15 vehículos</option>
                                        <option value="16-30">16 - 30 vehículos</option>
                                        <option value="31+">Más de 30 vehículos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-calendar-check"></i>
                                    Agendar Demostración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact-visual">
                        <div class="img-container">
                            <img src="{{ asset('tenant/images/contact-img.png') }}" alt="Demo del sistema">
                        </div>
                        <div class="demo-benefits">
                            <h5>En la demostración verás:</h5>
                            <div class="benefit-list">
                                <div class="benefit-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Panel de control administrativo</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Gestión de vehículos y conductores</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Sistema de facturación electrónica</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Reportes y estadísticas en tiempo real</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Control de pagos y finanzas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end contact section -->

@stop
