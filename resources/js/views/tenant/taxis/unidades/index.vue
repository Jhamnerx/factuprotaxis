<template>
    <div class="unidades">
        <div class="page-header pr-0">
            <h2>Unidades</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span
                        >Listado de unidades
                        {{
                            estado == "0" ? " - Inactivas" : " - Activas"
                        }}</span
                    >
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <button
                    class="btn btn-custom btn-sm mt-2 mr-2"
                    type="button"
                    @click.prevent="clickCreate()"
                >
                    <i class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card tab-content-default row-new mb-0">
            <div class="data-table-visible-columns">
                <div class="row">
                    <div class="col-md-12">
                        <el-dropdown :hide-on-click="false">
                            <el-button type="primary">
                                <i class="fa fa-columns"></i> Mostrar/Ocultar
                                columnas<i
                                    class="el-icon-arrow-down el-icon--right"
                                ></i>
                            </el-button>
                            <el-dropdown-menu
                                slot="dropdown"
                                class="column-selection-dropdown"
                            >
                                <el-dropdown-item
                                    v-for="(column, index) in columns"
                                    :key="index"
                                >
                                    <el-checkbox
                                        @change="getColumnsToShow(1)"
                                        v-model="column.visible"
                                        >{{ column.label }}</el-checkbox
                                    >
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <data-table
                    ref="dataTable"
                    :columns="columns"
                    :resource="resource"
                    :estadoVehiculo="estado"
                    @click-create="clickCreate"
                    @click-edit="clickEdit"
                    @click-delete="clickDelete"
                >
                    <tr slot="heading">
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Chasis</th>
                        <th v-if="columns.numero_interno.visible">
                            Nº Interno
                        </th>
                        <th v-if="columns.propietario.visible">
                            Propietario
                        </th>
                        <th v-if="columns.conductor.visible">
                            Conductor
                        </th>
                        <th v-if="columns.marca.visible">Marca</th>
                        <th v-if="columns.modelo.visible">Modelo</th>
                        <th v-if="columns.year.visible">Año</th>
                        <th v-if="columns.color.visible">Color</th>
                        <th v-if="columns.categoria.visible">Categoría</th>
                        <th v-if="columns.numero_motor.visible">
                            Nº Motor
                        </th>
                        <th v-if="columns.asientos.visible">Asientos</th>
                        <th v-if="columns.ccn.visible">CCN</th>
                        <th v-if="columns.ejes.visible">Ejes</th>
                        <th v-if="columns.fecha_ingreso.visible">
                            Fecha Ingreso
                        </th>
                        <th v-if="columns.largo.visible">Largo</th>
                        <th v-if="columns.ancho.visible">Ancho</th>
                        <th v-if="columns.alto.visible">Alto</th>
                        <th v-if="columns.peso.visible">Peso</th>
                        <th v-if="columns.carga_util.visible">
                            Carga Útil
                        </th>
                        <th>Estado</th>
                        <th>Detalle Plan</th>
                        <th>Estado TUC</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ row }">
                        <td>{{ row.id }}</td>
                        <td>{{ row.placa }}</td>
                        <td>{{ row.chasis }}</td>
                        <td v-if="columns.numero_interno.visible">
                            {{ row.numero_interno }}
                        </td>
                        <td v-if="columns.propietario.visible">
                            {{ row.propietario.name }}
                        </td>
                        <td v-if="columns.conductor.visible">
                            <span v-if="row.conductor" class="conductor-info">
                                <i class="fas fa-user text-green-600"></i>
                                {{ row.conductor.name }}
                            </span>
                            <span v-else class="text-gray-400">
                                <i class="fas fa-user-slash"></i>
                                Sin asignar
                            </span>
                        </td>
                        <td v-if="columns.marca.visible">
                            {{ row.marca.nombre }}
                        </td>
                        <td v-if="columns.modelo.visible">
                            {{ row.modelo.nombre }}
                        </td>
                        <td v-if="columns.year.visible" class="year">
                            {{ row.year }}
                        </td>
                        <td v-if="columns.color.visible">
                            {{ row.color }}
                        </td>
                        <td v-if="columns.categoria.visible">
                            {{ row.categoria }}
                        </td>
                        <td v-if="columns.numero_motor.visible">
                            {{ row.numero_motor }}
                        </td>
                        <td v-if="columns.asientos.visible" class="asientos">
                            {{ row.asientos }}
                        </td>
                        <td v-if="columns.ccn.visible">{{ row.ccn }}</td>
                        <td v-if="columns.ejes.visible" class="ejes">
                            {{ row.ejes }}
                        </td>
                        <td v-if="columns.fecha_ingreso.visible">
                            {{ formatDate(row.fecha_ingreso) }}
                        </td>
                        <td v-if="columns.largo.visible" class="numeric">
                            {{ formatNumber(row.largo) }} m
                        </td>
                        <td v-if="columns.ancho.visible" class="numeric">
                            {{ formatNumber(row.ancho) }} m
                        </td>
                        <td v-if="columns.alto.visible" class="numeric">
                            {{ formatNumber(row.alto) }} m
                        </td>
                        <td v-if="columns.peso.visible" class="numeric">
                            {{ formatNumber(row.peso) }} kg
                        </td>
                        <td v-if="columns.carga_util.visible" class="numeric">
                            {{ formatNumber(row.carga_util) }} kg
                        </td>

                        <td>
                            <span
                                :class="
                                    'badge-estado-tuc ' +
                                        getEstadoClass(row.estado)
                                "
                                >{{ row.estado || "NO REGISTRADO" }}</span
                            >
                        </td>
                        <td>
                            <el-popover
                                v-if="row.plan_id"
                                placement="top-start"
                                width="400"
                                trigger="hover"
                                popper-class="subscription-popover"
                            >
                                <div class="subscription-info">
                                    <h4>Información de Suscripción</h4>
                                    <div class="subscription-details">
                                        <p>
                                            <strong>Plan:</strong>
                                            {{ row.subscription.plan.name }}
                                        </p>
                                        <p>
                                            <strong>Precio:</strong>
                                            {{ row.subscription.plan.currency }}
                                            {{
                                                formatNumber(
                                                    row.subscription.plan.price
                                                )
                                            }}
                                        </p>
                                        <p>
                                            <strong>Periodo:</strong>
                                            {{
                                                row.subscription.plan
                                                    .invoice_interval
                                            }}
                                        </p>
                                        <p>
                                            <strong>Estado:</strong>
                                            {{
                                                getStatusText(
                                                    row.subscription.status
                                                )
                                            }}
                                            <el-tag
                                                v-if="
                                                    row.subscription.is_active
                                                "
                                                type="success"
                                                size="mini"
                                                class="ml-2"
                                                >Activo</el-tag
                                            >
                                            <el-tag
                                                v-if="
                                                    row.subscription.is_on_trial
                                                "
                                                type="warning"
                                                size="mini"
                                                class="ml-2"
                                                >En prueba</el-tag
                                            >
                                            <el-tag
                                                v-if="
                                                    row.subscription.is_canceled
                                                "
                                                type="danger"
                                                size="mini"
                                                class="ml-2"
                                                >Cancelado</el-tag
                                            >
                                            <el-tag
                                                v-if="row.subscription.is_ended"
                                                type="info"
                                                size="mini"
                                                class="ml-2"
                                                >Finalizado</el-tag
                                            >
                                        </p>
                                        <p>
                                            <strong>Fecha inicio:</strong>
                                            {{
                                                formatDate(
                                                    row.subscription.starts_at
                                                )
                                            }}
                                        </p>
                                        <p>
                                            <strong>Fecha fin:</strong>
                                            {{
                                                formatDate(
                                                    row.subscription.ends_at
                                                )
                                            }}
                                        </p>

                                        <div
                                            v-if="
                                                row.subscription
                                                    .plan_is_lifetime
                                            "
                                            class="mt-2"
                                        >
                                            <el-tag type="success"
                                                >Plan de por vida</el-tag
                                            >
                                        </div>

                                        <div
                                            v-if="row.subscription.plan_is_free"
                                            class="mt-2"
                                        >
                                            <el-tag type="info"
                                                >Plan gratuito</el-tag
                                            >
                                        </div>

                                        <div
                                            v-if="
                                                row.subscription.plan_has_trial
                                            "
                                            class="mt-2"
                                        >
                                            <el-tag type="warning"
                                                >Incluye período de
                                                prueba</el-tag
                                            >
                                        </div>

                                        <div
                                            v-if="
                                                row.subscription.plan_has_grace
                                            "
                                            class="mt-2"
                                        >
                                            <el-tag type="primary"
                                                >Incluye período de
                                                gracia</el-tag
                                            >
                                        </div>

                                        <div
                                            v-if="
                                                hasDiscounts(
                                                    row.subscription.plan
                                                        .discounts
                                                )
                                            "
                                            class="discounts-section"
                                        >
                                            <h5>Descuentos disponibles:</h5>
                                            <ul>
                                                <li
                                                    v-for="(discount,
                                                    index) in parseDiscounts(
                                                        row.subscription.plan
                                                            .discounts
                                                    )"
                                                    :key="index"
                                                    v-if="discount.value > 0"
                                                >
                                                    {{ discount.name }}: S/
                                                    {{
                                                        formatNumber(
                                                            discount.value
                                                        )
                                                    }}
                                                    <span v-if="discount.months"
                                                        >({{
                                                            discount.months
                                                        }}
                                                        meses)</span
                                                    >
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <span slot="reference" class="badge badge-info">
                                    {{ row.subscription.plan.name }} -
                                    {{ row.subscription.plan.currency }}
                                    {{
                                        formatNumber(
                                            row.subscription.plan.price
                                        )
                                    }}
                                    -
                                    {{ row.subscription.plan.invoice_interval }}
                                </span>
                            </el-popover>
                            <span v-else class="badge badge-secondary"
                                >Sin plan</span
                            >
                        </td>

                        <td>
                            <span
                                class="badge"
                                :style="{
                                    backgroundColor: row.estadoTuc.color
                                        ? row.estadoTuc.color
                                        : '#000000',
                                    color: getContrastYIQ(
                                        row.estadoTuc.color
                                            ? row.estadoTuc.color
                                            : '#000000'
                                    ),
                                    padding: '5px 8px',
                                    borderRadius: '4px',
                                    fontWeight: '500',
                                    fontSize: '0.85em'
                                }"
                            >
                                {{ row.estadoTuc.descripcion }}
                            </span>
                        </td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button
                                    class="btn btn-default btn-sm"
                                    type="button"
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div
                                    class="dropdown-menu"
                                    aria-labelledby="dropdownMenuButton"
                                >
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="clickEdit(row.id)"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="clickDelete(row.id)"
                                    >
                                        Eliminar
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="
                                            openSubscriptionModal(
                                                row,
                                                row.plan_id
                                                    ? 'change'
                                                    : 'create'
                                            )
                                        "
                                    >
                                        {{
                                            row.plan_id
                                                ? "Cambiar plan"
                                                : "Registrar plan"
                                        }}
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="openConductorModal(row)"
                                    >
                                        <i class="fas fa-user"></i>
                                        {{
                                            row.conductor
                                                ? "Cambiar conductor"
                                                : "Asignar conductor"
                                        }}
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="viewServices(row)"
                                    >
                                        <i class="fas fa-tools"></i> Ver
                                        Servicios
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="crearContrato(row)"
                                    >
                                        <i class="fas fa-file-contract"></i>
                                        Crear Contrato
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>

            <unidades-form
                :api_service_token="api_service_token"
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></unidades-form>
            <subscription-modal
                :showModal.sync="showSubscriptionModal"
                :vehiculo="selectedVehiculo"
                :type="subscriptionType"
                @saved="onSubscriptionSaved"
            />

            <!-- Modal de Conductor -->
            <conductor-modal
                :showDialog.sync="showConductorModal"
                :vehiculo="selectedVehiculoForConductor"
                @conductor-vinculado="onConductorVinculado"
            />

            <!-- Modal de Servicios -->
            <el-dialog
                title="Servicios del Vehículo"
                :visible.sync="showServicesModal"
                width="60%"
                :close-on-click-modal="false"
            >
                <div v-if="selectedVehicleForServices">
                    <h4 class="mb-3">
                        <i class="fas fa-car"></i>
                        {{ selectedVehicleForServices.placa }} -
                        {{ selectedVehicleForServices.numero_interno }}
                    </h4>

                    <div
                        v-if="vehicleServices.length === 0"
                        class="text-center py-4"
                    >
                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                        <p class="text-muted">
                            No hay servicios registrados para este vehículo
                        </p>
                    </div>

                    <div v-else>
                        <div class="services-list">
                            <div
                                v-for="service in vehicleServices"
                                :key="service.id"
                                class="service-card mb-3"
                            >
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="mb-2">
                                                    <i
                                                        class="fas fa-wrench text-primary"
                                                    ></i>
                                                    {{ service.name }}
                                                </h5>
                                                <p class="mb-1">
                                                    <strong
                                                        >Fecha de
                                                        vencimiento:</strong
                                                    >
                                                    {{
                                                        formatDate(
                                                            service.expires_date
                                                        )
                                                    }}
                                                </p>
                                                <p
                                                    class="mb-1"
                                                    v-if="service.description"
                                                >
                                                    <strong
                                                        >Descripción:</strong
                                                    >
                                                    {{ service.description }}
                                                </p>
                                                <p
                                                    class="mb-1"
                                                    v-if="service.mobile_phone"
                                                >
                                                    <strong>Teléfono:</strong>
                                                    {{ service.mobile_phone }}
                                                </p>

                                                <!-- Barra de progreso -->
                                                <div
                                                    class="progress-container mt-3"
                                                >
                                                    <div
                                                        class="d-flex justify-content-between mb-1"
                                                    >
                                                        <span
                                                            class="progress-label"
                                                        >
                                                            {{
                                                                getProgressLabel(
                                                                    service.dias_restantes
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            class="progress-days"
                                                        >
                                                            {{
                                                                getProgressDaysText(
                                                                    service.dias_restantes
                                                                )
                                                            }}
                                                        </span>
                                                    </div>
                                                    <el-progress
                                                        :percentage="
                                                            getProgressPercentage(
                                                                service.dias_restantes
                                                            )
                                                        "
                                                        :status="
                                                            getProgressStatus(
                                                                service.dias_restantes
                                                            )
                                                        "
                                                        :stroke-width="8"
                                                    ></el-progress>
                                                </div>

                                                <!-- Estado del servicio -->
                                                <div class="mt-2">
                                                    <el-tag
                                                        :type="
                                                            getServiceStatusType(
                                                                service.dias_restantes
                                                            )
                                                        "
                                                        size="small"
                                                    >
                                                        {{
                                                            getServiceStatusText(
                                                                service.dias_restantes
                                                            )
                                                        }}
                                                    </el-tag>

                                                    <el-tag
                                                        v-if="
                                                            service.event_sent
                                                        "
                                                        type="info"
                                                        size="small"
                                                        class="ml-2"
                                                    >
                                                        Notificación enviada
                                                    </el-tag>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <span slot="footer" class="dialog-footer">
                    <el-button @click="showServicesModal = false"
                        >Cerrar</el-button
                    >
                </span>
            </el-dialog>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import UnidadesForm from "./form.vue";

import SubscriptionModal from "./subscription-modal.vue";
import ConductorModal from "./conductor-modal.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisUnidadesIndex",
    props: ["estado", "api_service_token", "configuration"],
    mixins: [deletable],
    components: {
        DataTable,
        UnidadesForm,
        SubscriptionModal,
        ConductorModal
    },
    created() {
        this.getColumnsToShow();
    },
    data() {
        return {
            showDialog: false,
            resource: "unidades",
            recordId: null,
            showSubscriptionModal: false,
            selectedVehiculo: null,
            subscriptionType: "create",
            showServicesModal: false,
            selectedVehicleForServices: null,
            vehicleServices: [],
            loadingServices: false,
            showConductorModal: false,
            selectedVehiculoForConductor: null,
            estadosTuc: [
                "TUC",
                "RECIBO",
                "TRAMITE BAJA",
                "PAGO LOGO",
                "NO REGISTRADO",
                "DE BAJA",
                "LIBRE"
            ],
            estados: [
                "ACTIVO",
                "DE BAJA",
                "DE BAJA POR PAGO",
                "SUSPECION POR TRABAJO",
                "RETIRO"
            ],
            columns: {
                numero_interno: {
                    title: "Número Interno",
                    label: "Número Interno",
                    visible: true
                },
                propietario: {
                    title: "Propietario",
                    label: "Propietario",
                    visible: true
                },
                conductor: {
                    title: "Conductor",
                    label: "Conductor",
                    visible: true
                },
                marca: { title: "Marca", label: "Marca", visible: true },
                modelo: { title: "Modelo", label: "Modelo", visible: true },
                year: { title: "Año", label: "Año", visible: true },
                color: { title: "Color", label: "Color", visible: true },
                categoria: {
                    title: "Categoría",
                    label: "Categoría",
                    visible: true
                },
                numero_motor: {
                    title: "Número Motor",
                    label: "Número Motor",
                    visible: false
                },
                asientos: {
                    title: "Asientos",
                    label: "Asientos",
                    visible: false
                },
                ccn: { title: "CCN", label: "CCN", visible: false },
                ejes: { title: "Ejes", label: "Ejes", visible: false },
                fecha_ingreso: {
                    title: "Fecha Ingreso",
                    label: "Fecha Ingreso",
                    visible: false
                },
                largo: {
                    title: "Largo (m)",
                    label: "Largo (m)",
                    visible: false
                },
                ancho: {
                    title: "Ancho (m)",
                    label: "Ancho (m)",
                    visible: false
                },
                alto: { title: "Alto (m)", label: "Alto (m)", visible: false },
                peso: {
                    title: "Peso (kg)",
                    label: "Peso (kg)",
                    visible: false
                },
                carga_util: {
                    title: "Carga Útil (kg)",
                    label: "Carga Útil (kg)",
                    visible: false
                }
            }
        };
    },

    computed: {},
    methods: {
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickEdit(recordId) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        closeDialog() {
            this.showDialog = false;
            this.recordId = null;
            this.$nextTick(() => {
                this.$refs.dataTable.fetchData();
            });
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "unidades_index",
                    updated: updated !== undefined
                })
                .then(response => {
                    if (updated === undefined) {
                        let currentCols = response.data.columns;
                        if (currentCols !== undefined) {
                            this.columns = currentCols;
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        getEstadoClass(estado) {
            switch (estado) {
                case "ACTIVO":
                    return "badge-success";
                case "DE BAJA":
                case "DE BAJA POR PAGO":
                    return "badge-danger";
                case "SUSPECION POR TRABAJO":
                    return "badge-warning";
                case "RETIRO":
                    return "badge-secondary";
                default:
                    return "badge-info";
            }
        },
        getEstadoTucClass(estadoTuc) {
            switch (estadoTuc) {
                case "TUC":
                    return "badge-primary";
                case "RECIBO":
                    return "badge-info";
                case "TRAMITE BAJA":
                    return "badge-warning";
                case "PAGO LOGO":
                    return "badge-success";
                case "DE BAJA":
                    return "badge-danger";
                case "LIBRE":
                    return "badge-secondary";
                case "NO REGISTRADO":
                default:
                    return "badge-light";
            }
        },
        formatDate(dateString) {
            console.log("Formatting date:", dateString);

            if (!dateString) return "";

            // Si la fecha viene en formato dd/MM/yyyy (como "04/09/2025")
            if (dateString.includes("/") && dateString.length === 10) {
                const parts = dateString.split("/");
                if (parts.length === 3) {
                    // Convertir de dd/MM/yyyy a yyyy-MM-dd para que JavaScript lo entienda
                    const day = parts[0];
                    const month = parts[1];
                    const year = parts[2];
                    const isoDate = `${year}-${month}-${day}`;
                    const date = new Date(isoDate);

                    // Verificar si la fecha es válida
                    if (!isNaN(date.getTime())) {
                        return date.toLocaleDateString("es-ES");
                    }
                }
            }

            // Fallback para otros formatos
            const date = new Date(dateString);
            if (!isNaN(date.getTime())) {
                return date.toLocaleDateString("es-ES");
            }

            // Si no se puede parsear, devolver la fecha original
            return dateString;
        },
        formatNumber(number, decimals = 2) {
            if (number === null || number === undefined) return "";
            return Number(number).toLocaleString("es-ES", {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        },
        formatDocumentType(type) {
            switch (type) {
                case "DNI":
                    return "DNI";
                case "RUC":
                    return "RUC";
                case "CE":
                    return "Carnet de Extranjería";
                case "PASSPORT":
                    return "Pasaporte";
                default:
                    return type;
            }
        },
        openSubscriptionModal(row, type) {
            this.selectedVehiculo = row;
            this.subscriptionType = type;
            this.showSubscriptionModal = true;
        },
        onSubscriptionSaved() {
            this.showSubscriptionModal = false;
            this.selectedVehiculo = null;
            this.subscriptionType = "create";
            this.$refs.dataTable.getRecords();
        },
        parseDiscounts(discountsStr) {
            try {
                if (!discountsStr) return [];
                return typeof discountsStr === "string"
                    ? JSON.parse(discountsStr)
                    : discountsStr;
            } catch (e) {
                console.error("Error al parsear descuentos:", e);
                return [];
            }
        },
        hasDiscounts(discountsStr) {
            const discounts = this.parseDiscounts(discountsStr);
            return (
                discounts &&
                discounts.length > 0 &&
                discounts.some(d => d.value > 0)
            );
        },
        getStatusText(status) {
            const statusMap = {
                unpaid: "Pendiente de pago",
                paid: "Pagado",
                active: "Activo",
                cancelled: "Cancelado",
                expired: "Expirado"
            };
            return statusMap[status] || status;
        },
        /**
         * Calcula si el texto debe ser blanco o negro basado en el color de fondo
         * @param {string} hexcolor - Color en formato hexadecimal (#RRGGBB)
         * @returns {string} - Devuelve '#000000' o '#FFFFFF' dependiendo del contraste
         */
        getContrastYIQ(hexcolor) {
            // Si no hay color o no es un formato válido, usar texto oscuro
            if (!hexcolor || !hexcolor.startsWith("#")) {
                return "#000000";
            }

            // Eliminar el # si existe
            hexcolor = hexcolor.replace("#", "");

            // Si es formato corto #RGB, convertir a #RRGGBB
            if (hexcolor.length === 3) {
                hexcolor = hexcolor
                    .split("")
                    .map(char => char + char)
                    .join("");
            }

            // Verificar que tengamos un color hexadecimal válido
            if (!/^[0-9A-F]{6}$/i.test(hexcolor)) {
                return "#000000";
            }

            // Convertir a RGB y calcular luminancia
            const r = parseInt(hexcolor.substr(0, 2), 16);
            const g = parseInt(hexcolor.substr(2, 2), 16);
            const b = parseInt(hexcolor.substr(4, 2), 16);

            // Fórmula YIQ para determinar brillo (más precisa para percepción humana)
            const yiq = (r * 299 + g * 587 + b * 114) / 1000;

            // Usar texto blanco si es oscuro, negro si es claro
            return yiq >= 128 ? "#000000" : "#FFFFFF";
        },

        // Métodos para servicios
        viewServices(vehiculo) {
            this.selectedVehicleForServices = vehiculo;
            this.showServicesModal = true;
            this.loadVehicleServices(vehiculo.id);
        },

        loadVehicleServices(vehiculoId) {
            this.loadingServices = true;
            this.$http
                .get(`/vehicle-services/records?vehicle_id=${vehiculoId}`)
                .then(response => {
                    this.vehicleServices = response.data.data || [];
                })
                .catch(error => {
                    console.error("Error al cargar servicios:", error);
                    this.$message.error(
                        "Error al cargar los servicios del vehículo"
                    );
                })
                .finally(() => {
                    this.loadingServices = false;
                });
        },

        sendServiceNotification(service) {
            this.$http
                .post(`/vehicle-services/${service.id}/send-notification`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(
                            "Notificación enviada correctamente"
                        );
                        this.loadVehicleServices(
                            this.selectedVehicleForServices.id
                        );
                    }
                })
                .catch(error => {
                    console.error("Error al enviar notificación:", error);
                    this.$message.error("Error al enviar la notificación");
                });
        },

        // Métodos para la barra de progreso
        getProgressPercentage(diasRestantes) {
            if (diasRestantes === null || diasRestantes === undefined) return 0;

            // Consideramos 365 días como el 100% (un año completo)
            const maxDias = 365;

            if (diasRestantes <= 0) return 0;
            if (diasRestantes >= maxDias) return 100;

            return Math.round((diasRestantes / maxDias) * 100);
        },

        getProgressStatus(diasRestantes) {
            if (diasRestantes === null || diasRestantes === undefined)
                return "exception";
            if (diasRestantes < 0) return "exception";
            if (diasRestantes <= 7) return "exception";
            if (diasRestantes <= 30) return "warning";
            return "success";
        },

        getProgressLabel(diasRestantes) {
            if (diasRestantes === null || diasRestantes === undefined)
                return "Sin fecha";
            if (diasRestantes < 0) return "Vencido";
            if (diasRestantes <= 7) return "Crítico";
            if (diasRestantes <= 30) return "Próximo a vencer";
            return "Vigente";
        },

        getProgressDaysText(diasRestantes) {
            if (diasRestantes === null || diasRestantes === undefined)
                return "";
            if (diasRestantes < 0)
                return `${Math.abs(diasRestantes)} días vencido`;
            if (diasRestantes === 0) return "Vence hoy";
            if (diasRestantes === 1) return "1 día restante";
            return `${diasRestantes} días restantes`;
        },

        getServiceStatusType(diasRestantes) {
            if (diasRestantes === null || diasRestantes === undefined)
                return "info";
            if (diasRestantes < 0) return "danger";
            if (diasRestantes <= 7) return "danger";
            if (diasRestantes <= 30) return "warning";
            return "success";
        },

        getServiceStatusText(diasRestantes) {
            if (diasRestantes === null || diasRestantes === undefined)
                return "Sin fecha";
            if (diasRestantes < 0) return "Vencido";
            if (diasRestantes <= 7) return "Crítico";
            if (diasRestantes <= 30) return "Por vencer";
            return "Vigente";
        },

        /**
         * Abre la página de contratos para crear un nuevo contrato con el vehículo preseleccionado
         */
        crearContrato(vehiculo) {
            const url = `/contratos?vehiculo_id=${vehiculo.id}`;
            window.open(url, "_blank");
        },

        // Métodos para modal de conductor
        openConductorModal(vehiculo) {
            this.selectedVehiculoForConductor = vehiculo;
            this.showConductorModal = true;
        },

        onConductorVinculado(data) {
            // Recargar los datos de la tabla para mostrar el conductor actualizado
            this.$refs.dataTable.fetchData();
            this.$message.success("Conductor vinculado correctamente");
        }
    }
};
</script>

<style scoped>
.unidades .btn-custom i {
    margin-right: 5px;
}

.unidades .btn-custom.btn-danger {
    background-color: #f56c6c;
}

.unidades .btn-custom.btn-danger:hover {
    background-color: #e64942;
}

.unidades .btn-custom.btn-custom {
    background-color: #409eff;
}

.unidades .btn-custom.btn-custom:hover {
    background-color: #2889e9;
}

.badge-estado,
.badge-estado-tuc {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
    color: white;
}

.badge-success {
    background-color: #67c23a;
}

.badge-danger {
    background-color: #f56c6c;
}

.badge-warning {
    background-color: #e6a23c;
}

.badge-info {
    background-color: #909399;
}

.badge-primary {
    background-color: #409eff;
}

.badge-secondary {
    background-color: #6c757d;
}

.badge-light {
    background-color: #f8f9fa;
    color: #333;
    border: 1px solid #ddd;
}

tr.disable_color {
    opacity: 0.5;
}

button.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

button.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.15rem;
}

.btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

/* Estilos para el popover de suscripción */
.subscription-popover {
    font-size: 14px;
}

.subscription-info h4 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #409eff;
    font-weight: bold;
}

.subscription-details p {
    margin-bottom: 8px;
}

.discounts-section {
    margin-top: 15px;
    border-top: 1px solid #eee;
    padding-top: 10px;
}

.discounts-section h5 {
    margin-bottom: 10px;
    font-weight: bold;
    color: #67c23a;
}

.discounts-section ul {
    padding-left: 20px;
    margin-bottom: 0;
}

.discounts-section li {
    margin-bottom: 5px;
}

.ml-2 {
    margin-left: 8px;
}

.mt-2 {
    margin-top: 12px;
}

.el-tag {
    margin-right: 5px;
    font-size: 11px;
}

.el-tag--success {
    background-color: rgba(103, 194, 58, 0.1);
    color: #67c23a;
}

.el-tag--warning {
    background-color: rgba(230, 162, 60, 0.1);
    color: #e6a23c;
}

.el-tag--danger {
    background-color: rgba(245, 108, 108, 0.1);
    color: #f56c6c;
}

.el-tag--info {
    background-color: rgba(144, 147, 153, 0.1);
    color: #909399;
}

.el-tag--primary {
    background-color: rgba(64, 158, 255, 0.1);
    color: #409eff;
}

/* Estilos para el modal de servicios */
.service-card {
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.progress-container {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #409eff;
}

.progress-label {
    font-weight: 600;
    font-size: 14px;
}

.progress-days {
    font-size: 12px;
    color: #666;
}

.service-actions .el-button {
    margin-left: 5px;
}

.service-actions .el-button:first-child {
    margin-left: 0;
}

.services-list {
    max-height: 60vh;
    overflow-y: auto;
}

.dialog-footer {
    text-align: right;
}

/* Estilos para conductor */
.conductor-info {
    color: #67c23a;
    font-weight: 500;
}

.conductor-info i {
    margin-right: 5px;
}

.text-gray-400 {
    color: #9ca3af;
    font-style: italic;
}

.text-green-600 {
    color: #059669;
}
</style>
