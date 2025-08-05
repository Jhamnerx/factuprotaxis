<template>
    <div class="yape-notifications">
        <div class="page-header pr-0">
            <h2>Notificaciones Yape</h2>
            <ol class="breadcrumbs">
                <li class="breadcrumb-item">
                    <a href="#">Taxis</a>
                </li>
                <li class="breadcrumb-item active">Notificaciones Yape</li>
            </ol>
            <div class="right-wrapper pull-right">
                <el-button
                    @click="getStatistics"
                    type="info"
                    icon="el-icon-refresh"
                    size="small"
                >
                    Actualizar
                </el-button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-3">
            <div class="card-body">
                <el-form :model="filters" label-width="120px">
                    <el-row :gutter="20">
                        <el-col :span="6">
                            <el-form-item label="Fecha Inicio">
                                <el-date-picker
                                    v-model="filters.date_start"
                                    type="date"
                                    placeholder="Fecha inicio"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    @change="applyFilters"
                                >
                                </el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Fecha Fin">
                                <el-date-picker
                                    v-model="filters.date_end"
                                    type="date"
                                    placeholder="Fecha fin"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    @change="applyFilters"
                                >
                                </el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Estado">
                                <el-select
                                    v-model="filters.status"
                                    placeholder="Todos"
                                    @change="applyFilters"
                                >
                                    <el-option
                                        label="Todos"
                                        value=""
                                    ></el-option>
                                    <el-option
                                        label="Disponible"
                                        :value="0"
                                    ></el-option>
                                    <el-option
                                        label="Usado"
                                        :value="1"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Acciones">
                                <el-button @click="clearFilters" size="small"
                                    >Limpiar</el-button
                                >
                                <el-button
                                    @click="getStatistics"
                                    type="info"
                                    size="small"
                                    >Estadísticas</el-button
                                >
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-form>
            </div>
        </div>

        <!-- Estadísticas -->
        <div v-if="statistics" class="yape-stats-container">
            <div class="yape-stats-grid">
                <div class="yape-custom-card yape-custom-primary">
                    <div class="yape-custom-content">
                        <h4 class="yape-custom-number">
                            {{ statistics.total_notifications || 0 }}
                        </h4>
                        <small class="yape-custom-label">Total</small>
                    </div>
                </div>
                <div class="yape-custom-card yape-custom-success">
                    <div class="yape-custom-content">
                        <h4 class="yape-custom-number">
                            {{ statistics.unused_notifications || 0 }}
                        </h4>
                        <small class="yape-custom-label">Disponibles</small>
                    </div>
                </div>
                <div class="yape-custom-card yape-custom-danger">
                    <div class="yape-custom-content">
                        <h4 class="yape-custom-number">
                            {{ statistics.used_notifications || 0 }}
                        </h4>
                        <small class="yape-custom-label">Usadas</small>
                    </div>
                </div>
                <div class="yape-custom-card yape-custom-info">
                    <div class="yape-custom-content">
                        <h4 class="yape-custom-number">
                            S/ {{ formatNumber(statistics.total_amount || 0) }}
                        </h4>
                        <small class="yape-custom-label">Monto Total</small>
                    </div>
                </div>
                <div class="yape-custom-card yape-custom-warning">
                    <div class="yape-custom-content">
                        <h4 class="yape-custom-number">
                            S/ {{ formatNumber(statistics.unused_amount || 0) }}
                        </h4>
                        <small class="yape-custom-label">Disponible</small>
                    </div>
                </div>
                <div class="yape-custom-card yape-custom-secondary">
                    <div class="yape-custom-content">
                        <h4 class="yape-custom-number">
                            S/ {{ formatNumber(statistics.used_amount || 0) }}
                        </h4>
                        <small class="yape-custom-label">Usado</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card tab-content-default row-new mb-0">
            <div class="card-body">
                <data-table
                    ref="dataTable"
                    :resource="resource"
                    :columns="columns"
                    :filters="tableFilters"
                >
                    <template slot="sender" slot-scope="{ row }">
                        <strong>{{ row.sender }}</strong>
                    </template>
                    <template slot="amount" slot-scope="{ row }">
                        <span class="badge badge-info">{{
                            row.formatted_amount
                        }}</span>
                    </template>
                    <template slot="notification_date" slot-scope="{ row }">
                        {{ row.notification_date_formatted }}
                    </template>
                    <template slot="is_used" slot-scope="{ row }">
                        <span :class="'badge ' + row.status_class">{{
                            row.status_text
                        }}</span>
                    </template>
                    <template slot="used_at" slot-scope="{ row }">
                        {{ row.used_at_formatted || "-" }}
                    </template>
                    <template slot="actions" slot-scope="{ row }">
                        <el-button
                            v-if="!row.is_used"
                            size="mini"
                            type="success"
                            icon="el-icon-check"
                            @click="markAsUsed(row)"
                            title="Marcar como usado"
                        >
                            Marcar como usado
                        </el-button>
                        <span v-else class="text-muted">
                            Ya utilizada
                        </span>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";

