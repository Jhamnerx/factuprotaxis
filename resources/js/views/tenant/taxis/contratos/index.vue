<template>
    <div class="contratos">
        <div class="page-header pr-0">
            <h2>Contratos</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Listado de contratos</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <button
                    class="btn btn-custom btn-sm mt-2 mr-2"
                    type="button"
                    @click.prevent="clickCreate()"
                >
                    <i class="fa fa-plus-circle"></i> Nuevo Contrato
                </button>
            </div>
        </div>
        <div class="card tab-content-default row-new mb-0">
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
                        <th>Vehículo</th>
                        <th>Propietario</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Monto Tributo</th>
                        <th>Estado</th>
                        <th class="text-center">PDF</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ row }">
                        <td>{{ row.id }}</td>
                        <td>
                            <div v-if="row.vehiculo.placa">
                                <strong>{{ row.vehiculo.placa }}</strong>
                                <small
                                    class="d-block text-muted"
                                    v-if="row.vehiculo.numero_interno"
                                >
                                    N° Interno:
                                    {{ row.vehiculo.numero_interno }}
                                </small>
                            </div>
                            <span v-else class="text-muted">Sin vehículo</span>
                        </td>
                        <td>
                            <div v-if="row.propietario">
                                <strong>{{ row.propietario.name }}</strong>
                                <small
                                    class="d-block text-muted"
                                    v-if="row.propietario.number"
                                >
                                    Doc: {{ row.propietario.number }}
                                </small>
                            </div>
                        </td>
                        <td>{{ formatDate(row.fecha_inicio) }}</td>
                        <td>
                            {{
                                row.fecha_fin ? formatDate(row.fecha_fin) : "-"
                            }}
                        </td>
                        <td>{{ formatCurrency(row.monto_tributo) }}</td>
                        <td>
                            <el-tag
                                :type="getEstadoType(row.estado)"
                                size="small"
                            >
                                {{ getEstadoLabel(row.estado) }}
                            </el-tag>
                        </td>
                        <td class="text-center">
                            <button
                                class="btn btn-info btn-xs"
                                type="button"
                                @click.prevent="downloadPdf(row.id)"
                                title="Descargar PDF"
                            >
                                <i class="far fa-file-pdf"></i>
                            </button>
                        </td>
                        <td class="text-right">
                            <button
                                class="btn btn-custom btn-xs mr-1"
                                type="button"
                                @click.prevent="clickEdit(row.id)"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                class="btn btn-danger btn-xs"
                                type="button"
                                @click.prevent="clickDelete(row.id)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>

        <!-- Modal de formulario -->
        <contratos-form
            v-if="showDialog"
            :showDialog.sync="showDialog"
            :recordId="recordId"
            :vehiculo-preseleccionado="vehiculoPreseleccionado"
            @close="closeDialog"
        />
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import ContratosForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisContratosIndex",
    mixins: [deletable],
    components: {
        DataTable,
        ContratosForm
    },
    props: ["vehiculoId"], // Para crear contrato desde vehículo
    data() {
        return {
            resource: "contratos",
            showDialog: false,
            recordId: null,
            vehiculoPreseleccionado: null,
            columns: {
                vehiculo: { label: "Vehículo", visible: true },
                propietario: { label: "Propietario", visible: true },
                fecha_inicio: { label: "Fecha Inicio", visible: true },
                fecha_fin: { label: "Fecha Fin", visible: true },
                monto_tributo: { label: "Monto Tributo", visible: true },
                estado: { label: "Estado", visible: true }
            }
        };
    },
    created() {
        // Verificar si hay vehiculo_id en los parámetros de URL
        const urlParams = new URLSearchParams(window.location.search);
        const vehiculoId = urlParams.get("vehiculo_id");

        if (vehiculoId) {
            this.createContratoFromVehicle(vehiculoId);
        }
    },
    methods: {
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.vehiculoPreseleccionado = null;
            this.showDialog = true;
        },
        clickEdit(recordId) {
            this.recordId = recordId;
            this.vehiculoPreseleccionado = null;
            this.showDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$refs.dataTable.fetchData()
            );
        },
        closeDialog() {
            this.showDialog = false;
            this.recordId = null;
            this.vehiculoPreseleccionado = null;
            this.$nextTick(() => {
                this.$refs.dataTable.fetchData();
            });
        },
        createContratoFromVehicle(vehiculoId) {
            this.$http
                .post(`/contratos/create-from-vehicle`, {
                    vehiculo_id: vehiculoId
                })
                .then(response => {
                    if (response.data.success) {
                        this.vehiculoPreseleccionado = response.data.vehiculo;
                        this.recordId = null;
                        this.showDialog = true;
                    }
                })
                .catch(error => {
                    console.error(
                        "Error al obtener datos del vehículo:",
                        error
                    );
                    this.$message.error("Error al obtener datos del vehículo");
                });
        },
        downloadPdf(contratoId) {
            // Usar la ruta definida en Laravel para generar el PDF del contrato
            const url = `/pdf/contrato/${contratoId}`;
            window.open(url, "_blank");
        },
        formatDate(dateString) {
            if (!dateString) return "-";

            // Si ya está en formato dd/MM/yyyy, retornarlo tal como está
            if (/^\d{2}\/\d{2}\/\d{4}$/.test(dateString)) {
                return dateString;
            }

            // Si está en formato yyyy-MM-dd, convertirlo
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                const [year, month, day] = dateString.split("-");
                return `${day}/${month}/${year}`;
            }

            // Para otros formatos, intentar parsear con Date
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) {
                    return dateString;
                }
                return date.toLocaleDateString("es-ES");
            } catch (error) {
                return dateString;
            }
        },
        formatCurrency(amount) {
            if (!amount) return "S/ 0.00";
            return `S/ ${parseFloat(amount).toFixed(2)}`;
        },
        getEstadoType(estado) {
            switch (estado) {
                case "activo":
                    return "success";
                case "finalizado":
                    return "info";
                case "cancelado":
                    return "danger";
                default:
                    return "info";
            }
        },
        getEstadoLabel(estado) {
            switch (estado) {
                case "activo":
                    return "Activo";
                case "finalizado":
                    return "Finalizado";
                case "cancelado":
                    return "Cancelado";
                default:
                    return estado;
            }
        }
    }
};
</script>

<style scoped>
.contratos .btn-custom i {
    margin-right: 5px;
}

.contratos .btn-custom.btn-danger {
    background-color: #f56c6c;
}

.contratos .btn-custom.btn-danger:hover {
    background-color: #e64942;
}

.contratos .btn-custom.btn-custom {
    background-color: #409eff;
}

.contratos .btn-custom.btn-custom:hover {
    background-color: #2889e9;
}

.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.15rem;
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: #fff;
}

.btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
}
</style>
