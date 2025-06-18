<template>
    <div class="unidades">
        <div class="page-header pr-0">
            <h2>Unidades</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Listado de unidades</span>
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
                                    'badge-estado ' + getEstadoClass(row.estado)
                                "
                                >{{ row.estado }}</span
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
                                            <strong>Precio:</strong> S/
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
                                    {{ row.subscription.plan.name }} - S/
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
                                :class="
                                    'badge-estado-tuc ' +
                                        getEstadoTucClass(row.estado_tuc)
                                "
                                >{{ row.estado_tuc || "NO REGISTRADO" }}</span
                            >
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
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import UnidadesForm from "./form.vue";
import SubscriptionModal from "./subscription-modal.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisUnidadesIndex",
    props: ["estado", "api_service_token", "configuration"],
    mixins: [deletable],
    components: { DataTable, UnidadesForm, SubscriptionModal },
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
            if (!dateString) return "";
            const date = new Date(dateString);
            return date.toLocaleDateString("es-ES");
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
</style>
