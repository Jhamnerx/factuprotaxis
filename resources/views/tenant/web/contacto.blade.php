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
                        <form action="" class="contact-form-content">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" placeholder="Nombre completo" required>
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
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input type="email" placeholder="Correo electrónico" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-icon message-icon">
                                        <i class="fas fa-comment-alt"></i>
                                    </div>
                                    <textarea placeholder="Describe tu consulta o problema..." class="message_input" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Enviar Mensaje
                                </button>
                            </div>
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
