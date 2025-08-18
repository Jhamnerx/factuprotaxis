@extends('taxis::layouts.master')

@section('title', 'Gestión de Pagos - Conductor')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8" x-data="pagosManager()">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Pagos</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Administra los pagos de tu vehículo</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('taxis.conductor.dashboard') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" transform="rotate(180 8 8)" />
                        </svg>
                        <span class="max-xs:sr-only">Volver al Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del Vehículo -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Mi Vehículo: {{ $vehiculo->placa }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                {{ $vehiculo->marca->name ?? '' }} {{ $vehiculo->modelo->name ?? '' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Propietario</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $vehiculo->propietario->name ?? '' }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Estado</p>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $vehiculo->estado === 'ACTIVO' ? 'bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400' }}">
                                {{ $vehiculo->estado }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario de Pagos -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Calendario de Pagos</h3>
                        <div class="flex items-center space-x-4">
                            <button @click="cambiarYear(-1)"
                                class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span class="text-lg font-semibold text-gray-900 dark:text-white" x-text="currentYear"></span>
                            <button @click="cambiarYear(1)"
                                class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4"
                        id="meses-calendario">
                        <!-- Los meses se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Pagos -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Historial de Pagos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Mes/Año</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Monto</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="historial-tbody"
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Filas del historial se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para Registrar Pago con Yape -->
        <!-- Modal backdrop -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-900/30 z-50 transition-opacity"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-100"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
            style="display: none;">
        </div>

        <!-- Modal dialog -->
        <div x-show="modalOpen"
            class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6" role="dialog"
            aria-modal="true" aria-labelledby="modal-title" x-transition:enter="transition ease-in-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-auto max-w-2xl w-full max-h-full">

                <!-- Paso 1: Información de Yape -->
                <div x-show="pasoActual === 1">
                    <!-- Modal header -->
                    <header class="px-5 py-3 border-b border-gray-200 dark:border-gray-700/60">
                        <div class="flex justify-between items-center">
                            <h2 id="modal-title" class="font-semibold text-gray-800 dark:text-gray-100">Pagar con Yape
                            </h2>
                            <button @click="cerrarModal"
                                class="cursor-pointer text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                                <span class="sr-only">Cerrar</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </header>
                    <!-- Modal content -->
                    <div class="px-5 pt-4 pb-5">
                        <!-- Información del titular -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">Usuario:</h3>
                            </div>
                            <p class="text-blue-800 dark:text-blue-200">
                                {{ session('taxis_user')['number'] ?? 'No disponible' }}</p>
                        </div>

                        <!-- Información del pago -->
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                                <h3 class="font-semibold text-green-900 dark:text-green-100">Total a pagar:</h3>
                            </div>
                            <p class="text-2xl font-bold text-green-800 dark:text-green-200"
                                x-text="'S/ ' + pagoData.monto"></p>
                        </div>

                        <!-- Información de Yape -->
                        <div x-show="yapeConfig" class="text-center mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Escanea con Yape</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Abre tu app Yape y escanea este código
                            </p>

                            <!-- QR Code -->
                            <div class="flex justify-center mb-4">
                                <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-purple-300">
                                    <img x-show="yapeConfig && yapeConfig.image_url_yape"
                                        :src="yapeConfig ? yapeConfig.image_url_yape : ''" alt="QR Yape"
                                        class="w-64 h-64 object-contain mx-auto">
                                </div>
                            </div>

                            <!-- Datos del titular -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 mb-4">
                                <div class="text-sm">
                                    <p class="text-purple-900 dark:text-purple-100"><strong>Titular:</strong> <span
                                            x-text="yapeConfig ? yapeConfig.name_yape : 'Cargando...'"></span></p>
                                    <p class="text-purple-900 dark:text-purple-100"><strong>Número:</strong> <span
                                            x-text="yapeConfig ? yapeConfig.telephone_yape : 'Cargando...'"></span></p>
                                </div>
                            </div>

                            <!-- Instrucciones -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-4">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>{{ session('taxis_user')['name'] }}</strong><br>
                                    Paga aquí con Yape
                                </p>
                            </div>
                        </div>

                        <!-- Botón para continuar -->
                        <div class="flex justify-center">
                            <button @click="siguientePaso"
                                class="cursor-pointer bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                ✓ YA PAGUÉ
                            </button>
                        </div>

                        <!-- Información del mes a pagar -->
                        <div class="text-center mt-4">
                            <div
                                class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-4 py-2 rounded-full inline-block text-sm">
                                📅 Pagando: <span x-text="getMesNombre(pagoData.mes) + ' ' + pagoData.year"></span>
                            </div>
                        </div>

                        <!-- Seguridad -->
                        <div class="text-center mt-4">
                            <div class="flex items-center justify-center text-sm text-green-600 dark:text-green-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pago 100% Seguro
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tus datos están protegidos con
                                encriptación SSL</p>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Verificación del pago -->
                <div x-show="pasoActual === 2">
                    <!-- Modal header -->
                    <header class="px-5 py-3 border-b border-gray-200 dark:border-gray-700/60">
                        <div class="flex justify-between items-center">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Verificar Pago</h2>
                            <button @click="pasoAnterior"
                                class="cursor-pointer text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                                <span class="sr-only">Volver</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </header>
                    <!-- Modal content -->
                    <div class="px-5 pt-4 pb-5">
                        <div class="text-center mb-6">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 mb-4">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                Ingresa el nombre del titular con el que se hizo el pago
                            </p>
                        </div>

                        <form @submit.prevent="verificarPago">
                            <div class="space-y-4">
                                <div>
                                    <label for="titular-yape"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nombre completo del titular de Yape <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="formVerificacion.titular_yape" id="titular-yape"
                                        placeholder="Ej. Carlos Perez Gomez"
                                        class="appearance-none w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white"
                                        required>
                                </div>

                                <div>
                                    <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" x-model="formVerificacion.desde_yape"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2">Se hizo desde la aplicación Yape</span>
                                    </label>
                                </div>

                                <div x-show="formVerificacion.desde_yape" x-transition>
                                    <label for="codigo-seguridad"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Código de seguridad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="formVerificacion.codigo_seguridad"
                                        id="codigo-seguridad" placeholder="Código de 6 dígitos" maxlength="6"
                                        class="appearance-none w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white"
                                        :required="formVerificacion.desde_yape">
                                </div>

                                <!-- Checkbox para pago en días anteriores -->
                                <div>
                                    <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" x-model="formVerificacion.pago_dias_anteriores"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2">Realicé el pago en días anteriores (no hoy)</span>
                                    </label>
                                </div>

                                <!-- Campo de fecha cuando marcó pago anterior -->
                                <div x-show="formVerificacion.pago_dias_anteriores" x-transition>
                                    <label for="fecha-pago"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Fecha en que realizó el pago <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" x-model="formVerificacion.fecha_pago" id="fecha-pago"
                                        :max="new Date().toISOString().split('T')[0]"
                                        class="appearance-none w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm bg-white"
                                        :required="formVerificacion.pago_dias_anteriores">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Seleccione la fecha exacta en que realizó el yapeo
                                    </p>
                                </div>

                                <!-- Mostrar errores -->
                                <div x-show="errorVerificacion"
                                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 rounded-lg p-3">
                                    <p class="text-sm text-red-600 dark:text-red-400" x-text="errorVerificacion"></p>
                                </div>

                                <!-- Mostrar éxito de verificación -->
                                <div x-show="pagoVerificado"
                                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/50 rounded-lg p-3">
                                    <p class="text-sm text-green-600 dark:text-green-400">✓ Pago verificado correctamente
                                    </p>
                                </div>
                            </div>

                            <div class="flex space-x-3 mt-6">
                                <button type="button" @click="pasoAnterior"
                                    class="cursor-pointer flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors">
                                    Volver
                                </button>
                                <button type="submit"
                                    :disabled="verificandoPago || !formVerificacion.titular_yape.trim() || (formVerificacion
                                        .desde_yape && !formVerificacion.codigo_seguridad.trim()) || (
                                        formVerificacion.pago_dias_anteriores && !formVerificacion.fecha_pago)"
                                    class="cursor-pointer flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:cursor-not-allowed"
                                    x-text="verificandoPago ? 'Verificando...' : (pagoVerificado ? 'Confirmar Pago' : 'Verificar')">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Estilos personalizados para SweetAlert2
            const swalStyle = document.createElement('style');
            swalStyle.innerHTML = `
                .swal2-popup-custom {
                    border-radius: 0.75rem !important;
                }
                
                .swal2-popup.swal2-toast {
                    border-radius: 0.75rem !important;
                }
                
                .swal2-title {
                    font-weight: 600 !important;
                }
                
                .swal2-html-container {
                    line-height: 1.5 !important;
                }
                
                .swal2-confirm {
                    border-radius: 0.5rem !important;
                    font-weight: 500 !important;
                    padding: 0.625rem 1.25rem !important;
                }
                
                .swal2-cancel {
                    border-radius: 0.5rem !important;
                    font-weight: 500 !important;
                    padding: 0.625rem 1.25rem !important;
                }
            `;
            document.head.appendChild(swalStyle);

            function pagosManager() {
                return {
                    // Estados reactivos
                    vehiculoSeleccionado: null,
                    vehiculoData: @json($vehiculo),
                    pagosData: [],
                    currentYear: new Date().getFullYear(),
                    coloresPagos: {},
                    modalOpen: false,
                    enviandoPago: false,

                    // Variables para el flujo de Yape
                    pasoActual: 1,
                    pagoVerificado: false,
                    verificandoPago: false,
                    confirmandoPago: false,
                    notificacionId: null,
                    errorVerificacion: null,
                    yapeConfig: null,
                    pagoData: {},

                    // Datos del formulario (paso 2)
                    formVerificacion: {
                        titular_yape: '',
                        codigo_seguridad: '',
                        desde_yape: false,
                        pago_dias_anteriores: false,
                        fecha_pago: ''
                    },

                    // Inicialización
                    init() {
                        this.vehiculoSeleccionado = this.vehiculoData;
                        this.cargarConfiguracionYape();
                        this.cargarPagosVehiculo();

                        // Listener para seleccionar mes
                        document.addEventListener('seleccionar-mes', (event) => {
                            this.seleccionarMes(event.detail);
                        });
                    },

                    // URLs de la API
                    get API_URLS() {
                        return {
                            PAYMENT_CONFIG: '{{ route('taxis.conductor.pagos.configuration') }}',
                            PAGOS_RECORDS: '{{ route('taxis.conductor.pagos.records') }}',
                            VERIFICAR_YAPE: '{{ route('taxis.conductor.pagos.verificar-yape') }}',
                            CONFIRMAR_YAPE: '{{ route('taxis.conductor.pagos.confirmar-yape') }}',
                            REGISTRAR_PAGO: '{{ route('taxis.conductor.pagos.registrar') }}'
                        };
                    },

                    // Cargar configuración de Yape
                    async cargarConfiguracionYape() {
                        try {
                            const response = await fetch(this.API_URLS.PAYMENT_CONFIG);
                            const data = await response.json();

                            if (response.ok && data.success) {
                                console.log('Configuración de Yape cargada:', data);
                                this.yapeConfig = {
                                    monto: data.vehiculo?.subscription?.plan?.price || 30,
                                    name_yape: data.yape_config?.name_yape || 'FACTURAPRO TAXIS',
                                    telephone_yape: data.yape_config?.telephone_yape || '999999999',
                                    image_url_yape: data.yape_config?.image_url_yape || ''
                                };
                                this.vehiculoSeleccionado = data.vehiculo;
                            } else {
                                console.error('Error en la respuesta:', data.message);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Configuración',
                                    text: data.message || 'No se pudo cargar la configuración de pagos',
                                    confirmButtonColor: '#dc2626'
                                });
                            }
                        } catch (error) {
                            console.error('Error cargando configuración:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudo conectar con el servidor para cargar la configuración',
                                confirmButtonColor: '#dc2626'
                            });
                        }
                    },

                    // Cargar pagos del vehículo
                    async cargarPagosVehiculo() {
                        try {
                            const response = await fetch(this.API_URLS.PAGOS_RECORDS + '?vehiculo_id=' + this.vehiculoData
                                .id);
                            const data = await response.json();

                            if (response.ok) {
                                this.pagosData = data.data || [];
                                this.generarColoresPagos();
                                this.renderizarCalendario();
                                this.renderizarHistorial();
                            }
                        } catch (error) {
                            console.error('Error cargando pagos:', error);
                        }
                    },

                    // Generar colores de pagos
                    generarColoresPagos() {
                        this.coloresPagos = {};

                        // Solo 2 colores: verde para pagado y rojo para pendiente
                        const colorPagado = '#10b981'; // Verde
                        const colorPendiente = '#dc2626'; // Rojo

                        for (let year = this.currentYear - 1; year <= this.currentYear + 1; year++) {
                            this.coloresPagos[year] = {};
                            for (let mes = 1; mes <= 12; mes++) {
                                // Por defecto todos los meses son pendientes (rojo)
                                // Solo se pondrán verdes cuando estén pagados
                                this.coloresPagos[year][mes] = colorPendiente;
                            }
                        }

                        // Actualizar los meses pagados a verde
                        this.pagosData.forEach(pago => {
                            if (pago.estado === 'pagado' && this.coloresPagos[pago.year]) {
                                this.coloresPagos[pago.year][pago.mes] = colorPagado;
                            }
                        });
                    }, // Cambiar año
                    cambiarYear(direction) {
                        this.currentYear += direction;
                        this.renderizarCalendario();
                    },

                    // Renderizar calendario
                    renderizarCalendario() {
                        const container = document.getElementById('meses-calendario');

                        const meses = [
                            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                        ];

                        container.innerHTML = meses.map((mes, index) => {
                            const mesNum = index + 1;

                            // Buscar si este mes está pagado (individual o múltiple)
                            let isPagado = false;
                            let montoMes = null;
                            let pagoInfo = null;

                            // Verificar pagos individuales
                            const pagoIndividual = this.pagosData.find(p => p.mes === mesNum && p.year === this
                                .currentYear && p.estado === 'pagado');
                            if (pagoIndividual) {
                                isPagado = true;
                                montoMes = pagoIndividual.monto;
                                pagoInfo = pagoIndividual;
                            }

                            // Verificar pagos múltiples
                            if (!isPagado) {
                                this.pagosData.forEach(pago => {
                                    if (pago.es_pago_multiple && pago.payment_details && pago.estado ===
                                        'pagado') {
                                        const detalleMes = pago.payment_details.find(d => d.mes === mesNum && d
                                            .year === this.currentYear);
                                        if (detalleMes) {
                                            isPagado = true;
                                            montoMes = detalleMes.monto_por_mes || detalleMes.monto;
                                            pagoInfo = pago;
                                        }
                                    }
                                });
                            }

                            // Verificar si el mes está permitido para pago
                            const isAllowed = this.isMonthAllowedForPayment(this.currentYear, mesNum);

                            const color = isPagado ? '#10b981' : '#dc2626'; // Verde si pagado, rojo si pendiente

                            // Determinar clases CSS
                            let cardClasses =
                            'mes-card border-2 rounded-lg p-4 text-center transition-all duration-200';
                            let clickHandler = '';
                            let cursorClass = '';
                            let disabledIndicator = '';

                            if (!isAllowed) {
                                // Mes no permitido (anterior a fecha de ingreso)
                                cardClasses += ' border-gray-300 bg-gray-100 dark:bg-gray-700 opacity-50';
                                cursorClass = 'cursor-not-allowed';
                                disabledIndicator = '<div class="text-lg mb-1">🚫</div>';
                            } else {
                                // Mes permitido
                                if (isPagado) {
                                    cardClasses += ' border-green-500 bg-green-50 dark:bg-green-900/20';
                                } else {
                                    cardClasses += ' border-red-500 bg-red-50 dark:bg-red-900/20';
                                }
                                cursorClass = 'cursor-pointer hover:shadow-md';
                                clickHandler =
                                    `onclick="this.dispatchEvent(new CustomEvent('seleccionar-mes', { detail: ${mesNum}, bubbles: true }))"`;
                            }

                            return `
                            <div class="${cardClasses} ${cursorClass}" ${clickHandler}>
                                ${disabledIndicator}
                                <div class="w-8 h-8 mx-auto mb-2 rounded-full" style="background-color: ${isAllowed ? color : '#9ca3af'}"></div>
                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm">${mes}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    ${!isAllowed ? 'No disponible' : (isPagado ? 'Pagado' : 'Pendiente')}
                                </p>
                                ${montoMes && isAllowed ? `<p class="text-xs font-medium text-gray-800 dark:text-gray-200">S/ ${montoMes}</p>` : ''}
                            </div>
                        `;
                        }).join('');
                    },
                    <
                    h4 class = "font-semibold text-gray-900 dark:text-white text-sm" > $ {
                        mes
                    } < /h4> <
                    p class = "text-xs text-gray-600 dark:text-gray-400" > $ {
                        this.currentYear
                    } < /p> <
                    p class =
                    "text-xs font-medium ${isPagado ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'} mt-1" >
                    $ {
                        isPagado ? 'Pagado' : 'Pendiente'
                    } <
                    /p>
                    $ {
                        montoMes ? `<p class="text-xs font-medium text-gray-800 dark:text-gray-200">S/ ${montoMes}</p>` :
                            `<p class="text-xs text-blue-600 dark:text-blue-400">Clic para pagar</p>`
                    } <
                    /div>
                    `;
                        }).join('');
                    },

                    // Función para verificar si un mes está permitido para pago (no anterior a la fecha de ingreso)
                    isMonthAllowedForPayment(year, month) {
                        // Si no hay vehículo seleccionado, no permitir
                        if (!this.vehiculoData) {
                            return false;
                        }

                        // Si no hay fecha de ingreso, permitir cualquier mes
                        if (!this.vehiculoData.fecha_ingreso) {
                            return true;
                        }

                        // Crear fecha del mes que se quiere pagar (primer día del mes)
                        const paymentDate = new Date(year, month - 1, 1);
                        
                        // Crear fecha de ingreso del vehículo (primer día del mes de ingreso)
                        const entryDate = new Date(this.vehiculoData.fecha_ingreso);
                        const entryFirstDay = new Date(entryDate.getFullYear(), entryDate.getMonth(), 1);

                        // Permitir pago si la fecha del mes es mayor o igual a la fecha de ingreso
                        return paymentDate >= entryFirstDay;
                    },

                    // Seleccionar mes para realizar pago
                    seleccionarMes(mes) {
                        // Verificar si el mes está antes de la fecha de ingreso del vehículo
                        if (!this.isMonthAllowedForPayment(this.currentYear, mes)) {
                            const vehicleEntry = this.vehiculoData && this.vehiculoData.fecha_ingreso;
                            const entryDate = vehicleEntry ? new Date(vehicleEntry) : null;
                            const entryDateStr = entryDate ? 
                                `
                    $ {
                        String(entryDate.getMonth() + 1).padStart(2, '0')
                    }
                    /${entryDate.getFullYear()}` : 
            'fecha de ingreso';

            Swal.fire({
                icon: 'warning',
                title: 'Período no válido',
                text: `No se puede pagar un mes anterior a la fecha de ingreso del vehículo (${entryDateStr}).`,
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        // Verificar si ya está pagado
        const pagoExistente = this.pagosData.find(p => p.mes === mes && p.year === this.currentYear && p
            .estado === 'pagado');

        if (pagoExistente) {
            Swal.fire({
                title: 'Mes ya pagado',
                text: `El mes ${this.getMesNombre(mes)} ${this.currentYear} ya está pagado.`,
                icon: 'info',
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            });
            return;
        }

        // Configurar datos del pago
        this.pagoData = {
            mes: mes,
            year: this.currentYear,
            vehiculo_id: this.vehiculoData.id
        };

        // Resetear formulario y abrir modal
        this.pasoActual = 1;
        this.pagoVerificado = false;
        this.notificacionId = null;
        this.errorVerificacion = null;
        this.formVerificacion = {
            titular_yape: '',
            codigo_seguridad: '',
            desde_yape: false,
            pago_dias_anteriores: false,
            fecha_pago: ''
        };

        this.modalOpen = true;
    },

    // Obtener nombre del mes
    getMesNombre(numeroMes) {
            const meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            return meses[numeroMes - 1] || 'Mes desconocido';
        },

        // Renderizar historial
        renderizarHistorial() {
            const tbody = document.getElementById('historial-tbody');

            if (this.pagosData.length === 0) {
                tbody.innerHTML = `
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No hay pagos registrados
                                        </td>
                                    </tr>
                                `;
                return;
            }

            // Procesar pagos para el historial
            let historialRows = [];

            this.pagosData.forEach(pago => {
                if (pago.es_pago_multiple && pago.payment_details) {
                    // Es un pago múltiple, mostrar como un solo registro con detalles
                    const mesesPagados = pago.payment_details
                        .filter(d => d.year === this.currentYear)
                        .map(d => this.getMesNombre(d.mes))
                        .join(', ');

                    const montoTotal = pago.payment_details
                        .filter(d => d.year === this.currentYear)
                        .reduce((total, d) => total + (d.monto_por_mes || d.monto || 0), 0);

                    historialRows.push(`
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                <div class="font-medium">Pago Múltiple (${pago.cantidad_meses || pago.payment_details.length} meses)</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">${mesesPagados}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                S/ ${montoTotal.toFixed(2)}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                ${pago.tipo || 'Múltiple'}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${this.getEstadoPagoClasses(pago.estado)}">
                                                    ${pago.estado}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                ${pago.fecha_cobro ? new Date(pago.fecha_cobro).toLocaleDateString() : 'N/A'}
                                            </td>
                                        </tr>
                                    `);
                } else if (pago.mes && pago.year) {
                    // Es un pago individual
                    historialRows.push(`
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                ${this.getMesNombre(pago.mes)} ${pago.year}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                S/ ${pago.monto || '0.00'}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                ${pago.tipo || 'N/A'}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${this.getEstadoPagoClasses(pago.estado)}">
                                                    ${pago.estado}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                ${pago.fecha_cobro ? new Date(pago.fecha_cobro).toLocaleDateString() : 'N/A'}
                                            </td>
                                        </tr>
                                    `);
                }
            });

            tbody.innerHTML = historialRows.join('');
        },

        // Obtener clases para estado de pago
        getEstadoPagoClasses(estado) {
            switch (estado) {
                case 'pagado':
                    return 'bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400';
                case 'pendiente':
                    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-400/10 dark:text-yellow-400';
                case 'vencido':
                    return 'bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400';
                default:
                    return 'bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400';
            }
        },

        // Seleccionar mes para realizar pago
        seleccionarMes(mes) {
            // Verificar si el mes ya está pagado (individual o múltiple)
            let mesPagado = false;

            // Verificar en pagos individuales
            const pagoIndividual = this.pagosData.find(p => p.mes === mes && p.year === this.currentYear && p
                .estado === 'pagado');
            if (pagoIndividual) {
                mesPagado = true;
            }

            // Verificar en pagos múltiples
            if (!mesPagado) {
                this.pagosData.forEach(pago => {
                    if (pago.es_pago_multiple && pago.payment_details && pago.estado === 'pagado') {
                        const detalleMes = pago.payment_details.find(d => d.mes === mes && d.year === this
                            .currentYear);
                        if (detalleMes) {
                            mesPagado = true;
                        }
                    }
                });
            }

            if (mesPagado) {
                Swal.fire({
                    title: 'Mes ya pagado',
                    text: `El mes ${this.getMesNombre(mes)} ${this.currentYear} ya está pagado.`,
                    icon: 'info',
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
                });
                return;
            }

            // Configurar datos del pago
            this.pagoData = {
                mes: mes,
                year: this.currentYear,
                vehiculo_id: this.vehiculoData.id,
                monto: this.yapeConfig ? this.yapeConfig.monto : 50.00
            };

            // Resetear formulario y abrir modal
            this.pasoActual = 1;
            this.pagoVerificado = false;
            this.notificacionId = null;
            this.errorVerificacion = null;
            this.formVerificacion = {
                titular_yape: '',
                codigo_seguridad: '',
                desde_yape: false,
                pago_dias_anteriores: false,
                fecha_pago: ''
            };

            this.modalOpen = true;
        },

        // Ir al siguiente paso (de QR a verificación)
        siguientePaso() {
            this.pasoActual = 2;
            this.errorVerificacion = null;
        },

        // Ir al paso anterior (de verificación a QR)
        pasoAnterior() {
            this.pasoActual = 1;
            this.errorVerificacion = null;
        },

        // Verificar el pago de Yape
        async verificarPago() {
                if (!this.formVerificacion.titular_yape.trim()) {
                    this.mostrarError('Por favor ingrese el nombre completo del titular de Yape');
                    return;
                }

                // Validar código de seguridad si está marcado "desde_yape"
                if (this.formVerificacion.desde_yape && !this.formVerificacion.codigo_seguridad.trim()) {
                    this.mostrarError(
                        'El código de seguridad es requerido cuando se marca "Se hizo desde la aplicación Yape"'
                    );
                    return;
                }

                // Validar fecha si marcó pago en días anteriores
                if (this.formVerificacion.pago_dias_anteriores && !this.formVerificacion.fecha_pago) {
                    this.mostrarError('Por favor seleccione la fecha en que realizó el pago');
                    return;
                }

                this.verificandoPago = true;
                this.errorVerificacion = null;

                try {
                    // Determinar la fecha del pago
                    const fechaPago = this.formVerificacion.pago_dias_anteriores ?
                        this.formVerificacion.fecha_pago :
                        new Date().toISOString().split('T')[0];

                    const response = await fetch(this.API_URLS.VERIFICAR_YAPE, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            titular_yape: this.formVerificacion.titular_yape,
                            codigo_seguridad: this.formVerificacion.codigo_seguridad || null,
                            vehiculo_id: this.pagoData.vehiculo_id,
                            mes: this.pagoData.mes,
                            year: this.pagoData.year,
                            monto: this.pagoData.monto,
                            desde_yape: this.formVerificacion.desde_yape,
                            fecha_pago: fechaPago,
                            busqueda_flexible: true
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.pagoVerificado = true;
                        this.notificacionId = data.data.notification_id;
                        this.errorVerificacion = null;
                    } else {
                        if (data.errors) {
                            const firstError = Object.values(data.errors)[0];
                            this.errorVerificacion = Array.isArray(firstError) ? firstError[0] : firstError;
                        } else {
                            this.errorVerificacion = data.message || 'No se pudo verificar el pago';
                        }
                        this.pagoVerificado = false;
                        this.mostrarAvisoWhatsApp();
                    }
                } catch (error) {
                    console.error('Error al verificar pago:', error);
                    this.errorVerificacion = 'Error de conexión al verificar el pago';
                    this.pagoVerificado = false;
                    this.mostrarAvisoWhatsApp();
                } finally {
                    this.verificandoPago = false;
                }
            },

            // Confirmar el pago
            async confirmarPago() {
                    this.confirmandoPago = true;
                    this.errorVerificacion = null;

                    try {
                        const pagoData = {
                            vehiculoId: this.pagoData.vehiculo_id,
                            mes: this.pagoData.mes,
                            year: this.pagoData.year,
                            monto: this.pagoData.monto,
                            fecha: new Date().toISOString().split('T')[0],
                            descuento: 0,
                            moneda: 'PEN',
                            tipo: 'yape',
                            color: '#10b981',
                            metodo_pago: 'yape',
                            notification_id: this.notificacionId,
                            titular_yape: this.formVerificacion.titular_yape
                        };

                        const response = await fetch(this.API_URLS.CONFIRMAR_YAPE, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(pagoData)
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            this.modalOpen = false;

                            // Recargar los datos del vehículo para actualizar el calendario
                            await this.cargarPagosVehiculo();

                            // Regenerar los colores de pagos
                            this.generarColoresPagos();

                            // Renderizar el calendario actualizado
                            this.renderizarCalendario();

                            // Mostrar éxito con SweetAlert2
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Confirmado!',
                                html: `
                                                <div class="text-center">
                                                    <p class="text-lg font-semibold mb-2">Su pago ha sido procesado exitosamente</p>
                                                    <p class="text-sm text-gray-600">El calendario se ha actualizado automáticamente</p>
                                                </div>
                                            `,
                                confirmButtonText: 'Excelente',
                                confirmButtonColor: '#059669',
                                timer: 4000,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                background: document.documentElement.classList.contains('dark') ? '#1f2937' :
                                    '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f9fafb' :
                                    '#111827'
                            });
                        } else {
                            if (data.errors) {
                                const firstError = Object.values(data.errors)[0];
                                this.errorVerificacion = Array.isArray(firstError) ? firstError[0] : firstError;
                            } else {
                                this.errorVerificacion = data.message || 'Error al confirmar el pago';
                            }
                        }
                    } catch (error) {
                        console.error('Error al confirmar pago:', error);
                        this.errorVerificacion = 'Error de conexión al confirmar el pago';
                    } finally {
                        this.confirmandoPago = false;
                    }
                },

                // Cerrar modal
                cerrarModal() {
                    this.modalOpen = false;
                    this.pasoActual = 1;
                    this.pagoVerificado = false;
                    this.verificandoPago = false;
                    this.confirmandoPago = false;
                    this.notificacionId = null;
                    this.errorVerificacion = null;
                    this.formVerificacion = {
                        titular_yape: '',
                        codigo_seguridad: '',
                        desde_yape: false,
                        pago_dias_anteriores: false,
                        fecha_pago: ''
                    };
                },

                // Mostrar aviso de contacto por WhatsApp
                mostrarAvisoWhatsApp() {
                    Swal.fire({
                        icon: 'info',
                        title: '¿Realizaste el pago?',
                        html: `
                                        <div class="text-center">
                                            <p class="mb-4">Si realizaste el pago pero no pudimos verificarlo automáticamente, puedes contactar al propietario para confirmar tu pago manualmente.</p>
                                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 mb-4">
                                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                                    <strong>Vehículo:</strong> {{ $vehiculo->placa }}<br>
                                                    <strong>Mes:</strong> <span class="mes-info"></span>
                                                </p>
                                            </div>
                                        </div>
                                    `,
                        showCancelButton: true,
                        confirmButtonText: 'Contactar por WhatsApp',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#25D366',
                        customClass: {
                            popup: 'swal2-popup-custom'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.abrirWhatsApp();
                        }
                    });

                    // Actualizar el mes en el modal
                    setTimeout(() => {
                        const mesInfo = document.querySelector('.mes-info');
                        if (mesInfo && this.pagoData) {
                            mesInfo.textContent =
                                `${this.getMesNombre(this.pagoData.mes)} ${this.pagoData.year}`;
                        }
                    }, 100);
                },

                // Abrir WhatsApp con mensaje predefinido
                abrirWhatsApp() {
                    const mensaje = encodeURIComponent(
                        `Hola, soy conductor del vehículo ${this.vehiculoData.placa}. ` +
                        `Necesito información sobre cómo activar el plan de suscripción para poder realizar los pagos mensuales.`
                    );
                    const urlWhatsApp = `https://wa.me/51999999999?text=${mensaje}`;
                            window.open(urlWhatsApp, '_blank');
                        },

                        // Mostrar error cuando no hay plan
                        mostrarErrorSinPlan() {
                            setTimeout(() => {
                                this.mostrarAvisoWhatsApp();
                            }, 100);
                        },

                        // Mostrar error
                        mostrarError(mensaje) {
                            this.errorVerificacion = mensaje;
                        },

                        // Mostrar éxito
                        mostrarExito(mensaje) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: mensaje,
                                icon: 'success',
                                timer: 3000,
                                timerProgressBar: true,
                                position: 'top-end',
                                toast: true,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'swal2-popup-custom'
                                }
                            });
                        },

                        // Obtener nombre del mes
                        getMesNombre(mes) {
                            const meses = [
                                '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                            ];
                            return meses[mes] || '';
                        }
            }; // Cerrar correctamente el objeto retornado por pagosManager
            }
        </script>
    </div>
@endsection

@push('styles')
    <style>
        .form-input,
        .form-select {
            @apply block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }

        .btn {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
    </style>
@endpush
