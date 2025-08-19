<footer class="footer_section">
    <div class="container">
        <div class="footer-content">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <div class="footer-info">
                        <div class="company-info">
                            <h6>{{ $company->trade_name ?? $company->name }}</h6>
                            <p class="footer-description">
                                Sistema de gestión integral para empresas de taxis
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="footer-right">
                        <div class="footer-actions">
                            <a href="#" class="footer-btn">
                                <i class="fas fa-headset"></i>
                                Soporte
                            </a>
                            <a href="{{ route('login') }}" class="footer-btn primary">
                                <i class="fas fa-sign-in-alt"></i>
                                Acceder
                            </a>
                        </div>
                        <div class="copyright-simple">
                            <p>&copy; {{ now()->year }} Todos los derechos reservados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
