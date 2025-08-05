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
        <div class="yape-custom-card yape-custom-mb-3">
            <div class="yape-custom-card-body">
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
                <div class="yape-stat-card yape-card-primary">
                    <div class="yape-card-content">
                        <h4 class="yape-stat-number">
                            {{ statistics.total_notifications || 0 }}
                        </h4>
                        <small class="yape-stat-label">Total</small>
                    </div>
                </div>
                <div class="yape-stat-card yape-card-success">
                    <div class="yape-card-content">
                        <h4 class="yape-stat-number">
                            {{ statistics.unused_notifications || 0 }}
                        </h4>
                        <small class="yape-stat-label">Disponibles</small>
                    </div>
                </div>
                <div class="yape-stat-card yape-card-danger">
                    <div class="yape-card-content">
                        <h4 class="yape-stat-number">
                            {{ statistics.used_notifications || 0 }}
                        </h4>
                        <small class="yape-stat-label">Usadas</small>
                    </div>
                </div>
                <div class="yape-stat-card yape-card-info">
                    <div class="yape-card-content">
                        <h4 class="yape-stat-number">
                            S/ {{ formatNumber(statistics.total_amount || 0) }}
                        </h4>
                        <small class="yape-stat-label">Monto Total</small>
                    </div>
                </div>
                <div class="yape-stat-card yape-card-warning">
                    <div class="yape-card-content">
                        <h4 class="yape-stat-number">
                            S/ {{ formatNumber(statistics.unused_amount || 0) }}
                        </h4>
                        <small class="yape-stat-label">Disponible</small>
                    </div>
                </div>
                <div class="yape-stat-card yape-card-secondary">
                    <div class="yape-card-content">
                        <h4 class="yape-stat-number">
                            S/ {{ formatNumber(statistics.used_amount || 0) }}
                        </h4>
                        <small class="yape-stat-label">Usado</small>
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
                    <tr slot="heading">
                        <th>ID</th>
                        <th>Remitente</th>
                        <th>Monto</th>
                        <th>Fecha Notificación</th>
                        <th>Estado</th>
                        <th>Fecha Uso</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ row }">
                        <td>{{ row.id }}</td>
                        <td>
                            <strong>{{ row.sender }}</strong>
                        </td>
                        <td>
                            <span
                                class="yape-custom-badge yape-custom-badge-info"
                            >
                                S/ {{ formatNumber(row.amount) }}
                            </span>
                        </td>
                        <td>{{ formatDate(row.notification_date) }}</td>
                        <td>
                            <span
                                v-if="row.is_used"
                                class="yape-custom-badge yape-custom-badge-danger"
                                >Usado</span
                            >
                            <span
                                v-else
                                class="yape-custom-badge yape-custom-badge-success"
                                >Disponible</span
                            >
                        </td>
                        <td>
                            {{ row.used_at ? formatDate(row.used_at) : "-" }}
                        </td>
                        <td class="text-right">
                            <button
                                v-if="!row.is_used"
                                @click="markAsUsed(row.id)"
                                class="btn btn-sm btn-success"
                                title="Marcar como usado"
                            >
                                <i class="fa fa-check"></i>
                            </button>
                        </td>
                    </tr>
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
        markAsUsed(notificationId) {
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
                            `/${this.resource}/${notificationId}/mark-as-used`
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
        },
        formatDate(date) {
            if (!date) return "-";
            return new Date(date).toLocaleDateString("es-ES");
        }
    }
};
</script>

<style scoped>
/* Estilos personalizados para evitar conflictos */

/* Cards personalizadas */
.yape-custom-card {
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.15s ease-in-out;
}

.yape-custom-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.yape-custom-card-body {
    padding: 1.25rem;
}

.yape-custom-mb-3 {
    margin-bottom: 1rem !important;
}

/* Badges personalizados */
.yape-custom-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.yape-custom-badge-success {
    color: #fff;
    background-color: #28a745;
}

.yape-custom-badge-danger {
    color: #fff;
    background-color: #dc3545;
}

.yape-custom-badge-info {
    color: #fff;
    background-color: #17a2b8;
}

.yape-custom-badge-warning {
    color: #212529;
    background-color: #ffc107;
}

.yape-custom-badge-primary {
    color: #fff;
    background-color: #007bff;
}

.yape-custom-badge-secondary {
    color: #fff;
    background-color: #6c757d;
}

/* Estadísticas */
.yape-stats-container {
    margin-bottom: 1.5rem;
}

.yape-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.yape-stat-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.yape-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.yape-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.yape-stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.yape-stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
    opacity: 0.8;
}

/* Colores para las tarjetas de estadísticas */
.yape-card-primary {
    border-left: 4px solid #007bff;
}

.yape-card-primary .yape-stat-number {
    color: #007bff;
}

.yape-card-success {
    border-left: 4px solid #28a745;
}

.yape-card-success .yape-stat-number {
    color: #28a745;
}

.yape-card-danger {
    border-left: 4px solid #dc3545;
}

.yape-card-danger .yape-stat-number {
    color: #dc3545;
}

.yape-card-info {
    border-left: 4px solid #17a2b8;
}

.yape-card-info .yape-stat-number {
    color: #17a2b8;
}

.yape-card-warning {
    border-left: 4px solid #ffc107;
}

.yape-card-warning .yape-stat-number {
    color: #e0a800;
}

.yape-card-secondary {
    border-left: 4px solid #6c757d;
}

.yape-card-secondary .yape-stat-number {
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .yape-stats-grid {
        grid-template-columns: 1fr;
    }

    .yape-stat-card {
        padding: 1rem;
    }

    .yape-stat-number {
        font-size: 1.5rem;
    }
}

/* Utilidades */
.text-muted {
    color: #6c757d !important;
}

.text-right {
    text-align: right !important;
}
</style>
