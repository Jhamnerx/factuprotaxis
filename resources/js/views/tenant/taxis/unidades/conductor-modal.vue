<template>
    <el-dialog
        :visible.sync="showDialog"
        :title="modalTitle"
        width="500px"
        :before-close="closeModal"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
    >
        <div v-if="vehiculo">
            <!-- Información del vehículo -->
            <div class="vehicle-info mb-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">
                    Información del Vehículo
                </h4>
                <div class="bg-gray-50 p-3 rounded">
                    <p><strong>Placa:</strong> {{ vehiculo.placa }}</p>
                    <p>
                        <strong>N° Interno:</strong>
                        {{ vehiculo.numero_interno }}
                    </p>
                    <p v-if="vehiculo.marca && vehiculo.modelo">
                        <strong>Vehículo:</strong> {{ vehiculo.marca.nombre }}
                        {{ vehiculo.modelo.nombre }}
                    </p>
                    <p v-if="vehiculo.conductor">
                        <strong>Conductor Actual:</strong>
                        {{ vehiculo.conductor.name }}
                    </p>
                    <p v-else class="text-gray-500">
                        <strong>Conductor:</strong> Sin asignar
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <el-form
                :model="form"
                :rules="rules"
                ref="conductorForm"
                label-width="120px"
                label-position="left"
            >
                <el-form-item label="Conductor" prop="conductor_id">
                    <el-select
                        v-model="form.conductor_id"
                        placeholder="Seleccionar conductor"
                        filterable
                        clearable
                        :loading="loadingConductores"
                        style="width: 100%"
                        @change="onConductorChange"
                    >
                        <el-option
                            v-for="conductor in conductoresDisponibles"
                            :key="conductor.id"
                            :label="conductor.name + ' - ' + conductor.number"
                            :value="conductor.id"
                        >
                            <span>{{ conductor.name }}</span>
                            <span
                                style="float: right; color: #8492a6; font-size: 13px"
                            >
                                {{ conductor.number }}
                            </span>
                        </el-option>
                    </el-select>
                </el-form-item>

                <!-- Información del conductor seleccionado -->
                <div v-if="conductorSeleccionado" class="conductor-info mt-4">
                    <h5 class="text-md font-medium text-gray-700 mb-2">
                        Información del Conductor
                    </h5>
                    <div class="bg-blue-50 p-3 rounded">
                        <p>
                            <strong>Nombre:</strong>
                            {{ conductorSeleccionado.name }}
                        </p>
                        <p>
                            <strong>Documento:</strong>
                            {{ conductorSeleccionado.number }}
                        </p>
                        <p v-if="conductorSeleccionado.telephone_1">
                            <strong>Teléfono:</strong>
                            {{ conductorSeleccionado.telephone_1 }}
                        </p>
                        <p
                            v-if="
                                conductorSeleccionado.licencia &&
                                    conductorSeleccionado.licencia.numero
                            "
                        >
                            <strong>Licencia:</strong>
                            {{ conductorSeleccionado.licencia.numero }}
                        </p>
                    </div>
                </div>
            </el-form>
        </div>

        <span slot="footer" class="dialog-footer">
            <el-button @click="closeModal" :disabled="loading">
                Cancelar
            </el-button>
            <el-button type="primary" @click="guardar" :loading="loading">
                {{
                    form.conductor_id
                        ? "Vincular Conductor"
                        : "Desvincular Conductor"
                }}
            </el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    name: "ConductorModal",
    props: {
        showDialog: {
            type: Boolean,
            default: false
        },
        vehiculo: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                conductor_id: null
            },
            rules: {
                // conductor_id no es requerido porque se puede desvincular
            },
            loading: false,
            loadingConductores: false,
            conductoresDisponibles: [],
            conductorSeleccionado: null
        };
    },
    computed: {
        modalTitle() {
            if (!this.vehiculo) return "Vincular Conductor";
            return `Vincular Conductor - ${this.vehiculo.placa}`;
        }
    },
    watch: {
        showDialog(newVal) {
            if (newVal && this.vehiculo) {
                this.loadConductoresDisponibles();
                this.initForm();
            }
        },
        vehiculo: {
            handler(newVal) {
                if (newVal && this.showDialog) {
                    this.initForm();
                }
            },
            deep: true
        }
    },
    methods: {
        initForm() {
            if (this.vehiculo && this.vehiculo.conductor) {
                this.form.conductor_id = this.vehiculo.conductor.id;
                this.conductorSeleccionado = this.vehiculo.conductor;
            } else {
                this.form.conductor_id = null;
                this.conductorSeleccionado = null;
            }
        },

        async loadConductoresDisponibles() {
            this.loadingConductores = true;
            try {
                const response = await this.$http.get(
                    "/unidades/conductores-disponibles",
                    {
                        params: {
                            vehiculo_id: this.vehiculo ? this.vehiculo.id : null
                        }
                    }
                );

                if (response.data.success) {
                    this.conductoresDisponibles = response.data.data;
                } else {
                    this.$message.error(
                        response.data.message || "Error al cargar conductores"
                    );
                }
            } catch (error) {
                console.error("Error loading conductores:", error);
                this.$message.error("Error al cargar la lista de conductores");
            } finally {
                this.loadingConductores = false;
            }
        },

        onConductorChange(conductorId) {
            if (conductorId) {
                this.conductorSeleccionado = this.conductoresDisponibles.find(
                    c => c.id === conductorId
                );
            } else {
                this.conductorSeleccionado = null;
            }
        },

        async guardar() {
            try {
                await this.$refs.conductorForm.validate();

                this.loading = true;

                const response = await this.$http.post(
                    "/unidades/vincular-conductor",
                    {
                        vehiculo_id: this.vehiculo.id,
                        conductor_id: this.form.conductor_id
                    }
                );

                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.$emit("conductor-vinculado", response.data.data);
                    this.$eventHub.$emit("reloadData");
                    this.closeModal();
                } else {
                    this.$message.error(
                        response.data.message || "Error al vincular conductor"
                    );
                }
            } catch (error) {
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.message
                ) {
                    this.$message.error(error.response.data.message);
                } else {
                    this.$message.error("Error al procesar la solicitud");
                }
                console.error("Error vinculando conductor:", error);
            } finally {
                this.loading = false;
            }
        },

        closeModal() {
            this.$emit("update:showDialog", false);
            this.resetForm();
        },

        resetForm() {
            this.form = {
                conductor_id: null
            };
            this.conductorSeleccionado = null;
            if (this.$refs.conductorForm) {
                this.$refs.conductorForm.resetFields();
            }
        }
    }
};
</script>

<style scoped>
.vehicle-info {
    margin-bottom: 20px;
}

.conductor-info {
    margin-top: 15px;
}

.bg-gray-50 {
    background-color: #f9fafb;
}

.bg-blue-50 {
    background-color: #eff6ff;
}

.text-lg {
    font-size: 1.125rem;
}

.text-md {
    font-size: 1rem;
}

.font-semibold {
    font-weight: 600;
}

.font-medium {
    font-weight: 500;
}

.text-gray-800 {
    color: #1f2937;
}

.text-gray-700 {
    color: #374151;
}

.text-gray-500 {
    color: #6b7280;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

.mt-4 {
    margin-top: 1rem;
}

.p-3 {
    padding: 0.75rem;
}

.rounded {
    border-radius: 0.375rem;
}

.dialog-footer {
    text-align: right;
}

.dialog-footer .el-button {
    margin-left: 10px;
}
</style>
