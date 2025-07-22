<template>
    <div>
        <card size="7">
            <div slot="header">
                <h3 class="h5 mb-0">
                    <i class="fas fa-tools"></i> Servicios de Vehículos
                </h3>
            </div>
            <data-table
                :columns="[
                    { label: 'Vehículo', name: 'vehiculo_placa' },
                    { label: 'Tipo de Servicio', name: 'service_name' },
                    {
                        label: 'Fecha Vencimiento',
                        name: 'expires_date',
                        type: 'date'
                    },
                    {
                        label: 'Días Restantes',
                        name: 'dias_restantes',
                        type: 'custom'
                    },
                    {
                        label: 'Teléfono',
                        name: 'mobile_phone',
                        is_visible: columns.mobile_phone.visible
                    },
                    {
                        label: 'Email',
                        name: 'email',
                        is_visible: columns.email.visible
                    },
                    {
                        label: 'Estado Notificación',
                        name: 'event_sent',
                        type: 'custom'
                    },
                    {
                        label: 'Estado',
                        name: 'expired',
                        type: 'custom'
                    }
                ]"
                :resource="resource"
                :has_column_options="true"
                :columns_options="columns"
                @GetColumnsToShow="getColumnsToShow"
            >
                <template v-slot:buttons>
                    <button
                        class="btn btn-primary btn-sm"
                        @click="clickCreate()"
                    >
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                </template>

                <template v-slot:dias_restantes="{ row }">
                    <span
                        :class="
                            `badge badge-${getBadgeClass(row.dias_restantes)}`
                        "
                    >
                        {{
                            row.dias_restantes !== null
                                ? row.dias_restantes + " días"
                                : "N/A"
                        }}
                    </span>
                </template>

                <template v-slot:event_sent="{ row }">
                    <div class="d-flex align-items-center">
                        <span
                            :class="
                                `badge badge-${
                                    row.event_sent ? 'success' : 'secondary'
                                } mr-2`
                            "
                        >
                            {{ row.event_sent ? "Enviada" : "Pendiente" }}
                        </span>
                        <button
                            v-if="row.mobile_phone && !row.event_sent"
                            class="btn btn-info btn-xs"
                            @click="sendNotification(row)"
                            title="Enviar notificación WhatsApp"
                        >
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>
                </template>

                <template v-slot:expired="{ row }">
                    <span
                        :class="
                            `badge badge-${row.expired ? 'danger' : 'success'}`
                        "
                    >
                        {{ row.expired ? "Vencido" : "Vigente" }}
                    </span>
                </template>

                <template v-slot:btn_options="{ row }">
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
                </template>
            </data-table>
        </card>

        <app-dialog
            ref="modal"
            :title="'Servicios de Vehículos'"
            :show="showDialog"
            modal-size="modal-xl"
            @close="closeDialog"
        >
            <vehicle-services-form :recordId="recordId" @close="closeDialog" />
        </app-dialog>
    </div>
</template>

<script>
export default {
    mixins: [deletable],
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
    mounted() {
        this.getColumnsToShow();
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
            this.destroy(`/vehicle-services/${id}`).then(() =>
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
