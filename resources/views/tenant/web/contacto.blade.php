@extends('tenant.layouts.guest')

@section('contenido')
    <section class="contact_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    ¿Necesitas ayuda con nuestro sistema?
                </h2>
                <p class="section-subtitle">
                    Estamos aquí para resolver tus dudas sobre facturación, gestión de taxis y soporte técnico
                </p>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="contact_form">
                        <div class="form-header">
                            <i class="fas fa-headset"></i>
                            <h4>Contáctanos</h4>
                            <p>Envíanos tu consulta y te responderemos pronto</p>
                        </div>
                        <form action="{{ route('tenant.web.contacto.store') }}" method="POST" class="contact-form-content">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Nombre completo"
                                    value="{{ old('name') }}" class="form-input @error('name') is-invalid @enderror"
                                    required>
                                @if ($errors->has('name'))
                                    <p class="mt-1 text-sm text-red-600 error-message">
                                        {{ $errors->first('name') }}
                                    </p>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" placeholder="Número de teléfono"
                                    value="{{ old('phone') }}" class="form-input @error('phone') is-invalid @enderror">
                                @if ($errors->has('phone'))
                                    <p class="mt-1 text-sm text-red-600 error-message">
                                        {{ $errors->first('phone') }}
                                    </p>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Correo electrónico"
                                    value="{{ old('email') }}" class="form-input @error('email') is-invalid @enderror"
                                    required>
                                @if ($errors->has('email'))
                                    <p class="mt-1 text-sm text-red-600 error-message">
                                        {{ $errors->first('email') }}
                                    </p>
                                @endif
                            </div>
                            <div class="form-group">
                                <textarea name="message" placeholder="Describe tu consulta o problema..."
                                    class="form-input message-input @error('message') is-invalid @enderror" rows="4" required>{{ old('message') }}</textarea>
                                @if ($errors->has('message'))
                                    <p class="mt-1 text-sm text-red-600 error-message">
                                        {{ $errors->first('message') }}
                                    </p>
                                @endif
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Enviar Mensaje
                                </button>
                            </div>

                            @if (session('success'))
                                <div class="success-message">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif
                        </form>

                        <div class="contact-info">
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <div class="method-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="method-info">
                                        <h6>Horario de atención</h6>
                                        <p>Lunes a Viernes: 8:00 AM - 6:00 PM<br>Sábados: 9:00 AM - 2:00 PM</p>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <div class="method-icon">
                                        <i class="fas fa-life-ring"></i>
                                    </div>
                                    <div class="method-info">
                                        <h6>Soporte técnico</h6>
                                        <p>Asistencia especializada para el sistema de gestión y facturación</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact-visual">
                        <div class="img-container">

                            <img
                                src="{{ $web_page['contact_image'] ? asset('storage/uploads/contact/' . $web_page['contact_image']) : asset('tenant/images/contact-img.png') }}">
                        </div>
                        <div class="features-grid">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h5>Soporte Confiable</h5>
                                <p>Equipo especializado en sistemas de gestión empresarial</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <h5>Respuesta Rápida</h5>
                                <p>Atendemos tu consulta en menos de 24 horas</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <h5>Asistencia Técnica</h5>
                                <p>Soporte completo para configuración y uso del sistema</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h5>Capacitación</h5>
                                <p>Te ayudamos a aprovechar al máximo todas las funcionalidades</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
