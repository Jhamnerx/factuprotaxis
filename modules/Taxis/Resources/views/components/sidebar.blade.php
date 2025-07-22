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
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.dashboard*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.dashboard*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
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

                    <!-- Vehículos -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.vehiculos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.vehiculos.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.vehiculos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 2A1.5 1.5 0 0 0 1 3.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-4.185c.025-.012.05-.023.076-.032V3.5A1.5 1.5 0 0 0 13.5 2h-11zM15 4.057L8.755 8.024a1.5 1.5 0 0 1-1.51 0L1 4.057V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V4.057z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Vehículos</span>
                            </div>
                        </a>
                    </li>

                    <!-- Pagos -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.pagos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.pagos.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.pagos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
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

                    <!-- Contratos -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.contratos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.contratos.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.contratos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM2 2h12v12H2V2z" />
                                    <path d="M4 4h8v1H4V4zm0 3h8v1H4V7zm0 3h5v1H4v-1z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Contratos</span>
                            </div>
                        </a>
                    </li>

                    <!-- Solicitudes -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.solicitudes*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.solicitudes.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.solicitudes*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M13.95.879a3 3 0 0 0-4.243 0L1.293 9.293a1 1 0 0 0-.274.51l-1 5a1 1 0 0 0 1.177 1.177l5-1a1 1 0 0 0 .511-.273l8.414-8.414a3 3 0 0 0 0-4.242L13.95.879ZM11.12 2.293a1 1 0 0 1 1.414 0l1.172 1.172a1 1 0 0 1 0 1.414l-8.2 8.2-3.232.646.646-3.232 8.2-8.2Z" />
                                    <path d="M10 14a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2h-5Z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Solicitudes</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Documentación y Permisos -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Documentación</span>
                </h3>
                <ul class="mt-3">
                    <!-- Condiciones -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.condiciones*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.condiciones.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.condiciones*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Condiciones</span>
                            </div>
                        </a>
                    </li>

                    <!-- Constancias -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.constancias*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.constancias.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.constancias*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 2h8v12H4V2z" />
                                    <path d="M6 4v1h4V4H6zm0 3v1h4V7H6zm0 3v1h2v-1H6z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Constancias</span>
                            </div>
                        </a>
                    </li>

                    <!-- Permisos -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.permisos*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.permisos.index', [], false) }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.permisos*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                </svg>
                                <span
                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Permisos</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Configuración -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Cuenta</span>
                </h3>
                <ul class="mt-3">
                    <!-- Perfil -->
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ request()->routeIs('taxis.profile*') ? 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                        <a class="block text-gray-800 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white truncate transition"
                            href="{{ route('taxis.profile') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current {{ request()->routeIs('taxis.profile*') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}"
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