export default {
    name: "TenantTaxisYapeNotificationsIndex",
    props: ["configuration"],
    components: {
        DataTable
    },
    data() {
        return {
            resource: "yape-notifications",
            statistics: null,
            filters: {
                date_start: "",
                date_end: "",
                status: "",
                min_amount: "",
                max_amount: ""
            },
            tableFilters: {},
            columns: {
                sender: "Remitente",
                amount: "Monto",
                notification_date: "Fecha Notificación",
                message: "Mensaje",
                codigo_seguridad: "Código Seguridad",
                is_used: "Estado",
                used_at: "Fecha Uso",
                actions: "Acciones"
            }
        };
    },
    created() {
        this.getStatistics();
    },
    methods: {
        applyFilters() {
            this.tableFilters = { ...this.filters };
            this.$refs.dataTable.fetchData();
        },
        clearFilters() {
            this.filters = {
                date_start: "",
                date_end: "",
                status: "",
                min_amount: "",
                max_amount: ""
            };
            this.tableFilters = {};
            this.$refs.dataTable.fetchData();
        },
        getStatistics() {
            this.$http
                .get(`/${this.resource}/statistics`)
                .then(response => {
                    if (response.data.success) {
                        this.statistics = response.data.data;
                    } else {
                        console.error(
                            "Error en respuesta:",
                            response.data.message
                        );
                        // Mostrar estadísticas vacías por defecto
                        this.statistics = {
                            total_notifications: 0,
                            used_notifications: 0,
                            unused_notifications: 0,
                            total_amount: 0,
                            used_amount: 0,
                            unused_amount: 0
                        };
                    }
                })
                .catch(error => {
                    console.error("Error al obtener estadísticas:", error);
                    // Mostrar estadísticas vacías por defecto en caso de error
                    this.statistics = {
                        total_notifications: 0,
                        used_notifications: 0,
                        unused_notifications: 0,
                        total_amount: 0,
                        used_amount: 0,
                        unused_amount: 0
                    };
                });
        },
        markAsUsed(notification) {
            this.$confirm(
                "¿Está seguro de marcar esta notificación como usada?",
                "Confirmar",
                {
                    confirmButtonText: "Sí",
                    cancelButtonText: "No",
                    type: "warning"
                }
            )
                .then(() => {
                    this.$http
                        .post(
                            `/${this.resource}/${notification.id}/mark-as-used`
                        )
                        .then(response => {
                            if (response.data.success) {
                                this.$message.success(response.data.message);
                                this.$refs.dataTable.fetchData();
                                this.getStatistics();
                            } else {
                                this.$message.error(response.data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            this.$message.error(
                                "Error al marcar la notificación"
                            );
                        });
                })
                .catch(() => {
                    // Cancelado
                });
        },
        formatNumber(number, decimals = 2) {
            if (number === null || number === undefined) return "0.00";
            return Number(number).toLocaleString("es-ES", {
                maximumFractionDigits: decimals,
                minimumFractionDigits: decimals
            });
        }
    }
};
</script>

<style scoped>
/* ===== ESTILOS PERSONALIZADOS PARA EVITAR CONFLICTOS ===== */

/* Contenedor principal de estadísticas */
.yape-stats-container {
    margin-bottom: 20px;
}

/* Grid de estadísticas */
.yape-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

/* Tarjetas personalizadas */
.yape-custom-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
    min-height: 100px;
}

.yape-custom-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

/* Contenido de las tarjetas */
.yape-custom-content {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}

/* Números de estadísticas */
.yape-custom-number {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 8px 0;
    line-height: 1.2;
    color: #ffffff;
}

/* Etiquetas de estadísticas */
.yape-custom-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
}

/* Colores específicos para cada tipo de tarjeta */
.yape-custom-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-color: #007bff;
}

.yape-custom-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    border-color: #28a745;
}

.yape-custom-danger {
    background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
    border-color: #dc3545;
}

.yape-custom-info {
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
    border-color: #17a2b8;
}

.yape-custom-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border-color: #ffc107;
}

.yape-custom-warning .yape-custom-number,
.yape-custom-warning .yape-custom-label {
    color: #212529;
}

.yape-custom-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    border-color: #6c757d;
}

/* Badges personalizados */
.yape-notifications .badge {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.badge-success {
    background-color: #28a745;
    color: #ffffff;
}

.badge-danger {
    background-color: #dc3545;
    color: #ffffff;
}

.badge-info {
    background-color: #17a2b8;
    color: #ffffff;
}

/* Mejoras para tarjetas normales */
.card {
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card-body {
    padding: 1.25rem;
}

/* Botones personalizados */
.el-button--mini {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 4px;
}

/* Utilidades de espaciado */
.yape-mb-3 {
    margin-bottom: 1rem !important;
}

.yape-text-center {
    text-align: center !important;
}

.yape-text-muted {
    color: #6c757d !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .yape-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .yape-custom-content {
        padding: 15px;
    }

    .yape-custom-number {
        font-size: 20px;
    }

    .yape-custom-label {
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .yape-stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
