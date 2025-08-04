@extends('taxis::layouts.master')

@section('title', 'Gestión de Pagos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8" x-data="pagosManager()">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Pagos</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Administra los pagos de tus vehículos</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $propietario['name'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Selector de Vehículo -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Seleccionar Vehículo</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="vehiculos-container">
                    <!-- Los vehículos se cargarán aquí dinámicamente -->
                    <div class="flex items-center justify-center p-8" x-show="!vehiculosLoaded">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Cargando vehículos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Vehículo Seleccionado -->
        <div id="vehiculo-info" class="mb-6" x-show="vehiculoSeleccionado" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <span
                                x-text="vehiculoSeleccionado ? `Vehículo: ${vehiculoSeleccionado.placa}` : 'Información del Vehículo'"></span>
                        </h3>
                        <div
                            x-show="vehiculoSeleccionado && vehiculoSeleccionado.subscription && vehiculoSeleccionado.subscription.plan">
                            <button type="button" @click="modalOpen = true"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Registrar Pago
                            </button>
                        </div>
                        <div x-show="vehiculoSeleccionado && (!vehiculoSeleccionado.subscription || !vehiculoSeleccionado.subscription.plan)"
                            class="text-center">
                            <div
                                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 rounded-lg px-4 py-2">
                                <p class="text-sm text-red-600 dark:text-red-400 font-medium">Sin plan asignado</p>
                                <p class="text-xs text-red-500 dark:text-red-400">Contacte al administrador</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white"
                                x-text="vehiculoSeleccionado?.placa || '-'"></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Placa</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white"
                                x-text="vehiculoSeleccionado?.subscription?.plan?.name || 'Sin plan'"></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Plan</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white"
                                x-text="vehiculoSeleccionado?.estado || '-'"></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Estado</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario de Pagos -->
        <div id="calendario-pagos" class="mb-6" x-show="vehiculoSeleccionado" x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Calendario de Pagos</h3>
                        <div class="flex items-center space-x-4">
                            <button type="button" @click="cambiarYear(-1)"
                                class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span x-text="currentYear" class="text-lg font-medium text-gray-900 dark:text-white"></span>
                            <button type="button" @click="cambiarYear(1)"
                                class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
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
        <div id="historial-pagos" x-show="vehiculoSeleccionado" x-transition>
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
                                    Fecha</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Mes/Año</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Monto</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tipo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="historial-tbody"
                            class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Los registros se cargarán aquí dinámicamente -->
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
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-auto max-w-2xl w-full max-h-full"
                @click.outside="modalOpen = false" @keydown.escape.window="modalOpen = false">

                <!-- Paso 1: Información de Yape -->
                <div x-show="pasoActual === 1">
                    <!-- Modal header -->
                    <header class="px-5 py-3 border-b border-gray-200 dark:border-gray-700/60">
                        <div class="flex justify-between items-center">
                            <h2 id="modal-title" class="font-semibold text-gray-800 dark:text-gray-100">Pagar con Yape
                            </h2>
                            <button @click="cerrarModal"
                                class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
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
                                    <strong>{{ $propietario['name'] }}</strong><br>
                                    Paga aquí con Yape
                                </p>
                            </div>
                        </div>

                        <!-- Botón para continuar -->
                        <div class="flex justify-center">
                            <button @click="siguientePaso"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                ✓ YA PAGUÉ
                            </button>
                        </div>

                        <!-- Información del mes a pagar -->
                        <div class="text-center mt-4">
                            <div
                                class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-4 py-2 rounded-full inline-block text-sm">
                                � Pagando: <span x-text="getMesNombre(pagoData.mes) + ' ' + pagoData.year"></span>
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
                            <button @click="cerrarModal"
                                class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
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
                                    class="flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors">
                                    Volver
                                </button>
                                <button type="submit"
                                    :disabled="verificandoPago || !formVerificacion.titular_yape.trim() || (formVerificacion
                                        .desde_yape && !formVerificacion.codigo_seguridad.trim())"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <span x-show="!verificandoPago">VERIFICAR YAPEO</span>
                                    <span x-show="verificandoPago">Verificando...</span>
                                </button>
                            </div>

                            <div x-show="pagoVerificado" class="mt-4">
                                <button type="button" @click="confirmarPago" :disabled="confirmandoPago"
                                    class="w-full bg-green-600 hover:bg-green-700 disabled:bg-green-300 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <span x-show="!confirmandoPago">CONFIRMAR PAGO</span>
                                    <span x-show="confirmandoPago">Confirmando...</span>
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
                    vehiculosData: [],
                    vehiculoSeleccionado: null,
                    pagosData: [],
                    currentYear: new Date().getFullYear(),
                    coloresPagos: {},
                    modalOpen: false,
                    enviandoPago: false,
                    vehiculosLoaded: false,

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
                        desde_yape: false
                    },

                    // Inicialización
                    init() {
                        this.cargarVehiculos();
                        this.cargarConfiguracionYape();
                        this.$el.addEventListener('seleccionar-vehiculo', (e) => {
                            this.seleccionarVehiculo(e.detail);
                        });
                        this.$el.addEventListener('seleccionar-mes', (e) => {
                            this.seleccionarMes(e.detail);
                        });
                    },

                    // URLs de la API
                    get API_URLS() {
                        return {
                            vehiculos: `{{ route('taxis.propietario.vehiculos.records') }}`,
                            pagos: `{{ route('taxis.propietario.pagos.records') }}`,
                            registrarPago: `{{ route('taxis.propietario.pagos.registrar') }}`,
                            paymentConfiguration: `{{ route('taxis.propietario.payment.configuration') }}`,
                            verificarYape: `{{ route('taxis.propietario.verificar.yape') }}`,
                            confirmarPago: `{{ route('taxis.propietario.confirmar.pago.yape') }}`
                        };
                    },

                    // Cargar configuración de Yape
                    async cargarConfiguracionYape() {
                        try {
                            const response = await fetch(this.API_URLS.paymentConfiguration);
                            const data = await response.json();

                            if (data.success) {
                                this.yapeConfig = data.data;
                                console.log('Configuración Yape cargada:', this.yapeConfig);
                            } else {
                                console.error('Error al cargar configuración Yape:', data.message);
                            }
                        } catch (error) {
                            console.error('Error al cargar configuración Yape:', error);
                        }
                    },

                    // Cargar vehículos
                    async cargarVehiculos() {
                        try {
                            const response = await fetch(this.API_URLS.vehiculos);
                            const data = await response.json();

                            console.log('Respuesta de vehículos:', data);

                            if (data.data) {
                                this.vehiculosData = data.data;
                            } else if (Array.isArray(data)) {
                                this.vehiculosData = data;
                            } else {
                                this.vehiculosData = [];
                            }

                            this.vehiculosLoaded = true;
                            this.renderizarVehiculos();
                        } catch (error) {
                            console.error('Error al cargar vehículos:', error);
                            this.vehiculosLoaded = true;
                            
                            // Usar SweetAlert2 para mostrar error de carga
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudieron cargar los vehículos. Por favor, verifique su conexión e intente nuevamente.',
                                confirmButtonText: 'Reintentar',
                                confirmButtonColor: '#dc2626',
                                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.vehiculosLoaded = false;
                                    this.cargarVehiculos();
                                }
                            });
                        }
                    },

                    // Renderizar vehículos
                    renderizarVehiculos() {
                        const container = document.getElementById('vehiculos-container');

                        if (this.vehiculosData.length === 0) {
                            container.innerHTML = `
                            <div class="col-span-full text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No tienes vehículos registrados</p>
                            </div>
                        `;
                            return;
                        }

                        container.innerHTML = this.vehiculosData.map(vehiculo => {
                            const tienePlan = vehiculo.subscription && vehiculo.subscription.plan;
                            const borderClass = tienePlan ? 'border-gray-200 dark:border-gray-600' :
                                'border-red-200 dark:border-red-600';
                            const hoverClass = tienePlan ?
                                'hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20' :
                                'hover:border-red-400 hover:bg-red-50 dark:hover:bg-red-900/20';
                            const iconClass = tienePlan ? 'text-blue-600 dark:text-blue-400' :
                                'text-red-600 dark:text-red-400';
                            const bgClass = tienePlan ? 'bg-blue-100 dark:bg-blue-900' : 'bg-red-100 dark:bg-red-900';

                            return `
                            <div class="vehiculo-card cursor-pointer border-2 ${borderClass} rounded-lg p-4 ${hoverClass} transition-all duration-200" 
                                 data-vehiculo-id="${vehiculo.id}"
                                 onclick="this.dispatchEvent(new CustomEvent('seleccionar-vehiculo', { detail: ${vehiculo.id}, bubbles: true }))">
                                <div class="text-center">
                                    <div class="w-12 h-12 mx-auto mb-3 ${bgClass} rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 ${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            ${tienePlan ? 
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' : 
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>'
                                            }
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">${vehiculo.placa}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">${vehiculo.marca?.nombre || 'N/A'} ${vehiculo.modelo?.nombre || ''}</p>
                                    ${tienePlan ? 
                                        `<p class="text-xs text-blue-600 dark:text-blue-400 font-medium mt-1">${vehiculo.subscription.plan.name}</p>` :
                                        `<p class="text-xs text-red-600 dark:text-red-400 font-medium mt-1">Sin plan</p>`
                                    }
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2 ${this.getEstadoClasses(vehiculo.estado)}">
                                        ${vehiculo.estado}
                                    </span>
                                </div>
                            </div>
                        `;
                        }).join('');
                    },

                    // Obtener clases CSS para el estado
                    getEstadoClasses(estado) {
                        switch (estado?.toLowerCase()) {
                            case 'activo':
                                return 'bg-green-100 text-green-800 dark:bg-green-400/10 dark:text-green-400';
                            case 'inactivo':
                                return 'bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400';
                            default:
                                return 'bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400';
                        }
                    },

                    // Seleccionar vehículo
                    async seleccionarVehiculo(vehiculoId) {
                        // Remover selección anterior
                        document.querySelectorAll('.vehiculo-card').forEach(card => {
                            card.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                            card.classList.add('border-gray-200', 'dark:border-gray-600');
                        });

                        // Agregar selección actual
                        const cardSeleccionada = document.querySelector(`[data-vehiculo-id="${vehiculoId}"]`);
                        if (cardSeleccionada) {
                            cardSeleccionada.classList.remove('border-gray-200', 'dark:border-gray-600');
                            cardSeleccionada.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        }

                        const vehiculo = this.vehiculosData.find(v => v.id === vehiculoId);

                        // Validar si el vehículo tiene plan asignado
                        if (!vehiculo || !vehiculo.subscription || !vehiculo.subscription.plan) {
                            this.vehiculoSeleccionado = null;
                            this.mostrarErrorSinPlan();
                            return;
                        }

                        this.vehiculoSeleccionado = vehiculo;

                        // Cargar pagos del vehículo
                        await this.cargarPagosVehiculo(vehiculoId);
                    },

                    // Cargar pagos del vehículo
                    async cargarPagosVehiculo(vehiculoId) {
                        try {
                            const response = await fetch(`${this.API_URLS.pagos}?vehiculo_id=${vehiculoId}`);
                            const data = await response.json();

                            console.log('Respuesta de pagos:', data);

                            if (data.data) {
                                this.pagosData = data.data;
                            } else if (Array.isArray(data)) {
                                this.pagosData = data;
                            } else {
                                this.pagosData = [];
                            }

                            this.generarColoresPagos();
                            this.renderizarCalendario();
                            this.renderizarHistorial();
                        } catch (error) {
                            console.error('Error al cargar pagos:', error);
                            
                            // Usar SweetAlert2 para mostrar error de carga de pagos
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al Cargar Pagos',
                                text: 'No se pudieron cargar los pagos del vehículo. Los datos mostrados pueden no estar actualizados.',
                                confirmButtonText: 'Continuar',
                                confirmButtonColor: '#dc2626',
                                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                            });
                        }
                    },

                    // Generar colores de pagos
                    generarColoresPagos() {
                        this.coloresPagos = {
                            [this.currentYear]: {}
                        };

                        // Inicializar todos los meses como no pagados
                        for (let mes = 1; mes <= 12; mes++) {
                            this.coloresPagos[this.currentYear][mes] = '#dc2626'; // rojo por defecto
                        }

                        // Procesar cada pago
                        this.pagosData.forEach(pago => {
                            if (pago.year === this.currentYear && pago.estado === 'pagado') {
                                if (pago.es_pago_multiple && pago.payment_details) {
                                    // Es un pago múltiple, procesar cada mes del payment_details
                                    pago.payment_details.forEach(detalle => {
                                        if (detalle.year === this.currentYear) {
                                            this.coloresPagos[this.currentYear][detalle.mes] = detalle.color ||
                                                '#10b981';
                                        }
                                    });
                                } else if (pago.mes) {
                                    // Es un pago individual
                                    this.coloresPagos[this.currentYear][pago.mes] = '#10b981'; // verde
                                }
                            }
                        });
                    },

                    // Cambiar año
                    cambiarYear(direction) {
                        this.currentYear += direction;
                        if (this.vehiculoSeleccionado) {
                            this.cargarPagosVehiculo(this.vehiculoSeleccionado.id);
                        }
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

                            // Verificar en pagos individuales
                            const pagoIndividual = this.pagosData.find(p => p.mes === mesNum && p.year === this
                                .currentYear && p.estado === 'pagado');
                            if (pagoIndividual) {
                                isPagado = true;
                                montoMes = pagoIndividual.monto;
                                pagoInfo = pagoIndividual;
                            } else {
                                // Verificar en pagos múltiples
                                this.pagosData.forEach(pago => {
                                    if (pago.es_pago_multiple && pago.payment_details && pago.estado ===
                                        'pagado') {
                                        const detalleMes = pago.payment_details.find(d => d.mes === mesNum && d
                                            .year === this.currentYear);
                                        if (detalleMes) {
                                            isPagado = true;
                                            montoMes = detalleMes.monto_por_mes || detalleMes.monto;
                                            pagoInfo = {
                                                ...pago,
                                                ...detalleMes
                                            };
                                        }
                                    }
                                });
                            }

                            const color = this.coloresPagos[this.currentYear]?.[mesNum] || '#dc2626';

                            return `
                            <div class="mes-card border-2 rounded-lg p-4 text-center cursor-pointer hover:shadow-md transition-all duration-200 ${isPagado ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-red-500 bg-red-50 dark:bg-red-900/20'}" 
                                 onclick="this.dispatchEvent(new CustomEvent('seleccionar-mes', { detail: ${mesNum}, bubbles: true }))">
                                <div class="w-8 h-8 mx-auto mb-2 rounded-full" style="background-color: ${color}"></div>
                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm">${mes}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">${isPagado ? 'Pagado' : 'Pendiente'}</p>
                                ${montoMes ? `<p class="text-xs font-medium text-gray-800 dark:text-gray-200">S/ ${montoMes}</p>` : ''}
                            </div>
                        `;
                        }).join('');
                    },

                    // Renderizar historial
                    renderizarHistorial() {
                        const tbody = document.getElementById('historial-tbody');

                        if (this.pagosData.length === 0) {
                            tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        ${pago.fecha_cobro || 'N/A'}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        <div class="font-medium">Pago Múltiple (${pago.cantidad_meses || pago.payment_details.length} meses)</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">${mesesPagados}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        S/ ${montoTotal.toFixed(2)}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${this.getEstadoPagoClasses(pago.estado)}">
                                            ${pago.estado}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        ${pago.tipo || 'Múltiple'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Ver
                                        </button>
                                    </td>
                                </tr>
                                `);
                            } else if (pago.mes && pago.year) {
                                // Es un pago individual
                                historialRows.push(`
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        ${pago.fecha_cobro || 'N/A'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        ${this.getMesNombre(pago.mes)} ${pago.year}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        S/ ${pago.monto || '0.00'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${this.getEstadoPagoClasses(pago.estado)}">
                                            ${pago.estado}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        ${pago.tipo || 'N/A'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Ver
                                        </button>
                                    </td>
                                </tr>
                                `);
                            }
                        });

                        tbody.innerHTML = historialRows.join('');
                    },

                    // Obtener clases para estado de pago
                    getEstadoPagoClasses(estado) {
                        switch (estado?.toLowerCase()) {
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

                    // Seleccionar mes para pago (PASO 1 - mostrar QR)
                    seleccionarMes(mes) {
                        // Validar que haya un vehículo seleccionado con plan
                        if (!this.vehiculoSeleccionado || !this.vehiculoSeleccionado.subscription || !this.vehiculoSeleccionado
                            .subscription.plan) {
                            this.mostrarErrorSinPlan();
                            return;
                        }

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
                            this.mostrarError('Este mes ya está pagado');
                            return;
                        }

                        // Calcular el monto basado en el plan del vehículo
                        let monto = 150.00; // Monto por defecto

                        if (this.vehiculoSeleccionado && this.vehiculoSeleccionado.subscription && this.vehiculoSeleccionado
                            .subscription.plan) {
                            const plan = this.vehiculoSeleccionado.subscription.plan;
                            monto = parseFloat(plan.price) || 150.00;

                            // Si es un mes adelantado, aplicar descuento si lo tiene
                            const currentDate = new Date();
                            const currentMonth = currentDate.getMonth() + 1;
                            const currentYear = currentDate.getFullYear();

                            if (this.currentYear > currentYear || (this.currentYear === currentYear && mes >
                                    currentMonth)) {
                                // Es un pago adelantado, aplicar descuento si existe
                                if (plan.discount_percentage && plan.discount_percentage > 0) {
                                    const descuento = (monto * plan.discount_percentage) / 100;
                                    monto = monto - descuento;
                                }
                            }
                        }

                        // Configurar datos del pago
                        this.pagoData = {
                            mes: mes,
                            year: this.currentYear,
                            monto: monto,
                            vehiculo_id: this.vehiculoSeleccionado.id
                        };

                        // Reiniciar estado del modal
                        this.pasoActual = 1;
                        this.pagoVerificado = false;
                        this.verificandoPago = false;
                        this.confirmandoPago = false;
                        this.notificacionId = null;
                        this.errorVerificacion = null;
                        this.formVerificacion = {
                            titular_yape: '',
                            codigo_seguridad: '',
                            desde_yape: false
                        };

                        // Abrir modal
                        this.modalOpen = true;
                    },

                    // Ir al siguiente paso (de QR a verificación)
                    siguientePaso() {
                        this.pasoActual = 2;
                    },

                    // Ir al paso anterior (de verificación a QR)
                    pasoAnterior() {
                        this.pasoActual = 1;
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

                        this.verificandoPago = true;
                        this.errorVerificacion = null; // Limpiar errores anteriores

                        // Mostrar loading con SweetAlert2
                        Swal.fire({
                            title: 'Verificando Pago',
                            text: 'Por favor espere mientras verificamos su pago con Yape...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            const response = await fetch(this.API_URLS.verificarYape, {
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
                                    desde_yape: this.formVerificacion.desde_yape
                                })
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                this.pagoVerificado = true;
                                this.notificacionId = data.data.notification_id; // Capturar desde data.data
                                this.errorVerificacion = null;
                                
                                // Cerrar loading y mostrar éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Pago Verificado!',
                                    text: 'Su pago ha sido verificado correctamente. Ahora puede confirmar el pago.',
                                    confirmButtonText: 'Continuar',
                                    confirmButtonColor: '#059669',
                                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                                });
                            } else {
                                // Manejar errores de validación
                                if (data.errors) {
                                    // Si hay errores de validación específicos, mostrar el primero
                                    const firstError = Object.values(data.errors)[0];
                                    this.errorVerificacion = Array.isArray(firstError) ? firstError[0] : firstError;
                                } else {
                                    this.errorVerificacion = data.message || 'No se pudo verificar el pago';
                                }
                                this.pagoVerificado = false;
                                
                                // Cerrar loading y mostrar error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Verificación',
                                    text: this.errorVerificacion,
                                    confirmButtonText: 'Reintentar',
                                    confirmButtonColor: '#dc2626',
                                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                                });
                            }
                        } catch (error) {
                            console.error('Error al verificar pago:', error);
                            this.errorVerificacion = 'Error de conexión al verificar el pago';
                            this.pagoVerificado = false;
                            
                            // Cerrar loading y mostrar error de conexión
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudo conectar con el servidor. Por favor, verifique su conexión e intente nuevamente.',
                                confirmButtonText: 'Reintentar',
                                confirmButtonColor: '#dc2626',
                                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                            });
                        } finally {
                            this.verificandoPago = false;
                        }
                    },

                    // Confirmar el pago
                    async confirmarPago() {
                        this.confirmandoPago = true;
                        this.errorVerificacion = null; // Limpiar errores

                        // Mostrar loading con SweetAlert2
                        Swal.fire({
                            title: 'Confirmando Pago',
                            text: 'Por favor espere mientras procesamos su pago...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            // Preparar los datos del pago de manera similar a handleSavePago
                            const pagoData = {
                                vehiculoId: this.pagoData.vehiculo_id,
                                mes: this.pagoData.mes,
                                year: this.pagoData.year,
                                monto: this.pagoData.monto,
                                fecha: new Date().toISOString().split('T')[0], // Fecha actual en formato YYYY-MM-DD
                                descuento: 0,
                                moneda: 'PEN',
                                tipo: 'yape',
                                color: '#10b981', // Verde para pagos completados
                                metodo_pago: 'yape',
                                notification_id: this.notificacionId,
                                titular_yape: this.formVerificacion.titular_yape
                            };

                            const response = await fetch(this.API_URLS.confirmarPago, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(pagoData)
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Cerrar modal
                                this.modalOpen = false;

                                // Recargar los datos del vehículo para actualizar el calendario
                                await this.cargarPagosVehiculo(this.vehiculoSeleccionado.id);

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
                                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                                });
                            } else {
                                // Manejar errores de validación
                                if (data.errors) {
                                    const firstError = Object.values(data.errors)[0];
                                    this.errorVerificacion = Array.isArray(firstError) ? firstError[0] : firstError;
                                } else {
                                    this.errorVerificacion = data.message || 'Error al confirmar el pago';
                                }
                                
                                // Mostrar error con SweetAlert2
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al Confirmar',
                                    text: this.errorVerificacion,
                                    confirmButtonText: 'Reintentar',
                                    confirmButtonColor: '#dc2626',
                                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                                });
                            }
                        } catch (error) {
                            console.error('Error al confirmar pago:', error);
                            this.errorVerificacion = 'Error de conexión al confirmar el pago';
                            
                            // Mostrar error de conexión con SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudo conectar con el servidor para confirmar el pago. Por favor, intente nuevamente.',
                                confirmButtonText: 'Reintentar',
                                confirmButtonColor: '#dc2626',
                                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                            });
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
                            desde_yape: false
                        };
                    },

                    // Mostrar error cuando no hay plan
                    mostrarErrorSinPlan() {
                        // Usar SweetAlert2 para mostrar error de plan
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin Plan Asignado',
                            html: `
                                <div class="text-left">
                                    <p class="mb-3">Este vehículo no tiene un plan asignado.</p>
                                    <p class="text-sm text-gray-600">Por favor, comuníquese con el administrador del sistema para asignar un plan antes de poder realizar pagos.</p>
                                </div>
                            `,
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc2626',
                            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                            customClass: {
                                popup: 'swal2-popup-custom'
                            }
                        });

                        // Auto-remover la selección del vehículo después de un momento
                        setTimeout(() => {
                            document.querySelectorAll('.vehiculo-card').forEach(card => {
                                card.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                                card.classList.add('border-gray-200', 'dark:border-gray-600');
                            });
                        }, 100);
                    },

                    // Mostrar error
                    mostrarError(mensaje) {
                        // Usar SweetAlert2 para mostrar errores
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: mensaje,
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc2626',
                            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                        });
                        
                        // También asignar al estado para mostrar en el modal si está abierto
                        this.errorVerificacion = mensaje;
                    },

                    // Mostrar éxito
                    mostrarExito(mensaje) {
                        // Usar SweetAlert2 para mostrar éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: mensaje,
                            confirmButtonText: 'Genial',
                            confirmButtonColor: '#059669',
                            timer: 3000,
                            timerProgressBar: true,
                            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
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
