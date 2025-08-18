<footer class="footer_section">
    <div class="container">
        <div class="footer-content">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="footer-info">
                        <div class="company-info">
                            <h6>{{ $company->trade_name ?? $company->name }}</h6>
                            <p class="footer-description">
                                Sistema completo de gestión para empresas de taxis: control de flota, conductores,
                                propietarios, pagos, permisos, contratos y facturación electrónica SUNAT.
                            </p>
                        </div>
                        <div class="footer-features">
                            <span class="footer-badge">
                                <i class="fas fa-taxi"></i> Gestión de Flota
                            </span>
                            <span class="footer-badge">
                                <i class="fas fa-file-contract"></i> Contratos y Permisos
                            </span>
                            <span class="footer-badge">
                                <i class="fas fa-receipt"></i> Facturación SUNAT
                            </span>
                            <span class="footer-badge">
                                <i class="fas fa-calendar-check"></i> Control de Pagos
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="footer-right">
                        <div class="copyright-info">
                            <div class="copyright-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="copyright-text">
                                <p class="copyright-main">
                                    &copy; {{ now()->year }} Todos los derechos reservados
                                </p>
                                <p class="copyright-sub">
                                    Sistema de gestión integral para empresas de taxis desarrollado para
                                    optimizar operaciones, controlar flota, gestionar conductores y automatizar
                                    procesos administrativos de {{ $company->trade_name ?? $company->name }}
                                </p>
                            </div>
                        </div>
                        <div class="footer-actions">
                            <a href="{{ route('tenant.web.contacto') }}" class="footer-btn">
                                <i class="fas fa-headset"></i>
                                Soporte Técnico
                            </a>
                            <a href="{{ route('login') }}" class="footer-btn primary">
                                <i class="fas fa-tachometer-alt"></i>
                                Panel de Control
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="tech-info">
                        <span class="tech-badge">
                            <i class="fas fa-code"></i> Laravel + Vue.js
                        </span>
                        <span class="tech-badge">
                            <i class="fas fa-mobile-alt"></i> Multiplataforma
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-info">
                        <span class="security-item">
                            <i class="fas fa-shield-alt"></i> Sistema Seguro
                        </span>
                        <span class="security-item">
                            <i class="fas fa-cloud-upload-alt"></i> Backup Automático
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
