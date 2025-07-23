<template>
    <div class="servicios">
        <div class="page-header pr-0">
            <h2>Servicios de Vehículos</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Listado de Servicios</span>
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
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i
                            class="el-icon-arrow-down el-icon--right"
                        ></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item
                            v-for="(column, index) in columns"
                            :key="index"
                        >
                            <el-checkbox
                                v-model="column.visible"
                                @change="getColumnsToShow(true)"
                            >
                                {{ column.label }}
                            </el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table
                    :columns="columns"
                    :resource="resource"
                    @click-create="clickCreate"
                    @click-edit="clickEdit"
                    @click-delete="clickDelete"
                >
                    <tr slot="heading">
                        <th>Vehículo</th>
                        <th>Tipo de Servicio</th>
                        <th>Fecha Vencimiento</th>
                        <th>Días Restantes</th>
                        <th v-if="columns.mobile_phone.visible">Teléfono</th>
                        <th v-if="columns.email.visible">Email</th>
                        <th>Estado Notificación</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ row }">
                        <td>{{ row.vehiculo_placa }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.expires_date }}</td>
                        <td>
                            <span
                                v-if="row.dias_restantes_badge"
                                :class="
                                    `badge badge-${
                                        row.dias_restantes_badge.class
                                    }`
                                "
                            >
                                {{ row.dias_restantes_badge.text }}
                            </span>
                            <span
                                v-else
                                :class="
                                    `badge badge-${getBadgeClass(
                                        row.dias_restantes
                                    )}`
                                "
                            >
                                {{
                                    row.dias_restantes !== null
                                        ? row.dias_restantes + " días"
                                        : "N/A"
                                }}
                            </span>
                        </td>
                        <td v-if="columns.mobile_phone.visible">
                            {{ row.mobile_phone }}
                        </td>
                        <td v-if="columns.email.visible">{{ row.email }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span
                                    :class="
                                        `badge badge-${
                                            row.event_sent_boolean
                                                ? 'success'
                                                : 'secondary'
                                        } mr-2`
                                    "
                                >
                                    {{
                                        row.event_sent_boolean
                                            ? "Enviada"
                                            : "Pendiente"
                                    }}
                                </span>
                                <button
                                    v-if="
                                        row.mobile_phone &&
                                            !row.event_sent_boolean
                                    "
                                    class="btn btn-info btn-xs"
                                    @click="sendNotification(row)"
                                    title="Enviar notificación WhatsApp"
                                >
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span
                                :class="
                                    `badge badge-${
                                        row.expired_boolean
                                            ? 'danger'
                                            : 'success'
                                    }`
                                "
                            >
                                {{
                                    row.expired_boolean ? "Vencido" : "Vigente"
                                }}
                            </span>
                        </td>
                        <td class="text-right">
                            <button
                                class="btn btn-warning btn-xs"
                                @click="clickEdit(row.id)"
                                title="Editar"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                class="btn btn-danger btn-xs"
                                @click="clickDelete(row.id)"
                                title="Eliminar"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <taxis-services-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></taxis-services-form>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import TaxisServicesForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisServicesIndex",
    mixins: [deletable],
    props: ["configuration"],
    components: { DataTable, TaxisServicesForm },
    created() {
        this.getColumnsToShow();
    },
    data() {
        return {
            showDialog: false,
            recordId: null,
            resource: "vehicle-services",
            columns: {
                mobile_phone: {
                    label: "Teléfono",
                    visible: true
                },
                email: {
                    label: "Email",
                    visible: false
                }
            }
        };
    },
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
            this.$eventHub.$emit("reloadData");
        },
        sendNotification(row) {
            this.$http
                .post(`/vehicle-services/${row.id}/send-notification`)
                .then(response => {
                    if (response.data.success) {
                        this.$message({
                            message: response.data.message,
                            type: "success"
                        });
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message({
                            message: response.data.message,
                            type: "error"
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al enviar notificación:", error);
                    this.$message({
                        message: "Error al enviar la notificación",
                        type: "error"
                    });
                });
        },
        getBadgeClass(dias) {
            if (dias === null) return "secondary";
            if (dias < 0) return "danger";
            if (dias <= 7) return "danger";
            if (dias <= 30) return "warning";
            return "success";
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "vehicle_services_index",
                    updated: updated !== undefined
                })
                .then(response => {
                    if (updated === undefined) {
                        let currentCols = response.data.columns;
                        if (currentCols !== undefined) {
                            Object.keys(currentCols).forEach(key => {
                                if (this.columns[key] !== undefined) {
                                    this.columns[key].visible =
                                        currentCols[key].visible;
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
};
</script>
