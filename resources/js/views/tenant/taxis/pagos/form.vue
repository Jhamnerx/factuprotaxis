<template>
    <div
        class="container-fluid p-3 dark:bg-gray-900 min-h-screen"
        v-loading="loading_submit"
        style="font-family: 'Segoe UI', Roboto, Arial, sans-serif;"
    >
        <!-- Encabezado -->
        <div class="row mb-3">
            <div class="col-12">
                <div
                    class="rounded-lg bg-white dark:bg-gray-800 p-3 d-flex justify-content-between align-items-center shadow-sm"
                >
                    <div class="d-flex align-items-center">
                        <div>
                            <h2
                                class="mb-0 font-bold text-gray-800 dark:text-gray-100"
                                style="font-size: 1.1rem;"
                            >
                                Gestión de Dispositivos
                            </h2>
                            <p
                                class="text-gray-600 dark:text-gray-400 mb-0"
                                style="font-size: 0.8rem;"
                            >
                                Aquí puedes administrar tus vehículos y sus
                                pagos
                            </p>
                        </div>
                    </div>
                    <div>
                        <button
                            class="btn btn-primary btn-sm d-inline-flex align-items-center"
                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                            @click="openModalCreateUnidad"
                        >
                            <svg
                                style="width: 12px; height: 12px; margin-right: 0.25rem;"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                ></path>
                            </svg>
                            Añadir Dispositivos
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Lista de Vehículos -->
            <div
                class="col-12 col-md-3 md:h-screen md:overflow-y-auto border rounded-lg dark:border-gray-700 p-3 bg-white dark:bg-gray-800 mb-3 md:mb-0"
                style="max-height: calc(100vh - 180px);"
            >
                <div class="mb-2">
                    <h3
                        class="font-bold text-gray-800 dark:text-gray-100 mb-1"
                        style="font-size: 0.95rem;"
                    >
                        Dispositivos
                    </h3>
                    <p
                        class="text-gray-600 dark:text-gray-400"
                        style="font-size: 0.75rem;"
                    >
                        Seleccione un dispositivo para ver sus pagos
                    </p>
                </div>

                <vehiculos-pago-data-table :resource="resource">
                    <div
                        slot-scope="{ row }"
                        class="position-relative mb-3 border rounded shadow-sm overflow-hidden transition-hover vehicle-card"
                        @click="setSelectedVehicle(row.id)"
                        :class="{
                            'border-primary': selectedVehicleId === row.id,
                            'border-gray-200 dark:border-gray-700':
                                selectedVehicleId !== row.id
                        }"
                        style="border-radius: 6px; margin-bottom: 10px !important; cursor: pointer;"
                    >
                        <!-- Franja de color según selección -->
                        <div
                            :class="{
                                'bg-primary': selectedVehicleId === row.id,
                                'bg-secondary': selectedVehicleId !== row.id
                            }"
                            class="position-absolute top-0 start-0 h-100 ribbon"
                        ></div>

                        <!-- Indicador de selección -->
                        <div
                            v-if="selectedVehicleId === row.id"
                            class="position-absolute end-0 top-0"
                            style="padding: 6px; margin-right: 6px; margin-top: 6px;"
                        >
                            <span class="badge rounded-circle bg-primary p-1">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="10"
                                    height="10"
                                    fill="currentColor"
                                    class="bi bi-check"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"
                                    />
                                </svg>
                            </span>
                        </div>

                        <!-- Contenido de la tarjeta con padding-left adicional para la franja -->
                        <div
                            class="d-flex flex-column p-2"
                            style="padding-left: 18px; padding-top: 10px; padding-bottom: 8px;"
                        >
                            <!-- Encabezado con placa destacada -->
                            <div
                                class="d-flex justify-content-between align-items-center mb-2"
                            >
                                <h5
                                    class="fw-bold mb-0 text-gray-800 dark:text-gray-100"
                                    style="font-size: 1rem; letter-spacing: 0.5px; padding-left: 2px; margin-top: 4px;"
                                >
                                    {{ row ? row.placa : "" }}
                                </h5>

                                <!-- Ícono de vehículo -->
                                <div class="ms-1">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="14"
                                        height="14"
                                        fill="currentColor"
                                        class="bi bi-car-front text-primary"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M4 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm10 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM4.862 4.276 3.906 6.19a.51.51 0 0 0 .497.731c.91-.073 2.35-.17 3.597-.17 1.247 0 2.688.097 3.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 10.691 4H5.309a.5.5 0 0 0-.447.276Z"
                                        />
                                        <path
                                            d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM4.82 3a1.5 1.5 0 0 0-1.379.91l-.792 1.847a1.8 1.8 0 0 1-.853.904.807.807 0 0 0-.43.564L1.03 8.904a1.5 1.5 0 0 0-.03.294v.413c0 .796.62 1.448 1.408 1.484 1.555.07 3.786.155 5.592.155 1.806 0 4.037-.084 5.592-.155A1.479 1.479 0 0 0 15 9.611v-.413c0-.099-.01-.197-.03-.294l-.335-1.68a.807.807 0 0 0-.43-.563 1.807 1.807 0 0 1-.853-.904l-.792-1.848A1.5 1.5 0 0 0 11.18 3H4.82Z"
                                        />
                                    </svg>
                                </div>
                            </div>

                            <!-- Información del propietario -->
                            <div
                                class="mb-1 text-gray-600 dark:text-gray-400"
                                style="font-size: 0.7rem; padding-left: 4px; margin-top: 2px;"
                            >
                                <strong>Propietario:</strong>
                                {{
                                    row && row.propietario
                                        ? row.propietario.name
                                        : "N/A"
                                }}
                            </div>

                            <!-- Fecha de ingreso -->
                            <div
                                class="mb-2 text-gray-600 dark:text-gray-400"
                                style="font-size: 0.7rem; padding-left: 4px;"
                            >
                                <strong>Fecha de Ingreso:</strong>
                                {{
                                    row && row.fecha_ingreso
                                        ? formatDate(row.fecha_ingreso)
                                        : ""
                                }}
                            </div>

                            <!-- Sección de badges -->
                            <div
                                class="d-flex flex-wrap gap-2"
                                style="padding-left: 4px; margin-top: 2px;"
                            >
                                <!-- Indicador de estado de suscripción -->
                                <span
                                    v-if="row && row.subscription"
                                    class="badge bg-success text-white"
                                    style="font-size: 0.65rem; padding: 0.15rem 0.35rem; margin-right: 3px;"
                                >
                                    Con suscripción
                                </span>
                                <span
                                    v-else
                                    class="badge bg-danger text-white"
                                    style="font-size: 0.65rem; padding: 0.15rem 0.35rem; margin-right: 3px;"
                                >
                                    Sin suscripción
                                </span>

                                <!-- Plan Socio -->
                                <span
                                    v-if="
                                        row &&
                                            row.subscription &&
                                            row.subscription.plan &&
                                            row.subscription.plan.is_socio
                                    "
                                    class="badge bg-warning text-dark"
                                    style="font-size: 0.65rem; padding: 0.15rem 0.35rem; margin-right: 3px;"
                                >
                                    Plan Socio Por:
                                    {{ row.subscription.plan.invoice_period }}
                                    {{
                                        row.subscription.plan
                                            .invoice_interval == "year"
                                            ? " Años"
                                            : " Meses"
                                    }}
                                </span>

                                <!-- Plan Indeterminado -->
                                <span
                                    v-if="
                                        row &&
                                            row.subscription &&
                                            row.subscription.plan &&
                                            row.subscription.plan.type ===
                                                'indeterminate'
                                    "
                                    class="badge bg-success text-white"
                                    style="font-size: 0.65rem; padding: 0.15rem 0.35rem; margin-right: 3px;"
                                >
                                    Plan Indeterminado
                                </span>
                            </div>
                        </div>
                    </div>
                </vehiculos-pago-data-table>
            </div>

            <!-- Calendario de Pagos -->
            <div class="col-12 col-md-9 mt-4 md:mt-0">
                <div class="mb-3">
                    <div class="card border rounded-lg shadow-sm">
                        <div class="card-body p-3">
                            <div
                                class="d-flex align-items-center justify-content-between mb-2"
                            >
                                <h5
                                    class="card-title font-bold mb-0 text-gray-800 dark:text-gray-100"
                                    style="font-size: 0.9rem;"
                                >
                                    Modo de Selección
                                </h5>
                                <button
                                    @click="toggleMultipleSelection"
                                    :class="[
                                        'btn btn-sm d-flex align-items-center gap-2 shadow-sm',
                                        is_multiple
                                            ? 'btn-primary'
                                            : 'btn-outline-secondary'
                                    ]"
                                    style="font-size: 0.75rem; padding: 0.35rem 0.65rem;"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="14"
                                        height="14"
                                        fill="currentColor"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"
                                        />
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"
                                        />
                                    </svg>
                                    <span v-if="is_multiple"
                                        >Desactivar selección múltiple</span
                                    >
                                    <span v-else
                                        >Activar selección múltiple</span
                                    >
                                </button>
                            </div>
                            <div
                                class="text-gray-600 dark:text-gray-400"
                                style="font-size: 0.75rem;"
                            >
                                <p
                                    v-if="is_multiple"
                                    class="d-flex align-items-center text-primary mb-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        fill="currentColor"
                                        class="mr-1"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
                                        />
                                    </svg>
                                    <strong
                                        >Modo selección múltiple
                                        activado</strong
                                    >
                                </p>
                                <p v-if="is_multiple">
                                    Haga clic en las celdas del calendario para
                                    seleccionar varios meses. Una vez
                                    seleccionados, aparecerá un botón para pagar
                                    todos juntos.
                                </p>
                                <p v-else>
                                    Active este modo para seleccionar y pagar
                                    varios meses a la vez. Útil para pagos
                                    adelantados o poner al día múltiples meses
                                    pendientes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="calendarContainer">
                    <!-- Mensaje cuando no hay suscripción -->
                    <div
                        v-if="
                            selectedVehicleId &&
                                selectedVehicle &&
                                (!selectedVehicle.subscription_id ||
                                    !selectedVehicle.subscription)
                        "
                        class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 text-center"
                    >
                        <div class="p-5">
                            <svg
                                style="width: 48px; height: 48px;"
                                class="mx-auto mb-4 text-yellow-400 w-12 h-12"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                ></path>
                            </svg>
                            <h3
                                class="mb-3 text-lg font-semibold text-gray-800 dark:text-gray-200"
                            >
                                Sin Suscripción Activa
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                Este vehículo no tiene una suscripción activa.
                                Debe asignarle un plan de suscripción antes de
                                poder gestionar sus pagos.
                            </p>
                            <button
                                class="btn btn-primary d-flex align-items-center mx-auto py-2 px-4"
                                style="font-size: 0.9rem; font-weight: 600;"
                                @click="gotoSubscription"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="width: 16px; height: 16px; margin-right: 0.5rem;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Asignar Plan de Suscripción
                            </button>
                        </div>
                    </div>

                    <!-- Calendario cuando hay suscripción -->
                    <div
                        v-if="
                            selectedVehicleId &&
                                selectedVehicle &&
                                selectedVehicle.subscription_id &&
                                selectedVehicle.subscription
                        "
                        class="bg-white dark:bg-gray-800 rounded-lg shadow p-3"
                    >
                        <h3
                            class="font-bold text-gray-800 dark:text-gray-100"
                            style="font-size: 1rem;"
                        >
                            Calendario de Pagos
                        </h3>

                        <!-- Plan Socio Message -->
                        <div
                            v-if="
                                selectedVehicle &&
                                    selectedVehicle.subscription &&
                                    selectedVehicle.subscription.plan.is_socio
                            "
                            class="p-2 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 rounded mb-3"
                            style="font-size: 0.8rem;"
                        >
                            Este vehículo tiene un Plan Socio Valido Hasta
                            {{
                                selectedVehicle.subscription.ends_at
                                    ? formatDate(
                                          selectedVehicle.subscription.ends_at
                                      )
                                    : "N/A"
                            }}, por lo que no requiere pagos mensuales.
                        </div>

                        <!-- Plan Indeterminado Message -->
                        <div
                            v-else-if="
                                selectedVehicle &&
                                    selectedVehicle.subscription &&
                                    selectedVehicle.subscription.plan.type ===
                                        'indeterminate'
                            "
                            class="p-2 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 rounded mb-3"
                            style="font-size: 0.8rem;"
                        >
                            Este vehículo tiene un Plan Indeterminado, por lo
                            que no requiere pagos mensuales.
                        </div>

                        <!-- Payment Calendar -->
                        <div
                            v-else
                            class="table-responsive"
                            style="max-width: 100%; overflow-x: auto;"
                        >
                            <!-- Leyenda explicativa -->
                            <div
                                class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg mb-3 shadow-sm border border-blue-100 dark:border-blue-800"
                            >
                                <div
                                    class="d-flex align-items-center justify-content-between mb-2"
                                >
                                    <p
                                        class="text-primary font-weight-bold mb-0 small"
                                    >
                                        Guía de Navegación del Calendario
                                    </p>
                                </div>
                                <div class="row">
                                    <!-- Columna izquierda -->
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded p-2 shadow-sm"
                                    >
                                        <h5
                                            class="text-xs font-bold mb-1 text-gray-700 dark:text-gray-300 flex items-center"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                style="width: 16px; height: 16px; margin-right: 0.25rem;"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"
                                                />
                                            </svg>
                                            Acciones del Mouse
                                        </h5>
                                        <ul
                                            class="text-blue-700 dark:text-blue-200 text-xs space-y-1 ml-4 list-disc"
                                        >
                                            <li>
                                                <span class="font-medium"
                                                    >Click izquierdo:</span
                                                >
                                                Registrar nuevo pago para el mes
                                            </li>
                                            <li>
                                                <span class="font-medium"
                                                    >Click derecho:</span
                                                >
                                                Ver información detallada del
                                                pago
                                            </li>
                                            <li>
                                                <span class="font-medium">
                                                    <span
                                                        class="badge bg-success"
                                                        style="font-size: 0.65rem;"
                                                        >TOTAL</span
                                                    >:
                                                </span>
                                                Indica un pago total que no
                                                puede modificarse
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Columna derecha -->
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded p-2 shadow-sm"
                                    >
                                        <h5
                                            class="text-xs font-bold mb-1 text-gray-700 dark:text-gray-300 flex items-center"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                style="width: 16px; height: 16px; margin-right: 0.25rem;"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 6h16M4 12h16M4 18h16"
                                                />
                                            </svg>
                                            Selección Múltiple
                                        </h5>
                                        <div
                                            v-if="is_multiple"
                                            class="flex items-center text-green-600 dark:text-green-400 text-xs mb-1"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                style="width: 12px; height: 12px; margin-right: 0.25rem;"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>
                                            <span class="font-medium"
                                                >Modo selección múltiple
                                                activo</span
                                            >
                                        </div>
                                        <p
                                            class="text-xs text-blue-700 dark:text-blue-200 ml-2"
                                        >
                                            Haga clic en los meses para
                                            seleccionarlos (máx. 12 meses
                                            consecutivos) y luego en "Pagar
                                            Meses Seleccionados" para
                                            procesarlos juntos
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <table
                                class="min-w-full border-collapse"
                                id="calendarTable"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="px-3 py-1.5 border border-gray-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div>Año</div>
                                                <div>Mes</div>
                                            </div>
                                        </th>
                                        <th
                                            v-for="(mes, index) in meses"
                                            :key="index"
                                            class="px-3 py-1.5 border border-gray-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center"
                                        >
                                            <div class="text-sm">
                                                {{ mes }}
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="year in yearsRange" :key="year">
                                        <td
                                            class="border border-gray-300 p-1.5 font-medium text-center text-gray-800 dark:text-gray-100"
                                        >
                                            <div
                                                class="flex items-center justify-center"
                                            >
                                                <div
                                                    style="width: 4px; height: 12px; background-color: #3b82f6; display: inline-block; margin-right: 0.25rem;"
                                                ></div>
                                                <span>{{ year }}</span>
                                            </div>
                                        </td>
                                        <td
                                            v-for="m in 12"
                                            :key="`${year}-${m}`"
                                            class="calendar-cell border border-gray-300 p-1.5 text-center transition text-gray-800 dark:text-gray-100 whitespace-nowrap"
                                            :class="{
                                                'selected-cell':
                                                    is_multiple &&
                                                    selectedMonths.includes(
                                                        `${year}-${m}`
                                                    ),
                                                'opacity-50':
                                                    is_multiple &&
                                                    getMonthlyPayment(year, m) >
                                                        0
                                            }"
                                            :style="{
                                                'background-color': getCellColor(
                                                    year,
                                                    m
                                                ),
                                                cursor: 'pointer'
                                            }"
                                            @click="handleCellClick(year, m)"
                                            @contextmenu.prevent="
                                                openPaymentInfoModal(year, m)
                                            "
                                        >
                                            <div
                                                class="text-sm cell-content-wrapper"
                                                :id="
                                                    `cell-content-${year}-${m}`
                                                "
                                            >
                                                <template
                                                    v-if="
                                                        getMonthlyPayment(
                                                            year,
                                                            m
                                                        ) > 0
                                                    "
                                                >
                                                    <div class="amount">
                                                        {{
                                                            formatCurrency(
                                                                getMonthlyPayment(
                                                                    year,
                                                                    m
                                                                )
                                                            )
                                                        }}
                                                        <span
                                                            class="total-badge"
                                                            v-if="
                                                                hasPayedTotal(
                                                                    year,
                                                                    m
                                                                )
                                                            "
                                                        >
                                                            (Total)
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="text-xs text-gray-500 currency-info"
                                                    >
                                                        {{
                                                            getPaymentCurrency(
                                                                year,
                                                                m
                                                            )
                                                        }}
                                                    </div>
                                                </template>

                                                <!-- Indicador de carga -->
                                                <div
                                                    v-if="
                                                        isLoading &&
                                                            loadingYear ===
                                                                year &&
                                                            loadingMonth === m
                                                    "
                                                    class="inline-block ml-1"
                                                >
                                                    <div
                                                        class="spinner-border spinner-border-sm text-primary"
                                                        style="width: 16px; height: 16px;"
                                                    ></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Botón para abrir el modal avanzado -->
                        <div
                            v-if="selectedMonths.length > 0"
                            class="mt-4 mb-2"
                            id="advancedButtonContainer"
                        >
                            <div
                                class="bg-green-50 dark:bg-green-900 p-3 rounded-lg shadow-sm border border-green-100 dark:border-green-800 mb-2"
                            >
                                <div class="flex items-center mb-1">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        style="width: 16px; height: 16px; margin-right: 0.25rem;"
                                        class="text-success"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <p
                                        class="text-green-700 dark:text-green-200 text-sm font-bold"
                                    >
                                        {{ selectedMonths.length }}
                                        {{
                                            selectedMonths.length === 1
                                                ? "mes seleccionado"
                                                : "meses seleccionados"
                                        }}
                                    </p>
                                </div>
                                <p
                                    class="text-green-600 dark:text-green-300 text-xs"
                                >
                                    Puede procesar el pago de todos estos meses
                                    en una sola operación.
                                </p>
                            </div>

                            <button
                                class="btn btn-success d-inline-flex align-items-center justify-content-center w-100 shadow-lg py-2"
                                @click="openAdvancedModal"
                                :disabled="isAdvancedModalOpen"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="width: 20px; height: 20px; margin-right: 0.5rem;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                <span class="font-bold"
                                    >Pagar {{ selectedMonths.length }}
                                    {{
                                        selectedMonths.length === 1
                                            ? "mes"
                                            : "meses"
                                    }}
                                    seleccionados</span
                                >
                            </button>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <p class="text-lg text-gray-600 dark:text-gray-300">
                            Selecciona un dispositivo para ver el calendario de
                            pagos.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de registro de pago -->
        <register-payment-modal
            :is-open="isPaymentModalOpen"
            :vehiculo="selectedVehicle"
            :year="currentPaymentYear"
            :mes="currentPaymentMonth"
            :selected-months="is_multiple ? selectedMonths : []"
            @close="closePaymentModal"
            @save="handleSavePago"
        />

        <!-- Modal avanzado para pagos múltiples -->
        <advanced-payment-modal
            :is-open="isAdvancedModalOpen"
            :vehiculo="selectedVehicle"
            :selected-months="selectedMonths"
            @close="closeAdvancedModal"
            @save="handleSaveMultiplePagos"
        />

        <!-- Modal de información de pago -->
        <el-dialog
            :visible.sync="isPaymentInfoModalOpen"
            title="Información del Pago"
            width="450px"
            @close="closePaymentInfoModal"
        >
            <div v-if="selectedPaymentInfo">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Periodo:</div>
                        <div class="col-7 font-semibold">
                            {{ getMesNombre(selectedPaymentInfo.month) }}
                            {{ selectedPaymentInfo.year }}
                        </div>
                    </div>

                    <!-- Información específica para pagos múltiples -->
                    <div
                        class="row mb-2"
                        v-if="selectedPaymentInfo.es_pago_multiple"
                    >
                        <div class="col-5 text-muted">Tipo de pago:</div>
                        <div class="col-7">
                            <span class="badge bg-info">Pago múltiple</span>
                            <small class="d-block text-muted mt-1"
                                >({{
                                    selectedPaymentInfo.cantidad_meses
                                }}
                                meses)</small
                            >
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-5 text-muted">Monto:</div>
                        <div class="col-7 font-semibold">
                            {{ selectedPaymentInfo.divisa }}
                            {{
                                Math.round(selectedPaymentInfo.amount * 100) /
                                    100
                            }}
                        </div>
                    </div>

                    <!-- Mostrar descuento si existe -->
                    <div
                        class="row mb-2"
                        v-if="selectedPaymentInfo.descuentoPorMes > 0"
                    >
                        <div class="col-5 text-muted">Descuento:</div>
                        <div class="col-7">
                            {{ selectedPaymentInfo.divisa || divisa }}
                            {{
                                Math.round(
                                    selectedPaymentInfo.descuentoPorMes * 100
                                ) / 100
                            }}
                        </div>
                    </div>

                    <!-- Mostrar precio original si es diferente al monto pagado -->
                    <div
                        class="row mb-2"
                        v-if="
                            selectedPaymentInfo.montoOriginal &&
                                selectedPaymentInfo.montoOriginal !==
                                    selectedPaymentInfo.amount
                        "
                    >
                        <div class="col-5 text-muted">Precio normal:</div>
                        <div class="col-7">
                            {{ selectedPaymentInfo.divisa || divisa }}
                            {{
                                Math.round(
                                    selectedPaymentInfo.montoOriginal * 100
                                ) / 100
                            }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-5 text-muted">Estado:</div>
                        <div class="col-7">
                            <span
                                :class="{
                                    'badge bg-success':
                                        selectedPaymentInfo.estado === 'pagado',
                                    'badge bg-warning':
                                        selectedPaymentInfo.estado ===
                                        'prepagado',
                                    'badge bg-danger':
                                        selectedPaymentInfo.estado ===
                                        'vencido',
                                    'badge bg-secondary': !selectedPaymentInfo.estado
                                }"
                            >
                                {{
                                    selectedPaymentInfo.estado || "No definido"
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-2" v-if="selectedPaymentInfo.fecha">
                        <div class="col-5 text-muted">Fecha de pago:</div>
                        <div class="col-7">
                            {{ formatDate(selectedPaymentInfo.fecha) }}
                        </div>
                    </div>

                    <div class="row mb-2" v-if="selectedPaymentInfo.tipo">
                        <div class="col-5 text-muted">Tipo:</div>
                        <div class="col-7">{{ selectedPaymentInfo.tipo }}</div>
                    </div>

                    <!-- Información adicional para pagos múltiples -->
                    <div
                        class="row mb-2"
                        v-if="
                            selectedPaymentInfo.es_pago_multiple &&
                                selectedPaymentInfo.grupo_pago_id
                        "
                    >
                        <div class="col-5 text-muted">Grupo de pago:</div>
                        <div class="col-7">
                            <small>{{
                                selectedPaymentInfo.grupo_pago_id
                            }}</small>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button
                        class="btn btn-secondary"
                        @click="closePaymentInfoModal"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
            <div v-else class="text-center py-4">
                <p>No hay información disponible para este pago.</p>
            </div>
        </el-dialog>

        <!-- Modal de creación de unidades -->
        <unidades-form
            :showDialog="showUnidadDialog"
            :recordId="recordId"
            @close="closeUnidadDialog"
        ></unidades-form>
    </div>
</template>

<script>
import moment from "moment";
import axios from "axios";
import VehiculosPagoDataTable from "../../../../components/VehiculosPagoDataTable.vue";
import RegisterPaymentModal from "../../../../components/payments/RegisterPaymentModal.vue";
import AdvancedPaymentModal from "../../../../components/payments/AdvancedPaymentModal.vue";
import UnidadesForm from "../../taxis/unidades/form.vue";
import loginVue from "../../../system/configuration/login.vue";

export default {
    name: "TenantTaxisPagosForm",
    components: {
        VehiculosPagoDataTable,
        RegisterPaymentModal,
        AdvancedPaymentModal,
        UnidadesForm
    },
    props: {
        configuration: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            resource: "pagos",
            vehiculos: {
                data: [],
                current_page: 1,
                per_page: 10,
                total: 0,
                last_page: 1
            },
            search: "",
            searchTimeout: null,
            selectedVehicleId: null,
            selectedVehicle: null,
            yearsRange: [],
            monthlyPayments: {}, // Cambiado a objeto
            paymentColors: {}, // Cambiado a objeto
            paymentDetails: {}, // Nueva estructura para almacenar detalles del pago (estado, fecha, etc)
            showUnidadDialog: false, // Controla la visibilidad del modal de unidades
            recordId: null, // ID del registro a editar (null para creación)
            is_multiple: false,
            selectedMonths: [],
            divisa: "USD",
            isLoading: false,
            loadingYear: null,
            loadingMonth: null,
            isPaymentModalOpen: false,
            currentPaymentYear: null,
            currentPaymentMonth: null,
            debug: true, // Habilitar logs para depuración
            isAdvancedModalOpen: false,
            loading_submit: false,
            loading_record: false,
            // Modal de información de pago
            isPaymentInfoModalOpen: false,
            selectedPaymentInfo: null,
            meses: {
                1: "Enero",
                2: "Febrero",
                3: "Marzo",
                4: "Abril",
                5: "Mayo",
                6: "Junio",
                7: "Julio",
                8: "Agosto",
                9: "Septiembre",
                10: "Octubre",
                11: "Noviembre",
                12: "Diciembre"
            },
            debug: process.env.NODE_ENV !== "production",
            isPaymentInfoModalOpen: false,
            selectedPaymentInfo: null
        };
    },
    mounted() {
        // Forzar la actualización inicial del calendario después de montar el componente
        // Esperamos un poco más de tiempo para asegurarnos de que todo esté cargado
        setTimeout(() => {
            if (
                this.selectedVehicleId &&
                this.yearsRange &&
                this.yearsRange.length > 0
            ) {
                console.log(
                    "Forzando actualización inicial del calendario después de montar el componente"
                );
                this.forceCalendarUpdate();
            } else {
                console.log(
                    "No hay vehículo seleccionado o yearsRange no está inicializado todavía"
                );
            }
        }, 500); // Esperamos 500ms para asegurar que todos los datos estén disponibles
    },
    created() {
        this.initYearsRange();
    },
    methods: {
        initYearsRange() {
            const currentYear = new Date().getFullYear();
            this.yearsRange = Array.from(
                { length: currentYear - 2023 + 4 },
                (_, i) => 2023 + i
            );
        },
        toggleMultipleSelection() {
            // Si estamos desactivando el modo múltiple y hay meses seleccionados,
            // limpiamos la selección
            if (this.is_multiple && this.selectedMonths.length > 0) {
                this.selectedMonths = [];
                // Mostramos un mensaje informando que se ha limpiado la selección
                this.$message({
                    message: "Se ha limpiado la selección de meses",
                    type: "info",
                    duration: 3000
                });
            }
            // Cambiamos el estado del modo múltiple
            this.is_multiple = !this.is_multiple;

            // Si acabamos de activar el modo múltiple, aseguramos que no haya selecciones previas
            if (this.is_multiple) {
                this.selectedMonths = [];
            }
        },
        setSelectedVehicle(vehicleId) {
            console.log("Seleccionando vehículo con ID:", vehicleId);

            if (!vehicleId) {
                this.selectedVehicleId = null;
                this.selectedVehicle = null;
                this.monthlyPayments = {};
                this.paymentDetails = {};
                this.paymentColors = {};
                return;
            }

            this.loading_record = true;

            // Limpiar datos previos
            this.monthlyPayments = {};
            this.paymentDetails = {};
            this.paymentColors = {};
            this.selectedMonths = [];

            this.$http
                .get(`/unidades/record/${vehicleId}`)
                .then(response => {
                    // Obtener datos del vehículo de la respuesta
                    const data = response.data.data || response.data;
                    this.selectedVehicleId = data.id;
                    this.selectedVehicle = data;

                    // Verificar que el vehículo tenga subscription_id y datos de subscription
                    if (data.subscription_id && data.subscription) {
                        // Cargar datos de pagos solo si hay una suscripción válida
                        this.loadPaymentsData();

                        // Cargar colores de pagos
                        this.loadPaymentColors();

                        // Forzar actualización del calendario después de cargar los datos
                        this.$nextTick(() => {
                            console.log(
                                "Forzando actualización del calendario después de cargar datos"
                            );
                            if (this.debug) {
                                setTimeout(() => this.logCalendarData(), 500);
                            }
                        });
                    } else {
                        console.log(
                            "El vehículo no tiene una suscripción activa, no se cargará el calendario"
                        );
                        this.$message.warning(
                            "Este vehículo no tiene una suscripción activa."
                        );
                    }
                })
                .catch(error => {
                    console.error(
                        "Error al cargar los datos del vehículo",
                        error
                    );
                    this.$message.error(
                        "Error al cargar los datos del vehículo: " + error
                    );
                })
                .finally(() => {
                    this.loading_record = false;
                });
        },
        loadPaymentsData() {
            // Verificar que el vehículo tenga ID de suscripción y que los datos de suscripción existan
            if (
                !this.selectedVehicleId ||
                !this.selectedVehicle ||
                !this.selectedVehicle.subscription_id ||
                !this.selectedVehicle.subscription ||
                !this.selectedVehicle.subscription.id
            ) {
                console.log(
                    "No hay vehículo seleccionado o sin suscripción válida"
                );
                return;
            }

            console.log(
                `Cargando pagos para la suscripción #${
                    this.selectedVehicle.subscription.id
                }`
            );

            // Resetear estructuras de datos de pagos (esto ya se hace en setSelectedVehicle,
            // pero es buena práctica hacerlo también aquí para garantizar datos limpios)
            this.monthlyPayments = {};
            this.paymentDetails = {};
            this.paymentColors = {};

            this.$http
                .get(
                    `/unidades/subscription-invoices/${
                        this.selectedVehicle.subscription.id
                    }`
                )
                .then(response => {
                    const data = response.data.data || response.data;
                    console.log("Datos de pagos recibidos:", data);

                    if (Array.isArray(data)) {
                        // Crear una copia temporal para manipular los datos
                        const tempMonthlyPayments = {};
                        const tempPaymentDetails = {};
                        const tempPaymentColors = {};

                        data.forEach(element => {
                            // Verificar si es un pago múltiple o un pago individual
                            if (
                                element.es_pago_multiple &&
                                element.payment_details
                            ) {
                                console.log(
                                    "Procesando pago múltiple:",
                                    element
                                );

                                try {
                                    // Convertir payment_details a objeto si viene como string
                                    let paymentDetails =
                                        element.payment_details;
                                    if (typeof paymentDetails === "string") {
                                        paymentDetails = JSON.parse(
                                            paymentDetails
                                        );
                                    }

                                    // Procesar cada detalle de pago
                                    if (Array.isArray(paymentDetails)) {
                                        paymentDetails.forEach(detalle => {
                                            const year = parseInt(detalle.year);
                                            const month = parseInt(detalle.mes);

                                            // Verificar que tenemos datos válidos
                                            if (isNaN(year) || isNaN(month)) {
                                                console.error(
                                                    "Datos inválidos en payment_details:",
                                                    detalle
                                                );
                                                return; // Continuar con el siguiente elemento
                                            }

                                            // Inicializar objetos si no existen
                                            if (!tempMonthlyPayments[year]) {
                                                tempMonthlyPayments[year] = {};
                                            }

                                            if (!tempPaymentDetails[year]) {
                                                tempPaymentDetails[year] = {};
                                            }

                                            // Usar monto_por_mes si está disponible, de lo contrario usar monto
                                            const monto = detalle.monto_por_mes
                                                ? parseFloat(
                                                      detalle.monto_por_mes
                                                  )
                                                : parseFloat(
                                                      detalle.monto || 0
                                                  );

                                            // Guardar el monto en la estructura de pagos
                                            tempMonthlyPayments[year][
                                                month
                                            ] = monto;

                                            // Determinar si es un pago total
                                            const isPaidTotal =
                                                element.payed_total === true ||
                                                element.payed_total === 1 ||
                                                element.payed_total === "1";

                                            // Guardar información adicional del pago
                                            tempPaymentDetails[year][month] = {
                                                id: element.id,
                                                fecha:
                                                    detalle.fecha ||
                                                    element.fecha_cobro,
                                                estado: element.estado,
                                                payedTotal: isPaidTotal,
                                                tipo: element.tipo || "normal",
                                                monto: monto,
                                                year: year,
                                                month: month,
                                                es_pago_multiple: true,
                                                grupo_pago_id:
                                                    element.grupo_pago_id,
                                                cantidad_meses:
                                                    element.cantidad_meses,
                                                payment_detail: detalle,
                                                montoOriginal: parseFloat(
                                                    detalle.monto || 0
                                                ),
                                                descuentoPorMes: parseFloat(
                                                    detalle.descuento_por_mes ||
                                                        0
                                                ),
                                                moneda:
                                                    element.moneda ||
                                                    (this.selectedVehicle
                                                        .subscription &&
                                                    this.selectedVehicle
                                                        .subscription.plan
                                                        ? this.selectedVehicle
                                                              .subscription.plan
                                                              .currency
                                                        : "PEN")
                                            };

                                            // Guardar el color del pago
                                            if (detalle.color) {
                                                if (!tempPaymentColors[year]) {
                                                    tempPaymentColors[
                                                        year
                                                    ] = {};
                                                }
                                                tempPaymentColors[year][month] =
                                                    detalle.color;
                                            }

                                            console.log(
                                                `Pago múltiple registrado: Año ${year}, Mes ${month}, Monto ${monto}, Estado: ${
                                                    element.estado
                                                }`
                                            );
                                        });
                                    }
                                } catch (error) {
                                    console.error(
                                        "Error al procesar payment_details:",
                                        error,
                                        element
                                    );
                                }
                            } else {
                                // Procesamiento normal para pagos individuales
                                // Asegurar que year y mes sean valores numéricos enteros
                                const year = parseInt(element.year);
                                const month = parseInt(element.mes); // Usando 'mes' en lugar de 'month'

                                // Verificar que tenemos datos válidos
                                if (isNaN(year) || isNaN(month)) {
                                    console.error("Datos inválidos:", element);
                                    return; // Continuar con el siguiente elemento
                                }

                                // Inicializar objetos si no existen
                                if (!tempMonthlyPayments[year]) {
                                    tempMonthlyPayments[year] = {};
                                }

                                if (!tempPaymentDetails[year]) {
                                    tempPaymentDetails[year] = {};
                                }

                                // Convertir el monto a número (asegurarse de que es float)
                                const monto = element.monto_por_mes
                                    ? parseFloat(element.monto_por_mes)
                                    : element.monto
                                    ? parseFloat(element.monto)
                                    : 0;

                                // Guardar el monto en la estructura de pagos
                                tempMonthlyPayments[year][month] = monto;

                                // Determinar si es un pago total
                                const isPaidTotal =
                                    element.payed_total === true ||
                                    element.payed_total === 1 ||
                                    element.payed_total === "1";

                                // Guardar información adicional del pago
                                tempPaymentDetails[year][month] = {
                                    id: element.id,
                                    fecha: element.fecha_cobro,
                                    estado: element.estado,
                                    payedTotal: isPaidTotal,
                                    tipo: element.tipo || "normal",
                                    monto: monto,
                                    year: year,
                                    month: month,
                                    es_pago_multiple: false,
                                    descuentoPorMes: parseFloat(
                                        element.descuento_por_mes || 0
                                    ),
                                    moneda:
                                        element.moneda ||
                                        (this.selectedVehicle.subscription &&
                                        this.selectedVehicle.subscription.plan
                                            ? this.selectedVehicle.subscription
                                                  .plan.currency
                                            : "PEN")
                                };

                                // Guardar el color del pago (desde la relación paymentColors)
                                // Los colores ahora vienen de la relación payment_colors
                                if (
                                    element.payment_colors &&
                                    element.payment_colors.length > 0
                                ) {
                                    const colorRecord = element.payment_colors.find(
                                        pc =>
                                            pc.year == year && pc.month == month
                                    );

                                    if (colorRecord && colorRecord.color) {
                                        if (!tempPaymentColors[year]) {
                                            tempPaymentColors[year] = {};
                                        }
                                        tempPaymentColors[year][month] =
                                            colorRecord.color;
                                    }
                                }

                                console.log(
                                    `Pago individual registrado: Año ${year}, Mes ${month}, Monto ${monto}, Estado: ${
                                        element.estado
                                    }`
                                );
                            }
                        });

                        // Actualizar las propiedades reactivas en un solo paso
                        this.monthlyPayments = tempMonthlyPayments;
                        this.paymentDetails = tempPaymentDetails;
                        this.paymentColors = tempPaymentColors;

                        // Forzar actualización de la vista
                        this.$forceUpdate();

                        // Forzar actualización del calendario
                        this.forceCalendarUpdate();

                        // Ejecutar diagnóstico de estructura de datos
                        this.verifyPaymentDataStructure();

                        // Log detallado de la estructura de pagos mensuales
                        console.log(
                            "Pagos registrados después de procesar:",
                            this.monthlyPayments
                        );
                        console.log("Detalles de pagos:", this.paymentDetails);
                        console.log("Colores de pagos:", this.paymentColors);
                    } else {
                        console.warn(
                            "No se pudieron cargar los pagos correctamente:",
                            response.data
                        );
                    }
                })
                .catch(error => {
                    console.error("Error al cargar pagos:", error);
                });

            // Cargar los colores de pagos del vehículo
            this.loadPaymentColors();
        },
        loadPaymentColors() {
            // Si no hay vehículo seleccionado o no tiene suscripción, no hacer nada
            if (
                !this.selectedVehicleId ||
                !this.selectedVehicle ||
                !this.selectedVehicle.subscription_id ||
                !this.selectedVehicle.subscription
            ) {
                return;
            }

            console.log(
                `Cargando colores para vehículo #${
                    this.selectedVehicleId
                } con suscripción #${this.selectedVehicle.subscription_id}`
            );

            // Realizar petición para obtener los colores específicos de pagos para este vehículo
            this.$http
                .get(`/unidades/payment-colors/${this.selectedVehicleId}`)
                .then(response => {
                    const data = response.data;
                    if (data.success && data.colors) {
                        console.log("Estructura recibida de la API:", data);

                        // Inicializar la estructura como objeto
                        this.paymentColors = {};

                        // Procesar directamente la estructura recibida de la API
                        // La API devuelve: { "2025": { "6": "#34d399", ... } }
                        for (const yearKey in data.colors) {
                            const yearNum = parseInt(yearKey);

                            if (!isNaN(yearNum)) {
                                if (!this.paymentColors[yearNum]) {
                                    this.paymentColors[yearNum] = {};
                                }

                                const yearData = data.colors[yearKey];

                                // Si yearData es un objeto, procesar cada mes
                                if (
                                    typeof yearData === "object" &&
                                    yearData !== null
                                ) {
                                    for (const monthKey in yearData) {
                                        const monthNum = parseInt(monthKey);
                                        if (!isNaN(monthNum)) {
                                            this.paymentColors[yearNum][
                                                monthNum
                                            ] = yearData[monthKey];
                                        }
                                    }
                                }
                            }
                        }

                        console.log("Colores procesados:", this.paymentColors);
                    }
                })
                .catch(error => {
                    console.error("Error al cargar colores de pago:", error);
                    // No mostrar error al usuario ya que los colores son opcionales
                });
        },
        getMonthlyPayment(year, month) {
            try {
                // Convertir parámetros a números para asegurar acceso correcto
                const yearNum = parseInt(year);
                const monthNum = parseInt(month);

                if (isNaN(yearNum) || isNaN(monthNum)) {
                    console.warn(
                        `Valores inválidos en getMonthlyPayment - Año: ${year}, Mes: ${month}`
                    );
                    return 0;
                }

                // Primero verificar si hay información detallada sobre este pago
                const paymentInfo = this.getPaymentInfo(yearNum, monthNum);

                if (paymentInfo) {
                    // Si hay información detallada y es un pago múltiple con detalles específicos
                    if (
                        paymentInfo.es_pago_multiple &&
                        paymentInfo.payment_detail
                    ) {
                        // Usar monto_por_mes del payment_detail si existe
                        if (
                            paymentInfo.payment_detail.monto_por_mes !==
                            undefined
                        ) {
                            const amount = parseFloat(
                                paymentInfo.payment_detail.monto_por_mes || 0
                            );
                            return amount;
                        }
                    }

                    // Si hay información pero no es un pago múltiple o no tiene detalles específicos,
                    // usar el monto registrado en el objeto de información
                    if (paymentInfo.monto !== undefined) {
                        const amount = parseFloat(paymentInfo.monto || 0);
                        return amount;
                    }
                }

                // Verificar si existen datos para el año y mes especificados
                if (
                    this.monthlyPayments &&
                    typeof this.monthlyPayments === "object" &&
                    this.monthlyPayments[yearNum] &&
                    typeof this.monthlyPayments[yearNum] === "object" &&
                    this.monthlyPayments[yearNum][monthNum] !== undefined
                ) {
                    // Acceder al valor directamente
                    const rawAmount = this.monthlyPayments[yearNum][monthNum];

                    // IMPORTANTE: Asegurar que el valor es un número válido
                    let amount = 0;
                    if (typeof rawAmount === "number") {
                        amount = rawAmount;
                    } else if (typeof rawAmount === "string") {
                        amount = parseFloat(rawAmount) || 0;
                    } else {
                        if (this.debug) {
                            console.log(
                                `Valor no reconocido: ${typeof rawAmount}`,
                                rawAmount
                            );
                        }
                        amount = 0;
                    }

                    if (this.debug) {
                        console.log(
                            `Monto encontrado para {${yearNum}-${monthNum}}: ${amount}`
                        );
                    }

                    // Devolver el valor numérico sin formatear
                    return amount;
                }
            } catch (error) {
                console.error("Error en getMonthlyPayment:", error);
            }

            return 0;
        },
        getCellColor(year, month) {
            try {
                // Convertir parámetros a números para asegurar acceso correcto
                const yearNum = parseInt(year);
                const monthNum = parseInt(month);

                // Verificar si el mes está seleccionado in modo múltiple
                const isSelected =
                    this.is_multiple &&
                    this.selectedMonths.includes(`${yearNum}-${monthNum}`);

                // Si está seleccionado, devuelve el color de selección
                if (isSelected) {
                    return "#dbeafe"; // azul claro para celdas seleccionadas
                }

                // Comprobar si existe un color específico para este pago
                if (
                    this.paymentColors &&
                    this.paymentColors[yearNum] &&
                    this.paymentColors[yearNum][monthNum]
                ) {
                    if (this.debug) {
                        console.log(
                            `Color personalizado para ${yearNum}-${monthNum}: ${
                                this.paymentColors[yearNum][monthNum]
                            }`
                        );
                    }
                    return this.paymentColors[yearNum][monthNum];
                }

                // Si no hay un color específico de la API, no aplicar ningún color
                return "";
            } catch (error) {
                console.error("Error obteniendo color de celda:", error);
                return "";
            }
        },
        handleCellClick(year, month) {
            // Convertir parámetros a números para asegurar acceso correcto
            const yearNum = parseInt(year);
            const monthNum = parseInt(month);

            // Verificar si este mes ya tiene un pago registrado
            const paymentInfo = this.getPaymentInfo(yearNum, monthNum);

            if (paymentInfo) {
                // Si es un pago múltiple o un pago total, no permitir modificarlo
                if (paymentInfo.es_pago_multiple) {
                    this.notifyError(
                        "Acción no permitida",
                        "Este mes forma parte de un pago múltiple y no puede ser modificado individualmente."
                    );
                    return;
                }

                // Si es un pago total, no permitir modificarlo
                if (paymentInfo.payedTotal) {
                    this.notifyError(
                        "Acción no permitida",
                        "Este mes ya tiene un pago total registrado y no puede ser modificado."
                    );
                    return;
                }

                // Si tiene cualquier otro tipo de pago, mostrar mensaje
                if (this.getMonthlyPayment(yearNum, monthNum) > 0) {
                    // Mostrar información del pago en lugar de error
                    this.showPaymentInfoModal(yearNum, monthNum);
                    return;
                }
            }

            if (this.is_multiple) {
                this.handleMultipleSelection(yearNum, monthNum);
            } else {
                this.openModalregisterPayment(yearNum, monthNum);
            }
        },

        // Función para verificar si un mes tiene pago total marcado
        hasPayedTotal(year, month) {
            const yearNum = parseInt(year);
            const monthNum = parseInt(month);
            console.log(
                "Detalles de pago para el año y mes:",
                this.paymentDetails[yearNum]
            );
            return (
                this.paymentDetails &&
                typeof this.paymentDetails === "object" &&
                this.paymentDetails[yearNum] &&
                typeof this.paymentDetails[yearNum] === "object" &&
                this.paymentDetails[yearNum][monthNum] !== undefined &&
                this.paymentDetails[yearNum][monthNum].payedTotal === true
            );
        },
        handleMultipleSelection(year, month) {
            const cellId = `${year}-${month}`;

            // Validar que el mes no tenga pago registrado
            if (this.getMonthlyPayment(year, month) > 0) {
                // Si el mes tiene un pago registrado, verificar si es un pago total
                if (this.hasPayedTotal(year, month)) {
                    this.notifyError(
                        "Error al seleccionar mes",
                        "Este mes ya tiene un pago total registrado y no puede ser modificado."
                    );
                } else {
                    this.notifyError(
                        "Error al seleccionar mes",
                        "Este mes ya tiene un pago registrado. No se puede seleccionar."
                    );
                }
                return;
            }

            // Ya no validamos que el mes sea a partir del mes actual
            // Solo validamos que no sea un mes con pago registrado (validación ya realizada arriba)

            // Deseleccionar si ya está seleccionado
            const index = this.selectedMonths.indexOf(cellId);
            if (index > -1) {
                this.selectedMonths.splice(index, 1);

                // Ordenar las selecciones para mantener consistencia
                this.sortSelectedMonths();
                return;
            }

            // Verificar que no se seleccionen más de 12
            if (this.selectedMonths.length >= 12) {
                this.notifyError(
                    "Error al seleccionar mes",
                    "Solo se pueden seleccionar máximo 12 meses consecutivos."
                );
                return;
            }

            // Validar consecutividad
            if (this.selectedMonths.length > 0) {
                const temp = [...this.selectedMonths, cellId];
                this.sortMonthArray(temp);

                // Comprobar si los meses son consecutivos
                if (!this.areMonthsConsecutive(temp)) {
                    this.notifyError(
                        "Error al seleccionar mes",
                        "Los meses seleccionados deben ser consecutivos."
                    );
                    return;
                }
            }

            // Añadir a seleccionados
            this.selectedMonths.push(cellId);

            // Ordenar las selecciones
            this.sortSelectedMonths();
        },

        // Método para ordenar los meses seleccionados
        sortSelectedMonths() {
            this.sortMonthArray(this.selectedMonths);
        },

        // Ordena un array de meses en formato "año-mes"
        sortMonthArray(monthArray) {
            monthArray.sort((a, b) => {
                const [yearA, monthA] = a.split("-").map(Number);
                const [yearB, monthB] = b.split("-").map(Number);

                const valueA = yearA * 100 + monthA;
                const valueB = yearB * 100 + monthB;

                return valueA - valueB;
            });
        },

        // Verifica si un array de meses es consecutivo
        areMonthsConsecutive(monthArray) {
            // Necesitamos al menos 2 meses para verificar consecutividad
            if (monthArray.length < 2) return true;

            // Primero ordenamos el array
            this.sortMonthArray(monthArray);

            for (let i = 1; i < monthArray.length; i++) {
                const [prevYear, prevMonth] = monthArray[i - 1]
                    .split("-")
                    .map(Number);
                const [currYear, currMonth] = monthArray[i]
                    .split("-")
                    .map(Number);

                // Verificar si son meses consecutivos
                const isNextMonth =
                    (currYear === prevYear && currMonth === prevMonth + 1) ||
                    (currYear === prevYear + 1 &&
                        currMonth === 1 &&
                        prevMonth === 12);

                if (!isNextMonth) {
                    return false;
                }
            }

            return true;
        },
        openModalregisterPayment(year, month) {
            this.isLoading = true;
            this.loadingYear = year;
            this.loadingMonth = month;

            // Simular una breve carga antes de abrir el modal
            setTimeout(() => {
                this.isLoading = false;
                this.loadingYear = null;
                this.loadingMonth = null;

                // Guardar la información del mes y año a pagar
                this.currentPaymentYear = year;
                this.currentPaymentMonth = month;

                // Preparar datos para el modal - Ahora el modal calcula sus propios valores
                this.$nextTick(() => {
                    // Abrir el modal de pago
                    this.isPaymentModalOpen = true;

                    console.log(`Abriendo modal de pago para ${year}-${month}`);
                });
            }, 300);
        },
        openAdvancedModal() {
            console.log("Abrir modal avanzado para:", this.selectedMonths);

            // Verificar si hay meses seleccionados
            if (this.selectedMonths.length === 0) {
                this.notifyError(
                    "Error al abrir modal",
                    "No hay meses seleccionados para procesar."
                );
                return;
            }

            // Verificar si los meses son consecutivos
            if (!this.areMonthsConsecutive(this.selectedMonths)) {
                this.$confirm(
                    "Los meses seleccionados no son consecutivos. ¿Desea continuar de todos modos?",
                    "Aviso",
                    {
                        confirmButtonText: "Continuar",
                        cancelButtonText: "Cancelar",
                        type: "warning"
                    }
                )
                    .then(() => {
                        // Abrir el modal avanzado - ahora calcula sus propios valores
                        this.isAdvancedModalOpen = true;
                    })
                    .catch(() => {
                        // El usuario canceló
                        this.$message({
                            type: "info",
                            message: "Operación cancelada"
                        });
                    });
            } else {
                // Abrir el modal avanzado directamente si los meses son consecutivos
                this.isAdvancedModalOpen = true;
            }
        },
        openModalCreateUnidad() {
            console.log("Abriendo modal para crear unidad");
            this.recordId = null; // Aseguramos que sea null para crear un nuevo registro
            this.showUnidadDialog = true;
        },
        closePaymentModal() {
            this.isPaymentModalOpen = false;
            this.currentPaymentYear = null;
            this.currentPaymentMonth = null;
        },
        closeAdvancedModal() {
            this.isAdvancedModalOpen = false;
        },
        closeUnidadDialog() {
            this.showUnidadDialog = false;
            this.recordId = null;
            // Recargamos la lista de vehículos después de crear uno nuevo
            //  this.loadVehiculos(this.vehiculos.current_page, this.search);
        },
        handleSavePago(pago) {
            const year = pago.year;
            const month = pago.mes;

            // Verificar que el monto sea válido
            if (!pago.monto || pago.monto <= 0) {
                this.notifyError(
                    "Error al registrar pago",
                    "El monto debe ser mayor a 0"
                );
                return;
            }

            this.loading_submit = true;

            // Guardar el ID del vehículo para la recarga
            const vehiculoId = pago.vehiculoId;
            console.log("Guardando pago para vehículo ID:", vehiculoId);

            this.$http
                .post(`/${this.resource}`, pago)
                .then(res => {
                    if (res.data.success) {
                        this.$message.success(res.data.message);

                        // Cerrar el modal de pago
                        this.closePaymentModal();

                        // Recargar los datos del vehículo seleccionado
                        // Esto cargará tanto los pagos como los colores
                        this.setSelectedVehicle(vehiculoId);
                    } else {
                        this.$message.error(res.data.message);
                    }
                })
                .catch(err => {
                    if (err.response && err.response.status === 422) {
                        const errors = err.response.data;
                        this.$message.error(errors.message);
                    } else {
                        console.log("error: " + err);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        handleSaveMultiplePagos(pagos) {
            // Validar que haya pagos
            if (!pagos || pagos.length === 0) {
                this.notifyError(
                    "Error en pagos múltiples",
                    "No hay pagos para registrar"
                );
                return;
            }

            // Verificar el monto de cada pago
            let error = false;
            let montoTotal = 0;

            // Verificar monto total
            pagos.forEach(pago => {
                if (!pago.monto || pago.monto <= 0) {
                    error = true;
                    return;
                }
                montoTotal += parseFloat(pago.monto);
            });

            if (error) {
                this.notifyError(
                    "Error en pagos múltiples",
                    "Todos los pagos deben tener monto mayor a 0"
                );
                return;
            }

            this.loading_submit = true;
            // En una implementación real, aquí se enviarían los pagos al servidor
            // Por ahora, actualizamos el estado local de los pagos

            this.$http
                .post(`/${this.resource}`, pagos)
                .then(res => {
                    if (res.data.success) {
                        this.$message.success(res.data.message);

                        // Limpiar los meses seleccionados
                        this.selectedMonths = [];
                        this.is_multiple = false;

                        this.setSelectedVehicle(this.selectedVehicleId);
                        this.closeAdvancedModal();
                    } else {
                        this.$message.error(res.data.message);
                    }
                })
                .catch(err => {
                    if (err.response && err.response.status === 422) {
                        const errors = err.response.data;
                        this.$message.error(errors.message);
                    } else {
                        console.log("error: " + err);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });

            // Cerrar el modal avanzado
            // this.closeAdvancedModal();
        },
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY");
        },
        formatCurrency(value) {
            try {
                // Manejo de valores undefined, null, o no válidos
                if (value === undefined || value === null) {
                    return "0.00";
                }

                // Asegurar que tenemos un número
                let numValue;

                if (typeof value === "number") {
                    numValue = value;
                } else if (typeof value === "string") {
                    numValue = parseFloat(value);
                } else {
                    console.warn(
                        "Tipo de valor inesperado en formatCurrency:",
                        typeof value,
                        value
                    );
                    numValue = 0;
                }

                if (isNaN(numValue)) {
                    console.warn("Valor no numérico en formatCurrency:", value);
                    return "0.00";
                }

                // Usar toLocaleString para formatear con 2 decimales y separador de miles
                return numValue.toLocaleString("es-PE", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Alternativa más simple si toLocaleString da problemas
                // return (Math.round(numValue * 100) / 100)
                //     .toFixed(2)
                //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } catch (error) {
                console.error("Error en formatCurrency:", error);
                return "0.00";
            }
        },
        notifyError(title, mensaje) {
            this.$message({
                title: title,
                message: mensaje,
                type: "error"
            });
        },
        /**
         * Abre el menú contextual para seleccionar color
         * @param {Number} year - Año de la celda
         * @param {Number} month - Mes de la celda
         * @param {Event} event - Evento del click derecho
         */
        /**
         * Abre el modal de información de pago
         * @param {Number} year - Año del pago
         * @param {Number} month - Mes del pago
         */
        openPaymentInfoModal(year, month) {
            const yearNum = parseInt(year);
            const monthNum = parseInt(month);

            // Verificar si hay pago para esta celda
            const amount = this.getMonthlyPayment(yearNum, monthNum);
            if (amount <= 0) return;

            // Obtener información del pago
            const info = this.getPaymentInfo(yearNum, monthNum);

            // Crear objeto de información de pago
            this.selectedPaymentInfo = {
                year: yearNum,
                month: monthNum,
                amount: amount,
                estado: info.estado || "pagado",
                fecha: info.fecha || null,
                tipo: info.tipo || "regular",
                payedTotal: info.payedTotal || true,
                // Usar la moneda del pago, o si no está disponible, usar la del plan de suscripción
                divisa:
                    info.moneda ||
                    (this.selectedVehicle &&
                    this.selectedVehicle.subscription &&
                    this.selectedVehicle.subscription.plan
                        ? this.selectedVehicle.subscription.plan.currency
                        : "PEN"),
                es_pago_multiple: info.es_pago_multiple || false,
                grupo_pago_id: info.grupo_pago_id || null,
                cantidad_meses: info.cantidad_meses || 1,
                descuentoPorMes: info.descuentoPorMes || 0,
                montoOriginal: info.montoOriginal || amount,
                // Si es un pago múltiple, incluir detalles adicionales
                payment_detail: info.payment_detail || null
            };

            // Abrir el modal
            this.isPaymentInfoModalOpen = true;
        },

        /**
         * Cierra el modal de información de pago
         */
        closePaymentInfoModal() {
            this.isPaymentInfoModalOpen = false;
            this.selectedPaymentInfo = null;
        },

        /**
         * Obtener el nombre de un mes a partir de su número
         * @param {Number} month - Número de mes (1-12)
         * @returns {String} - Nombre del mes
         */
        getMesNombre(month) {
            return this.meses[month] || "";
        },

        // Nuevo método para obtener información detallada del pago
        getPaymentInfo(year, month) {
            try {
                const yearNum = parseInt(year);
                const monthNum = parseInt(month);

                if (
                    this.paymentDetails &&
                    this.paymentDetails[yearNum] &&
                    this.paymentDetails[yearNum][monthNum]
                ) {
                    // Recuperar la información básica del pago
                    const info = this.paymentDetails[yearNum][monthNum];

                    // Si es un pago múltiple y tiene detalles específicos para este mes, complementar la información
                    if (info.es_pago_multiple && info.payment_detail) {
                        // Combinar la información básica con los detalles específicos del mes
                        return {
                            ...info,
                            monto: parseFloat(
                                info.payment_detail.monto_por_mes ||
                                    info.monto ||
                                    0
                            ),
                            montoOriginal: parseFloat(
                                info.payment_detail.monto || 0
                            ),
                            descuentoPorMes: parseFloat(
                                info.payment_detail.descuento_por_mes || 0
                            ),
                            color: info.payment_detail.color || null
                        };
                    }

                    return info;
                }

                return null;
            } catch (error) {
                console.error("Error obteniendo información del pago:", error);
                return null;
            }
        },

        /**
         * Obtiene la moneda (divisa) de un pago específico
         * @param {Number} year - Año del pago
         * @param {Number} month - Mes del pago
         * @returns {String} - Moneda del pago (PEN, USD, etc.)
         */
        getPaymentCurrency(year, month) {
            // Primero verificar si hay información detallada sobre este pago
            const paymentInfo = this.getPaymentInfo(year, month);

            if (paymentInfo && paymentInfo.moneda) {
                return paymentInfo.moneda;
            }

            // Si no hay información específica del pago, usar la moneda del plan del vehículo
            if (
                this.selectedVehicle &&
                this.selectedVehicle.subscription &&
                this.selectedVehicle.subscription.plan &&
                this.selectedVehicle.subscription.plan.currency
            ) {
                return this.selectedVehicle.subscription.plan.currency;
            }

            // Si todo lo demás falla, devolver la divisa por defecto
            return this.divisa || "PEN";
        },
        // Método para formatear la información del pago para tooltip
        formatPaymentInfoTooltip(year, month) {
            const info = this.getPaymentInfo(year, month);
            if (!info) return "";

            const paymentAmount = this.getMonthlyPayment(year, month);

            let tooltip = `<div class="payment-tooltip">`;

            // Información básica del monto
            tooltip += `<div><strong>Monto:</strong> ${info.moneda ||
                this.divisa} ${this.formatCurrency(paymentAmount)}</div>`;

            // Si es un pago múltiple, mostrar información adicional
            if (info.es_pago_multiple) {
                if (info.descuentoPorMes) {
                    tooltip += `<div><strong>Descuento:</strong> ${info.moneda ||
                        this.divisa} ${this.formatCurrency(
                        info.descuentoPorMes
                    )}</div>`;
                }

                // Si tenemos el monto original y es diferente al monto por mes
                if (
                    info.montoOriginal &&
                    info.montoOriginal !== paymentAmount
                ) {
                    tooltip += `<div><strong>Precio normal:</strong> ${info.moneda ||
                        this.divisa} ${this.formatCurrency(
                        info.montoOriginal
                    )}</div>`;
                }

                // Información sobre el pago múltiple
                tooltip += `<div><strong>Parte de pago múltiple:</strong> <span style="color: #10b981;">Sí</span></div>`;

                if (info.cantidad_meses) {
                    tooltip += `<div><strong>Total meses:</strong> ${
                        info.cantidad_meses
                    }</div>`;
                }
            } else if (info.descuentoPorMes) {
                // Para pagos individuales con descuento
                tooltip += `<div><strong>Descuento:</strong> ${info.moneda ||
                    this.divisa} ${this.formatCurrency(
                    info.descuentoPorMes
                )}</div>`;
            }

            // Estado del pago
            tooltip += `<div><strong>Estado:</strong> <span class="${
                info.estado === "pagado" ? "text-success" : "text-warning"
            }">${info.estado}</span></div>`;

            // Fecha del pago
            if (info.fecha) {
                tooltip += `<div><strong>Fecha de pago:</strong> ${this.formatDate(
                    info.fecha
                )}</div>`;
            }

            // Tipo de pago
            if (info.tipo) {
                tooltip += `<div><strong>Tipo:</strong> ${info.tipo}</div>`;
            }

            // Si es pago múltiple, mostrar ID del grupo
            if (info.es_pago_multiple && info.grupo_pago_id) {
                tooltip += `<div><small>Grupo: ${
                    info.grupo_pago_id
                }</small></div>`;
            }

            tooltip += `</div>`;
            return tooltip;
        },

        // Métodos para mostrar/ocultar tooltip
        showPaymentInfo(year, month) {
            const payment = this.getMonthlyPayment(year, month);
            if (payment <= 0) return; // No mostrar tooltip si no hay pago

            const tooltipContent = this.formatPaymentInfoTooltip(year, month);
            if (!tooltipContent) return;

            // Usar alguna biblioteca como tippy.js para mostrar el tooltip
            // O implementar un tooltip personalizado
            this.$message({
                dangerouslyUseHTMLString: true,
                message: tooltipContent,
                offset: 20,
                duration: 3000,
                showClose: true,
                type: "info"
            });
        },

        hidePaymentInfo() {
            // Si usas una biblioteca de tooltip, aquí puedes ocultarlo
            // this.$tippy.hideAll();
        },

        // Añadir método para depuración de datos
        logCalendarData() {
            console.log("===== DATOS DEL CALENDARIO =====");
            console.log(
                "Vehículo seleccionado:",
                this.selectedVehicle ? this.selectedVehicle.id : "ninguno"
            );
            console.log("Estructura de monthlyPayments:", this.monthlyPayments);
            console.log("Estructura de paymentColors:", this.paymentColors);

            // Verificar algunos valores de ejemplo
            const currentYear = new Date().getFullYear();
            for (let m = 1; m <= 12; m++) {
                const amount = this.getMonthlyPayment(currentYear, m);
                console.log(`Pago para ${currentYear}-${m}: ${amount}`);
            }

            console.log("================================");
        },
        /**
         * Método de diagnóstico para verificar la estructura de los datos de pago
         */
        verifyPaymentDataStructure() {
            console.log("=== DIAGNÓSTICO DE DATOS DE PAGOS ===");

            // Verificar que yearsRange exista
            if (!this.yearsRange) {
                console.error("yearsRange no está definido!");
            } else {
                console.log("yearsRange:", JSON.stringify(this.yearsRange));
            }

            // Verificar monthlyPayments
            try {
                console.log(
                    "monthlyPayments:",
                    this.monthlyPayments
                        ? JSON.stringify(this.monthlyPayments)
                        : "undefined"
                );
                if (
                    !this.monthlyPayments ||
                    typeof this.monthlyPayments !== "object"
                ) {
                    console.error("monthlyPayments no es un objeto válido!");
                }
            } catch (error) {
                console.error("Error al serializar monthlyPayments:", error);
            }

            // Verificar cada año y mes
            if (this.monthlyPayments) {
                for (const year in this.monthlyPayments) {
                    const yearNum = parseInt(year);
                    if (isNaN(yearNum)) {
                        console.error(
                            `Año inválido en monthlyPayments: ${year}`
                        );
                        continue;
                    }

                    const monthData = this.monthlyPayments[year];
                    if (!monthData || typeof monthData !== "object") {
                        console.error(
                            `Datos de mes inválidos para año ${year}`
                        );
                        continue;
                    }

                    for (const month in monthData) {
                        const monthNum = parseInt(month);
                        if (isNaN(monthNum)) {
                            console.error(
                                `Mes inválido en monthlyPayments[${year}]: ${month}`
                            );
                            continue;
                        }

                        const amount = monthData[month];
                        if (typeof amount !== "number") {
                            console.warn(
                                `Monto no numérico en monthlyPayments[${year}][${month}]: ${amount} (${typeof amount})`
                            );
                        }

                        // Prueba de acceso con getMonthlyPayment
                        const retrievedAmount = this.getMonthlyPayment(
                            year,
                            month
                        );
                        console.log(
                            `Año ${year}, Mes ${month}: Valor directo = ${amount}, Valor obtenido por getMonthlyPayment = ${retrievedAmount}`
                        );

                        if (amount !== retrievedAmount) {
                            console.error(
                                `¡Discrepancia en el monto! ${amount} vs ${retrievedAmount}`
                            );
                        }
                    }
                }
            }

            console.log("=== FIN DEL DIAGNÓSTICO ===");
        },
        /**
         * Forzar actualización del calendario
         */
        forceCalendarUpdate() {
            console.log("Forzando actualización del calendario");

            // Comprobar si yearsRange está definido
            if (
                !this.yearsRange ||
                !Array.isArray(this.yearsRange) ||
                this.yearsRange.length === 0
            ) {
                console.warn(
                    "No hay años definidos para actualizar el calendario"
                );
                return;
            }

            // Forzar actualización del componente
            this.$forceUpdate();

            // Actualizar las celdas de forma segura
            this.$nextTick(() => {
                try {
                    console.log(
                        "Actualizando celdas del calendario. Años disponibles:",
                        this.yearsRange.length
                    );

                    // Usar un bucle for normal en lugar de forEach
                    for (let i = 0; i < this.yearsRange.length; i++) {
                        const year = this.yearsRange[i];

                        for (let month = 1; month <= 12; month++) {
                            try {
                                const cellId = `cell-content-${year}-${month}`;
                                const cellElement = document.getElementById(
                                    cellId
                                );

                                // Ya no manipulamos directamente el DOM con textContent
                                // porque nuestra estructura de celda es más compleja
                                // Ahora usamos reactiveData para actualizar la vista
                            } catch (err) {
                                console.error(
                                    `Error al actualizar celda ${year}-${month}:`,
                                    err
                                );
                            }
                        }
                    }
                } catch (error) {
                    console.error(
                        "Error general al actualizar calendario:",
                        error
                    );
                }

                console.log("Actualización del calendario completada");
            });
        },

        /**
         * Redirige al usuario a la página de planes para asignar una suscripción al vehículo seleccionado
         */
        gotoSubscription() {
            if (!this.selectedVehicleId || !this.selectedVehicle) {
                this.$message.warning("No hay un vehículo seleccionado.");
                return;
            }

            // Redireccionar a la página de planes para este vehículo
            const url = `unidades`;
            // Usar el router de Vue si está disponible
            if (this.$router) {
                this.$router.push(url);
            } else {
                // Alternativa: usar window.location
                window.location.href = url;
            }
        }
    }
};
</script>

<style scoped>
/* Estilos para las celdas del calendario */
.calendar-cell {
    min-width: 100px;
    height: 55px; /* Dar suficiente altura para el contenido y la divisa */
    vertical-align: top;
    padding: 4px !important;
}

.selected-cell {
    position: relative;
    border: 2px solid #3b82f6 !important;
    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
}

/* Estilos para el contenido de las celdas */
.cell-content-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.cell-content-wrapper .amount {
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.cell-content-wrapper .currency-info {
    margin-top: 2px;
    opacity: 0.8;
}

.cell-content-wrapper .total-badge {
    font-size: 0.65rem;
    color: #10b981;
    margin-left: 3px;
}

/* Asegurando que todas las celdas del calendario tengan cursor pointer */
#calendarTable td {
    cursor: pointer !important;
}

/* Para elementos específicos with color-option */
.color-option {
    cursor: pointer !important;
}

/* Estilos para el menú de colores */
.color-option {
    transition: transform 0.1s ease, box-shadow 0.1s ease;
}

.color-option:hover {
    transform: scale(1.1);
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    z-index: 1;
}

/* Estilos generales para mejorar la apariencia */
:deep(.container-fluid) {
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
}

/* Mejoras en las tarjetas de vehículos */
.position-relative.transition-hover {
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

/* Hacer que la placa destaque más */
.fw-bold.mb-0.text-gray-800 {
    color: #3b82f6;
    letter-spacing: 0.8px;
    font-weight: 700 !important;
    text-transform: uppercase;
}

/* Hacer que el calendario se vea más profesional */
table {
    font-size: 0.75rem;
    border-collapse: separate;
    border-spacing: 0;
}

table th {
    background-color: #f8f9fa;
    font-weight: 500;
    padding: 6px;
    border-bottom: 1px solid #dee2e6;
}

table td {
    padding: 6px;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}

table td:hover {
    background-color: rgba(59, 130, 246, 0.05);
}

/* Estilo para la franja lateral */
.ribbon {
    width: 6px;
    transition: width 0.2s ease;
    border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
}

.position-relative:hover .ribbon {
    width: 8px;
}

/* Efecto hover para las tarjetas */
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.position-relative.transition-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
    display: inline-flex;
    align-items: center;
}

/* Estilo para el botón de pago adelantado */
.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1),
        0 2px 4px -1px rgba(59, 130, 246, 0.06);
    transform: translateY(-1px);
}

#advancedButtonContainer {
    text-align: right;
    margin-top: 15px;
    margin-bottom: 10px;
    padding-right: 15px;
}

/* Estilos para mejorar la experiencia con el cursor */
.vehicle-card {
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.vehicle-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.vehicle-card:active {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

/* Estilos específicos para las celdas del calendario */
.calendar-cell {
    cursor: pointer !important;
}

/* Estilo para celdas seleccionadas */
.selected-cell {
    border: 2.5px solid #3b82f6 !important;
    position: relative;
    z-index: 1;
    box-shadow: 0 0 6px rgba(59, 130, 246, 0.5);
    animation: pulse-border 1.5s infinite;
}

@keyframes pulse-border {
    0% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}

/* Estilos para el tooltip de información de pago */
.payment-tooltip {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    white-space: nowrap;
}

.payment-tooltip strong {
    display: inline-block;
    margin-bottom: 4px;
}
</style>
