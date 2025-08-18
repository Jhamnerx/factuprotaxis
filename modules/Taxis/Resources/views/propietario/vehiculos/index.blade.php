@extends('taxis::layouts.master')

@section('title', 'Mis Vehículos - Propietario')



@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Mis Vehículos</h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 dark:text-gray-400">Gestiona todos tus vehículos registrados</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-400/10 dark:text-violet-400">
                        {{ $vehiculos->total() }} vehículo{{ $vehiculos->total() != 1 ? 's' : '' }}
                        @if (request()->hasAny(['estado', 'marca_id', 'conductor']))
                            filtrado{{ $vehiculos->total() != 1 ? 's' : '' }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back to Dashboard button -->
                <a href="{{ route('taxis.propietario.dashboard') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" transform="rotate(180 8 8)" />
                    </svg>
                    <span class="max-xs:sr-only">Volver al Dashboard</span>
                </a>
            </div>

        </div>

        <!-- Filters -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <form method="GET" action="{{ route('taxis.propietario.vehiculos') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                            <select name="estado" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="ACTIVO" {{ request('estado') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                <option value="DE BAJA" {{ request('estado') == 'DE BAJA' ? 'selected' : '' }}>De Baja
                                </option>
                                <option value="DE BAJA POR PAGO"
                                    {{ request('estado') == 'DE BAJA POR PAGO' ? 'selected' : '' }}>De Baja por Pago
                                </option>
                                <option value="SUSPECION POR TRABAJO"
                                    {{ request('estado') == 'SUSPECION POR TRABAJO' ? 'selected' : '' }}>Suspención por
                                    Trabajo</option>
                                <option value="RETIRO" {{ request('estado') == 'RETIRO' ? 'selected' : '' }}>Retiro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Marca</label>
                            <select name="marca_id" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todas las marcas</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}"
                                        {{ request('marca_id') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Conductor</label>
                            <select name="conductor" class="form-select w-full" onchange="this.form.submit()">
                                <option value="">Todos los conductores</option>
                                <option value="sin_conductor"
                                    {{ request('conductor') == 'sin_conductor' ? 'selected' : '' }}>Sin conductor</option>
                                @foreach ($conductores as $conductor)
                                    <option value="{{ $conductor->id }}"
                                        {{ request('conductor') == $conductor->id ? 'selected' : '' }}>
                                        {{ $conductor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white flex-1">
                                Filtrar
                            </button>
                            @if (request()->hasAny(['estado', 'marca_id', 'conductor']))
                                <a href="{{ route('taxis.propietario.vehiculos') }}"
                                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if (request()->hasAny(['estado', 'marca_id', 'conductor']))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                            </svg>
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-100">Filtros aplicados:</span>
                            <div class="flex items-center space-x-2">
                                @if (request('estado'))
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                        Estado:
                                        {{ request('estado') == 'ACTIVO' ? 'Activo' : (request('estado') == 'DE BAJA' ? 'De Baja' : (request('estado') == 'DE BAJA POR PAGO' ? 'De Baja por Pago' : (request('estado') == 'SUSPECION POR TRABAJO' ? 'Suspención por Trabajo' : 'Retiro'))) }}
                                    </span>
                                @endif
                                @if (request('marca_id') && isset($marcas))
                                    @php
                                        $marcaSeleccionada = $marcas->find(request('marca_id'));
                                    @endphp
                                    @if ($marcaSeleccionada)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                            Marca: {{ $marcaSeleccionada->nombre }}
                                        </span>
                                    @endif
                                @endif
                                @if (request('conductor'))
                                    @if (request('conductor') == 'sin_conductor')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                            Conductor: Sin conductor
                                        </span>
                                    @elseif(isset($conductores))
                                        @php
                                            $conductorSeleccionado = $conductores->find(request('conductor'));
                                        @endphp
                                        @if ($conductorSeleccionado)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                                Conductor: {{ $conductorSeleccionado->name }}
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('taxis.propietario.vehiculos') }}"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Vehicles Grid -->
        @if ($vehiculos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($vehiculos as $vehiculo)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/60 hover:shadow-lg transition-shadow duration-200">

                        <!-- Vehicle Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $vehiculo->marca->nombre ?? 'Marca' }}
                                    {{ $vehiculo->modelo->nombre ?? 'Modelo' }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $vehiculo->placa }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @switch($vehiculo->estado)
                                    @case('ACTIVO')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-400/10 dark:text-emerald-400">
                                            Activo
                                        </span>
                                    @break

                                    @case('DE BAJA')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                            De Baja
                                        </span>
                                    @break

                                    @case('DE BAJA POR PAGO')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-400/10 dark:text-red-400">
                                            De Baja por Pago
                                        </span>
                                    @break

                                    @case('SUSPECION POR TRABAJO')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-400/10 dark:text-yellow-400">
                                            Suspención por Trabajo
                                        </span>
                                    @break

                                    @case('RETIRO')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-400/10 dark:text-orange-400">
                                            Retiro
                                        </span>
                                    @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-400/10 dark:text-gray-400">
                                            {{ ucfirst($vehiculo->estado) }}
                                        </span>
                                @endswitch
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Año:</span>
                                <span class="text-gray-800 dark:text-gray-100 ml-1">{{ $vehiculo->year ?? 'N/A' }}</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Conductor:</span>
                                @if ($vehiculo->conductor)
                                    <span
                                        class="text-gray-800 dark:text-gray-100 ml-1">{{ $vehiculo->conductor->name }}</span>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400 ml-1">Sin asignar</span>
                                @endif
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Número Interno:</span>
                                <span
                                    class="text-gray-800 dark:text-gray-100 ml-1">{{ $vehiculo->numero_interno ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('taxis.propietario.vehiculos.show', $vehiculo->id) }}"
                                class="flex-1 btn-sm bg-violet-500 hover:bg-violet-600 text-white text-center">
                                Ver Detalles
                            </a>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="btn-sm border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                    <div class="py-1">
                                        <button @click="open = false" onclick="openConductorModal({{ $vehiculo->id }})"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-200">
                                            Cambiar conductor
                                        </button>
                                        @if ($vehiculo->conductor_id)
                                            <button @click="open = false" onclick="quitarConductor({{ $vehiculo->id }})"
                                                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-red-600 dark:text-red-400">
                                                Quitar conductor
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($vehiculos->hasPages())
                <div class="mt-8">
                    <div class="flex justify-center">
                        {{ $vehiculos->links('taxis::components.pagination') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    @if (request()->hasAny(['estado', 'marca_id', 'conductor']))
                        <!-- Filtered empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron vehículos
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            No hay vehículos que coincidan con los filtros aplicados. Intenta ajustar los criterios de
                            búsqueda.
                        </p>
                        <a href="{{ route('taxis.propietario.vehiculos') }}"
                            class="inline-flex items-center px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar filtros
                        </a>
                    @else
                        <!-- Default empty state -->
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            No tienes vehículos registrados
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Actualmente no tienes vehículos asignados. Los vehículos deben ser gestionados por el
                            administrador
                            del sistema.
                        </p>
                    @endif
                </div>
            </div>
        @endif

    </div>

    <!-- Modal para cambiar conductor -->
    <div id="modal-cambiar-conductor"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/30 hidden transition-opacity">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md relative">
            <button
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-xl font-bold w-8 h-8 flex items-center justify-center cursor-pointer transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                onclick="closeConductorModal()">&times;</button>
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Cambiar Conductor</h3>
            <form id="form-cambiar-conductor">
                <input type="hidden" id="vehiculo_id_modal" name="vehiculo_id">
                <div class="mb-4">
                    <label for="dni_conductor" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">DNI
                        del conductor</label>
                    <input type="text" id="dni_conductor" name="dni_conductor" maxlength="15"
                        placeholder="Ingrese el DNI del conductor"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-gray-100"
                        required>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ingrese el DNI y presione Tab o haga clic
                        fuera del campo para buscar</p>
                </div>
                <div id="conductor-info" class="mb-4 text-sm min-h-[24px]"></div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition-colors cursor-pointer"
                        onclick="closeConductorModal()">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded transition-colors cursor-pointer">Guardar
                        Cambio</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Definir funciones globales para los modales
        window.openConductorModal = function(vehiculoId) {
            document.getElementById('vehiculo_id_modal').value = vehiculoId;
            document.getElementById('dni_conductor').value = '';
            document.getElementById('conductor-info').innerHTML = '';
            document.getElementById('modal-cambiar-conductor').classList.remove('hidden');
        }

        window.closeConductorModal = function() {
            document.getElementById('modal-cambiar-conductor').classList.add('hidden');
        }

        // Función para quitar conductor
        window.quitarConductor = function(vehiculoId) {
            if (typeof Swal === 'undefined') {
                alert('Por favor espere a que la página termine de cargar');
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Se quitará el conductor asignado a este vehículo',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, quitar conductor',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/taxis/propietario/quitar-conductor', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                vehiculo_id: vehiculoId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Removido!', data.message, 'success').then(() => window.location
                                    .reload());
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Error al comunicarse con el servidor', 'error');
                        });
                }
            });
        }

        // Buscar conductor al ingresar DNI
        window.addEventListener('DOMContentLoaded', function() {
            const dniInput = document.getElementById('dni_conductor');
            if (dniInput) {
                dniInput.addEventListener('blur', function() {
                    const dni = this.value.trim();
                    if (dni.length >= 6) {
                        fetch(`/taxis/propietario/buscar-conductor/${dni}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    let info =
                                        `<span class='text-green-600'>${data.conductor.name} (${data.conductor.number})</span>`;
                                    if (data.conductor.has_vehicle && data.conductor.current_vehicle) {
                                        info +=
                                            `<br><span class='text-yellow-600 text-xs'>Ya tiene vehículo: ${data.conductor.current_vehicle}</span>`;
                                    }
                                    document.getElementById('conductor-info').innerHTML = info;
                                } else {
                                    document.getElementById('conductor-info').innerHTML =
                                        `<span class='text-red-600'>${data.message}</span>`;
                                }
                            })
                            .catch(() => {
                                document.getElementById('conductor-info').innerHTML =
                                    `<span class='text-red-600'>Error al buscar conductor</span>`;
                            });
                    }
                });
            }
        });

        // Guardar cambio de conductor
        window.addEventListener('DOMContentLoaded', function() {
            const formCambiar = document.getElementById('form-cambiar-conductor');
            if (formCambiar) {
                formCambiar.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const vehiculoId = document.getElementById('vehiculo_id_modal').value;
                    const dni = document.getElementById('dni_conductor').value.trim();
                    fetch(`/taxis/propietario/buscar-conductor/${dni}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                fetch(`/taxis/propietario/cambiar-conductor`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content')
                                        },
                                        body: JSON.stringify({
                                            vehiculo_id: vehiculoId,
                                            conductor_id: data.conductor.id
                                        })
                                    })
                                    .then(res => res.json())
                                    .then(resp => {
                                        if (resp.success) {
                                            Swal.fire('¡Actualizado!', resp.message, 'success')
                                                .then(() => {
                                                    closeConductorModal();
                                                    window.location.reload();
                                                });
                                        } else {
                                            Swal.fire('Error', resp.message, 'error');
                                        }
                                    })
                                    .catch(err => {
                                        Swal.fire('Error', 'Error al comunicarse con el servidor',
                                            'error');
                                    });
                            } else {
                                Swal.fire('No encontrado', data.message ||
                                    'El conductor no existe. Solicite al administrador registrar el conductor.',
                                    'warning');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Error al buscar el conductor', 'error');
                        });
                });
            }
        });
    </script>
@endpush
