<template>
    <div class="contactMessages">
        <div class="page-header pr-0">
            <h2><i class="fas fa-envelope"></i> Mensajes de Contacto</h2>
            <ol class="breadcrumbs">
                <li class="active">Gestión de Mensajes</li>
            </ol>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="contact-stats-card stats-primary">
                    <div class="stats-card-body">
                        <div class="stats-content">
                            <div class="stats-number">
                                <h3 class="stats-title">
                                    {{ stats.total || 0 }}
                                </h3>
                                <p class="stats-label">Total</p>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-envelope fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="contact-stats-card stats-warning">
                    <div class="stats-card-body">
                        <div class="stats-content">
                            <div class="stats-number">
                                <h3 class="stats-title">
                                    {{ stats.pending || 0 }}
                                </h3>
                                <p class="stats-label">Pendientes</p>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="contact-stats-card stats-info">
                    <div class="stats-card-body">
                        <div class="stats-content">
                            <div class="stats-number">
                                <h3 class="stats-title">
                                    {{ stats.read || 0 }}
                                </h3>
                                <p class="stats-label">Leídos</p>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="contact-stats-card stats-success">
                    <div class="stats-card-body">
                        <div class="stats-content">
                            <div class="stats-number">
                                <h3 class="stats-title">
                                    {{ stats.replied || 0 }}
                                </h3>
                                <p class="stats-label">Respondidos</p>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-reply fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters 
        <div class="card tab-content-default row-new mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Buscar:</label>
                        <input
                            type="text"
                            v-model="filters.search"
                            @input="applyFilters"
                            class="form-control"
                            placeholder="Nombre, email o mensaje..."
                        />
                    </div>
                    <div class="col-md-3">
                        <label>Estado:</label>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="form-control"
                        >
                            <option value="">Todos</option>
                            <option value="pending">Pendiente</option>
                            <option value="read">Leído</option>
                            <option value="replied">Respondido</option>
                            <option value="closed">Cerrado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Fecha:</label>
                        <input
                            type="date"
                            v-model="filters.date"
                            @change="applyFilters"
                            class="form-control"
                        />
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <div>
                            <button
                                @click="clearFilters"
                                class="btn btn-secondary btn-block"
                            >
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
-->
        <!-- Messages Table -->
        <div class="card tab-content-default row-new mb-0">
            <div class="card-body">
                <data-table
                    :resource="resource"
                    :columns="columns"
                    ref="dataTable"
                    @click-edit="viewMessage"
                    @click-delete="clickDelete"
                >
                    <tr slot="heading">
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ row }">
                        <td>
                            <div>
                                <strong>{{ row.name }}</strong>
                                <br />
                                <small class="text-muted">{{
                                    truncateMessage(row.message, 50)
                                }}</small>
                            </div>
                        </td>
                        <td>{{ row.email }}</td>
                        <td>{{ row.phone }}</td>
                        <td>
                            <span
                                :class="
                                    'contact-badge contact-badge-' +
                                        row.status_color
                                "
                            >
                                {{ row.status_text }}
                            </span>
                        </td>
                        <td>{{ formatDate(row.created_at) }}</td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button
                                    @click="viewMessage(row)"
                                    class="btn btn-primary btn-xs"
                                    title="Ver mensaje"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    @click="clickDelete(row.id)"
                                    class="btn btn-danger btn-xs"
                                    title="Eliminar"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>

        <!-- Message Detail Modal -->
        <el-dialog
            title=""
            :visible.sync="showModal"
            width="60%"
            :close-on-click-modal="false"
        >
            <template slot="title">
                <span>Mensaje de {{ selectedMessage.name }}</span>
            </template>

            <div v-if="selectedMessage.id">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Email:</strong> {{ selectedMessage.email }}
                    </div>
                    <div class="col-md-6">
                        <strong>Teléfono:</strong> {{ selectedMessage.phone }}
                    </div>
                    <div class="col-md-6">
                        <strong>Estado:</strong>
                        <span
                            :class="
                                'contact-badge contact-badge-' +
                                    selectedMessage.status_color
                            "
                        >
                            {{ selectedMessage.status_text }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha:</strong>
                        {{ formatDate(selectedMessage.created_at) }}
                    </div>
                </div>
                <hr />
                <div>
                    <strong>Mensaje:</strong>
                    <div class="message-content">
                        {{ selectedMessage.message }}
                    </div>
                </div>
                <div v-if="selectedMessage.admin_notes" class="mt-3">
                    <strong>Notas del admin:</strong>
                    <div class="admin-notes">
                        {{ selectedMessage.admin_notes }}
                    </div>
                </div>
                <div class="mt-3">
                    <label><strong>Notas del administrador:</strong></label>
                    <el-input
                        type="textarea"
                        v-model="adminNotes"
                        :rows="3"
                        placeholder="Agregar notas internas..."
                    ></el-input>
                </div>
            </div>

            <span slot="footer" class="dialog-footer">
                <div class="btn-group mr-3">
                    <el-button
                        type="info"
                        @click="updateStatus('read')"
                        :disabled="selectedMessage.status === 'read'"
                    >
                        <i class="fas fa-eye"></i> Marcar como leído
                    </el-button>
                    <el-button
                        type="success"
                        @click="updateStatus('replied')"
                        :disabled="selectedMessage.status === 'replied'"
                    >
                        <i class="fas fa-reply"></i> Marcar como respondido
                    </el-button>
                    <el-button
                        type="info"
                        @click="updateStatus('closed')"
                        :disabled="selectedMessage.status === 'closed'"
                    >
                        <i class="fas fa-times"></i> Cerrar
                    </el-button>
                </div>
                <el-button @click="showModal = false">Cerrar</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
import DataTable from "../../../components/DataTable.vue";
import { deletable } from "../../../mixins/deletable";

export default {
    name: "ContactMessageList",
    mixins: [deletable],
    components: {
        DataTable
    },
    data() {
        return {
            resource: "contactMessages",
            showModal: false,
            selectedMessage: {},
            adminNotes: "",
            stats: {},
            filters: {
                search: "",
                status: "",
                date: ""
            },
            columns: {
                name: {
                    title: "Nombre",
                    label: "Nombre",
                    visible: true
                },
                email: {
                    title: "Email",
                    label: "Email",
                    visible: true
                },
                phone: {
                    title: "Teléfono",
                    label: "Teléfono",
                    visible: true
                },
                status: {
                    title: "Estado",
                    label: "Estado",
                    visible: true
                },
                created_at: {
                    title: "Fecha",
                    label: "Fecha",
                    visible: true
                },
                actions: {
                    title: "Acciones",
                    label: "Acciones",
                    visible: true
                }
            }
        };
    },
    mounted() {
        this.loadStats();
    },
    methods: {
        async loadStats() {
            try {
                const response = await this.$http.get("/contactMessages/stats");
                this.stats = response.data;
            } catch (error) {
                console.error("Error loading stats:", error);
            }
        },

        applyFilters() {
            // Recargar datos con filtros
            this.$refs.dataTable.fetchData(1, {
                search: this.filters.search,
                status: this.filters.status,
                date: this.filters.date
            });
        },

        clearFilters() {
            this.filters = {
                search: "",
                status: "",
                date: ""
            };
            this.$eventHub.$emit("reloadData");
        },

        viewMessage(row) {
            this.selectedMessage = row;
            this.adminNotes = row.admin_notes || "";
            this.showModal = true;

            // Mark as read if it's pending
            if (row.status === "pending") {
                this.updateStatus("read", false);
            }
        },

        async updateStatus(status, closeModal = true) {
            try {
                await this.$http.put(
                    `/contactMessages/${this.selectedMessage.id}/status`,
                    {
                        status: status,
                        admin_notes: this.adminNotes
                    }
                );

                this.$message.success("Estado actualizado correctamente");
                this.$eventHub.$emit("reloadData");
                this.loadStats();

                if (closeModal) {
                    this.showModal = false;
                }
            } catch (error) {
                console.error("Error updating status:", error);
                this.$message.error("Error al actualizar el estado");
            }
        },

        clickDelete(id) {
            this.destroy(`/contactMessages/${id}`).then(() => {
                this.$eventHub.$emit("reloadData");
                this.loadStats();
            });
        },

        truncateMessage(message, length) {
            if (!message || message.length <= length) return message;
            return message.substring(0, length) + "...";
        },

        formatDate(date) {
            if (!date) return "";
            return new Date(date).toLocaleString("es-ES", {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit"
            });
        }
    }
};
</script>

<style scoped>
.contactMessages {
    padding: 0;
}

/* Stats Cards Custom Styles */
.contact-stats-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    margin-bottom: 20px;
    overflow: hidden;
    position: relative;
    border: none;
}

