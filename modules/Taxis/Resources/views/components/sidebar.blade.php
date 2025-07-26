<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 shadow-xs rounded-r-2xl p-4 transition-all duration-200 ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false" x-cloak="lg">

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="{{ route('taxis.dashboard') }}">
                <div
                    class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Links -->
        @if (session('taxis_authenticated'))
            @php
                $user = session('taxis_user');
                $userType = $user['type'] ?? null;
            @endphp
            <div class="space-y-8">
                <!-- Sistema Taxis -->
                <div>
                    <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                        <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                            aria-hidden="true">•••</span>
                        <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Sistema Taxis</span>
                    </h3>
                    <ul class="mt-3">
                        <!-- Dashboard -->
                        <li
                            class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.dashboard*', 'taxis.conductor.dashboard*', 'taxis.propietario.dashboard*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                            @if ($userType === 'conductor')
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.conductor.dashboard') }}">
                                @elseif($userType === 'propietario')
                                    <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                        href="{{ route('taxis.propietario.dashboard') }}">
                                    @else
                                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                            href="{{ route('taxis.dashboard') }}">
                            @endif
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.dashboard*', 'taxis.conductor.dashboard*', 'taxis.propietario.dashboard*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path
                                        d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                            </a>
                        </li>

                        @if ($userType === 'conductor')
                            <!-- Mi Vehículo -->
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.conductor.vehiculo*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.conductor.vehiculo') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.conductor.vehiculo*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 2A1.5 1.5 0 0 0 1 3.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-4.185c.025-.012.05-.023.076-.032V3.5A1.5 1.5 0 0 0 13.5 2h-11zM15 4.057L8.755 8.024a1.5 1.5 0 0 1-1.51 0L1 4.057V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V4.057z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Mi
                                            Vehículo</span>
                                    </div>
                                </a>
                            </li>

                            <!-- Permisos -->
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.conductor.permisos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.conductor.permisos') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.conductor.permisos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h8l5-5V1c0-.6-.4-1-1-1zM3 2h10v8H9v4H3V2z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Permisos</span>
                                    </div>
                                </a>
                            </li>

                            <!-- Servicios -->
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.conductor.servicios*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.conductor.servicios') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.conductor.servicios*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M10.5 0h-9A1.5 1.5 0 000 1.5v9A1.5 1.5 0 001.5 12h9a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0010.5 0zM10 7L8.207 5.207l-3 3-1.414-1.414 3-3L5 2h5v5z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Servicios</span>
                                    </div>
                                </a>
                            </li>
                        @elseif($userType === 'propietario')
                            <!-- Vehículos -->
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.propietario.vehiculos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.propietario.vehiculos') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.propietario.vehiculos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 2A1.5 1.5 0 0 0 1 3.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-4.185c.025-.012.05-.023.076-.032V3.5A1.5 1.5 0 0 0 13.5 2h-11zM15 4.057L8.755 8.024a1.5 1.5 0 0 1-1.51 0L1 4.057V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V4.057z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Mis
                                            Vehículos</span>
                                    </div>
                                </a>
                            </li>

                            <!-- Conductores -->
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.propietario.conductores*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.propietario.conductores') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.propietario.conductores*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M6 0a6 6 0 1 0 0 12A6 6 0 0 0 6 0zM6 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm0 8a4 4 0 0 1-3.464-2A3 3 0 0 1 6 6a3 3 0 0 1 3.464 2A4 4 0 0 1 6 10z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Mis
                                            Conductores</span>
                                    </div>
                                </a>
                            </li>

                            <!-- Documentos -->
                            <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 relative {{ request()->routeIs('taxis.propietario.contratos*', 'taxis.propietario.solicitudes*', 'taxis.propietario.constancias*', 'taxis.propietario.permisos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}"
                                x-data="{ open: {{ request()->routeIs('taxis.propietario.contratos*', 'taxis.propietario.solicitudes*', 'taxis.propietario.constancias*', 'taxis.propietario.permisos*') ? 'true' : 'false' }} }">
                                <button
                                    class="w-full text-left text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    @click="open = !open; sidebarExpanded = true"
                                    :class="open ? 'text-violet-500' : ''">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current"
                                                :class="open ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500'"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h8l5-5V1c0-.6-.4-1-1-1zM3 2h10v8H9v4H3V2z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Documentos</span>
                                        </div>
                                        <svg class="w-3 h-3 fill-current text-gray-400 dark:text-gray-500 transition-transform duration-200"
                                            :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </button>
                                <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                    <ul class="pl-8 mt-1" x-show="open"
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2">
                                        <li class="mb-1 last:mb-0">
                                            <a class="block {{ request()->routeIs('taxis.propietario.contratos*') ? 'text-violet-500' : 'text-gray-600 dark:text-gray-400' }} hover:text-gray-900 dark:hover:text-white py-2 text-sm transition truncate"
                                                href="{{ route('taxis.propietario.contratos') }}">
                                                <span
                                                    class="text-sm {{ request()->routeIs('taxis.propietario.contratos*') ? 'font-medium' : '' }} lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Contratos</span>
                                            </a>
                                        </li>
                                        <li class="mb-1 last:mb-0">
                                            <a class="block {{ request()->routeIs('taxis.propietario.solicitudes*') ? 'text-violet-500' : 'text-gray-600 dark:text-gray-400' }} hover:text-gray-900 dark:hover:text-white py-2 text-sm transition truncate"
                                                href="{{ route('taxis.propietario.solicitudes') }}">
                                                <span
                                                    class="text-sm {{ request()->routeIs('taxis.propietario.solicitudes*') ? 'font-medium' : '' }} lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Solicitudes</span>
                                            </a>
                                        </li>
                                        <li class="mb-1 last:mb-0">
                                            <a class="block {{ request()->routeIs('taxis.propietario.constancias*') ? 'text-violet-500' : 'text-gray-600 dark:text-gray-400' }} hover:text-gray-900 dark:hover:text-white py-2 text-sm transition truncate"
                                                href="{{ route('taxis.propietario.constancias') }}">
                                                <span
                                                    class="text-sm {{ request()->routeIs('taxis.propietario.constancias*') ? 'font-medium' : '' }} lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Constancias</span>
                                            </a>
                                        </li>
                                        <li class="mb-1 last:mb-0">
                                            <a class="block {{ request()->routeIs('taxis.propietario.permisos*') ? 'text-violet-500' : 'text-gray-600 dark:text-gray-400' }} hover:text-gray-900 dark:hover:text-white py-2 text-sm transition truncate"
                                                href="{{ route('taxis.propietario.permisos') }}">
                                                <span
                                                    class="text-sm {{ request()->routeIs('taxis.propietario.permisos*') ? 'font-medium' : '' }} lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Permisos</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Servicios -->
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.propietario.servicios*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.propietario.servicios') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.propietario.servicios*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M10.5 0h-9A1.5 1.5 0 000 1.5v9A1.5 1.5 0 001.5 12h9a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0010.5 0zM10 7L8.207 5.207l-3 3-1.414-1.414 3-3L5 2h5v5z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Servicios</span>
                                    </div>
                                </a>
                            </li>
                        @endif

                        <!-- Pagos (común para ambos tipos) -->
                        <li
                            class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.conductor.pagos*', 'taxis.propietario.pagos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                            @if ($userType === 'conductor')
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.conductor.pagos') }}">
                                @elseif($userType === 'propietario')
                                    <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                        href="{{ route('taxis.propietario.pagos') }}">
                            @endif
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.conductor.pagos*', 'taxis.propietario.pagos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm0 2h12v2H2V4zm0 4h12v4H2V8z" />
                                    <path d="M3 10h2v1H3v-1z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Pagos</span>
                            </div>
                            </a>
                        </li>

                        <!-- Mi Perfil -->
                        <li
                            class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.conductor.perfil*', 'taxis.propietario.perfil*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                            @if ($userType === 'conductor')
                                <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                    href="{{ route('taxis.conductor.perfil') }}">
                                @elseif($userType === 'propietario')
                                    <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                        href="{{ route('taxis.propietario.perfil') }}">
                            @endif
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.conductor.perfil*', 'taxis.propietario.perfil*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M6 0a6 6 0 1 0 0 12A6 6 0 0 0 6 0zM6 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm0 8a4 4 0 0 1-3.464-2A3 3 0 0 1 6 6a3 3 0 0 1 3.464 2A4 4 0 0 1 6 10z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Mi
                                    Perfil</span>
                            </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            <!-- Usuario no autenticado -->
            <div class="space-y-8">
                <div>
                    <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                        <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Acceso</span>
                    </h3>
                    <ul class="mt-3">
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0">
                            <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                                href="{{ route('taxis.login') }}">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M9 6V4a4 4 0 0 0-8 0v2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zM2 4a2 2 0 0 1 4 0v2H2V4z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Iniciar
                                        Sesión</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button
                    class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>