.contact-stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.stats-card-body {
    padding: 25px;
    color: white;
    position: relative;
    z-index: 2;
}

.stats-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stats-number .stats-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: white;
}

.stats-number .stats-label {
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    opacity: 0.9;
    color: white;
}

.stats-icon {
    opacity: 0.7;
}

.stats-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

/* Contact badges */
.contact-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.contact-badge-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.contact-badge-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #74c0fc;
}

.contact-badge-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #51cf66;
}

.contact-badge-secondary {
    background-color: #e2e3e5;
    color: #383d41;
    border: 1px solid #ced4da;
}

/* Message content */
.message-content {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
    font-size: 14px;
    line-height: 1.6;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.admin-notes {
    background: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
    font-size: 14px;
    line-height: 1.6;
}

/* Button styles */
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-group .btn {
    margin-right: 3px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Table responsive */
.card.tab-content-default {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    border: none;
}

/* Dialog footer */
.dialog-footer {
    text-align: right;
}

.dialog-footer .btn-group {
    display: inline-block;
}

/* Responsive design */
@media (max-width: 768px) {
    .stats-content {
        text-align: center;
    }

    .stats-number .stats-title {
        font-size: 2rem;
    }

    .contact-stats-card .stats-card-body {
        padding: 20px;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 5px;
    }
}
</style>
