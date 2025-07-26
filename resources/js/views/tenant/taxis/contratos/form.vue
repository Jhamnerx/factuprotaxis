<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible="showDialog"
        append-to-body
        class="pt-0"
        top="7vh"
        width="70%"
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Vehículo *</label>
                            <el-select
                                v-model="form.vehiculo_id"
                                :disabled="!!vehiculoPreseleccionado"
                                :loading="loading_search"
                                :remote-method="searchRemoteVehicles"
                                filterable
                                placeholder="Escriba la placa o número interno del vehículo"
                                remote
                                style="width: 100%"
                                @change="onVehiculoChange"
                            >
                                <el-option
                                    v-for="vehiculo in vehiculos"
                                    :key="vehiculo.id"
                                    :label="
                                        `${vehiculo.placa} - ${
                                            vehiculo.numero_interno
                                        } (${
                                            vehiculo.propietario
                                                ? vehiculo.propietario.name
                                                : 'Sin propietario'
                                        })`
                                    "
                                    :value="vehiculo.id"
                                />
                            </el-select>
                            <small
                                v-if="errors.vehiculo_id"
                                class="text-danger"
                            >
                                {{ errors.vehiculo_id[0] }}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Propietario</label>
                            <div class="form-control-static">
                                <strong v-if="selectedVehiculePropietario">
                                    {{ selectedVehiculePropietario }}
                                </strong>
                                <span v-else class="text-muted">
                                    Seleccione un vehículo para ver el
                                    propietario
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"
                                >Fecha de Inicio *</label
                            >
                            <el-date-picker
                                v-model="form.fecha_inicio"
                                type="date"
                                placeholder="Seleccione fecha de inicio"
                                style="width: 100%"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd"
                                @change="validateFechas"
                            />
                            <small
                                v-if="errors.fecha_inicio"
                                class="text-danger"
                            >
                                {{ errors.fecha_inicio[0] }}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Fecha de Fin</label>
                            <el-date-picker
                                v-model="form.fecha_fin"
                                type="date"
                                placeholder="Seleccione fecha de fin (opcional)"
                                style="width: 100%"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd"
                                :picker-options="pickerOptions"
                                @change="validateFechas"
                            />
                            <small v-if="errors.fecha_fin" class="text-danger">
                                {{ errors.fecha_fin[0] }}
                            </small>
                            <small v-if="fechaFinError" class="text-danger">
                                {{ fechaFinError }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"
                                >Monto de Tributo *</label
                            >
                            <el-input
                                v-model="form.monto_tributo"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                            >
                                <template slot="prepend"
                                    >S/</template
                                >
                            </el-input>
                            <small
                                v-if="errors.monto_tributo"
                                class="text-danger"
                            >
                                {{ errors.monto_tributo[0] }}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Estado *</label>
                            <el-select
                                v-model="form.estado"
                                placeholder="Seleccione estado"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="estado in estados"
                                    :key="estado.value"
                                    :label="estado.label"
                                    :value="estado.value"
                                />
                            </el-select>
                            <small v-if="errors.estado" class="text-danger">
                                {{ errors.estado[0] }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Observaciones</label>
                            <el-input
                                v-model="form.observaciones"
                                type="textarea"
                                :rows="3"
                                placeholder="Ingrese observaciones adicionales..."
                            />
                            <small
                                v-if="errors.observaciones"
                                class="text-danger"
                            >
                                {{ errors.observaciones[0] }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions text-right mt-4">
                <el-button @click="close">Cancelar</el-button>
                <el-button
                    :loading="loading"
                    type="primary"
                    native-type="submit"
                >
                    {{ recordId ? "Actualizar" : "Guardar" }}
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    name: "ContratosForm",
    props: ["showDialog", "recordId", "vehiculoPreseleccionado"],
    data() {
        return {
            titleDialog: "Contrato",
            loading: false,
            loading_search: false,
            resource: "contratos",
            errors: {},
            fechaFinError: null,
            form: {
                vehiculo_id: null,
                fecha_inicio: null,
                fecha_fin: null,
                monto_tributo: 0,
                estado: "activo",
                observaciones: ""
            },
            vehiculos: [],
            selectedVehiculePropietario: null,
            estados: [
                { value: "activo", label: "Activo" },
                { value: "finalizado", label: "Finalizado" },
                { value: "cancelado", label: "Cancelado" }
            ]
        };
    },
    async created() {
        await this.loadTables();
    },
    computed: {
        pickerOptions() {
            return {
                disabledDate: time => {
                    if (!this.form.fecha_inicio) return false;
                    const fechaInicio = new Date(this.form.fecha_inicio);
                    return (
                        time.getTime() <
                        fechaInicio.getTime() - 24 * 60 * 60 * 1000
                    );
                }
            };
        }
    },
    methods: {
        async loadTables() {
            try {
                const response = await this.$http.get(
                    `/${this.resource}/tables`
                );
                const data = response.data;
                this.vehiculos = data.vehiculos || [];

                console.log(
                    "Vehículos cargados inicialmente:",
                    this.vehiculos.length
                );
            } catch (error) {
                console.error("Error al cargar tablas:", error);
                this.$message.error("Error al cargar datos de referencia");
            }
        },

        async searchRemoteVehicles(search) {
            if (search !== "") {
                try {
                    const response = await this.$http.post(
                        "/contratos/search-vehiculos",
                        { search: search }
                    );

                    console.log(
                        "Búsqueda remota de vehículos:",
                        response.data.length
                    );

                    // Añadir vehículos encontrados a la lista sin duplicar
                    response.data.forEach(vehiculo => {
                        const exists = this.vehiculos.find(
                            v => v.id === vehiculo.id
                        );
                        if (!exists) {
                            this.vehiculos.push(vehiculo);
                        }
                    });
                } catch (error) {
                    console.error(
                        "Error en búsqueda remota de vehículos:",
                        error
                    );
                }
            }
        },
        async create() {
            console.log("=== MÉTODO CREATE EJECUTADO ===");
            console.log(
                "vehiculoPreseleccionado:",
                this.vehiculoPreseleccionado
            );
            console.log("recordId:", this.recordId);

            this.titleDialog = this.recordId
                ? "Editar Contrato"
                : "Nuevo Contrato";
            this.errors = {};

            // Resetear formulario
            this.form = {
                vehiculo_id: null,
                propietario_id: null,
                fecha_inicio: null,
                fecha_fin: null,
                monto_tributo: 0,
                estado: "activo",
                observaciones: ""
            };
            console.log("Formulario inicializado:", this.form);

            // Si hay vehículo preseleccionado
            if (this.vehiculoPreseleccionado) {
                // Primero agregar el vehículo preseleccionado al array de vehículos disponibles
                const vehiculoExists = this.vehiculos.find(
                    v => v.id === this.vehiculoPreseleccionado.id
                );
                if (!vehiculoExists) {
                    this.vehiculos.push(this.vehiculoPreseleccionado);
                    console.log(
                        "Vehículo preseleccionado agregado al array:",
                        this.vehiculoPreseleccionado
                    );
                }

                // Luego asignar los valores al formulario
                this.form.vehiculo_id = this.vehiculoPreseleccionado.id;
                this.form.propietario_id = this.vehiculoPreseleccionado.propietario_id;

                // Actualizar el propietario mostrado
                if (this.vehiculoPreseleccionado.propietario) {
                    this.selectedVehiculePropietario = this.vehiculoPreseleccionado.propietario.name;
                }

                console.log("Vehículo preseleccionado aplicado:", {
                    vehiculo_id: this.form.vehiculo_id,
                    propietario_id: this.form.propietario_id,
                    propietario_nombre: this.selectedVehiculePropietario,
                    vehiculos_disponibles: this.vehiculos.length
                });

                // Asegurar que se actualice el propietario
                this.$nextTick(() => {
                    this.onVehiculoChange();
                });
            }

            // Si estamos editando, cargar datos
            if (this.recordId) {
                await this.initForm();
            }
        },
        async initForm() {
            this.loading = true;
            try {
                const response = await this.$http.get(
                    `/${this.resource}/record/${this.recordId}`
                );
                const data = response.data;

                this.form = {
                    vehiculo_id: data.vehiculo_id,
                    propietario_id: data.propietario_id,
                    fecha_inicio: data.fecha_inicio
                        ? this.convertDateToInput(data.fecha_inicio)
                        : null,
                    fecha_fin: data.fecha_fin
                        ? this.convertDateToInput(data.fecha_fin)
                        : null,
                    monto_tributo: data.monto_tributo,
                    estado: data.estado,
                    observaciones: data.observaciones || ""
                };
            } catch (error) {
                console.error("Error al cargar contrato:", error);
                this.$message.error("Error al cargar datos del contrato");
            } finally {
                this.loading = false;
            }
        },
        convertDateToInput(dateString) {
            if (!dateString) return null;

            // Si viene en formato dd/MM/yyyy, convertir a yyyy-MM-dd
            if (/^\d{2}\/\d{2}\/\d{4}$/.test(dateString)) {
                const [day, month, year] = dateString.split("/");
                return `${year}-${month}-${day}`;
            }

            // Si ya está en formato yyyy-MM-dd, retornarlo
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                return dateString;
            }

            return dateString;
        },
        onVehiculoChange() {
            // Si es un vehículo preseleccionado, usar esos datos
            if (
                this.vehiculoPreseleccionado &&
                this.form.vehiculo_id === this.vehiculoPreseleccionado.id
            ) {
                this.form.propietario_id = this.vehiculoPreseleccionado.propietario_id;
                this.selectedVehiculePropietario = this.vehiculoPreseleccionado
                    .propietario
                    ? this.vehiculoPreseleccionado.propietario.name
                    : null;
            } else if (this.form.vehiculo_id) {
                // Buscar si el vehículo tiene propietario asociado
                const vehiculoSeleccionado = this.vehiculos.find(
                    v => v.id === this.form.vehiculo_id
                );
                if (
                    vehiculoSeleccionado &&
                    vehiculoSeleccionado.propietario_id
                ) {
                    this.form.propietario_id =
                        vehiculoSeleccionado.propietario_id;

                    // Actualizar el propietario mostrado
                    this.selectedVehiculePropietario = vehiculoSeleccionado.propietario
                        ? vehiculoSeleccionado.propietario.name
                        : null;
                }
            } else {
                // Limpiar propietario si no hay vehículo seleccionado
                this.form.propietario_id = null;
                this.selectedVehiculePropietario = null;
            }
        },
        validateFechas() {
            this.fechaFinError = null;

            if (this.form.fecha_inicio && this.form.fecha_fin) {
                const fechaInicio = new Date(this.form.fecha_inicio);
                const fechaFin = new Date(this.form.fecha_fin);

                if (fechaFin < fechaInicio) {
                    this.fechaFinError =
                        "La fecha de fin no puede ser menor que la fecha de inicio";
                    this.form.fecha_fin = null;
                }
            }
        },
        async submit() {
            this.loading = true;
            this.errors = {};
            this.fechaFinError = null;

            // Validación de fechas antes del envío
            if (this.form.fecha_inicio && this.form.fecha_fin) {
                const fechaInicio = new Date(this.form.fecha_inicio);
                const fechaFin = new Date(this.form.fecha_fin);

                if (fechaFin < fechaInicio) {
                    this.fechaFinError =
                        "La fecha de fin no puede ser menor que la fecha de inicio";
                    this.loading = false;
                    return;
                }
            }

            console.log("Datos del formulario antes de enviar:", this.form);

            const formData = { ...this.form };
            if (this.recordId) {
                formData.id = this.recordId;
            }

            try {
                const response = await this.$http.post(
                    `/${this.resource}`,
                    formData
                );

                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.$eventHub.$emit("reloadData");
                    this.close();
                } else {
                    this.$message.error("Error al guardar el contrato");
                }
            } catch (error) {
                if (error.response.status === 422) {
                    this.errors = error.response.data;
                } else {
                    console.log(error);
                }
                console.error("Error:", error);
            } finally {
                this.loading = false;
            }
        },
        close() {
            this.$emit("update:showDialog", false);
            this.$emit("close");
        }
    }
};
</script>

<style scoped>
.form-body {
    max-height: 60vh;
    overflow-y: auto;
    padding-right: 10px;
}

.form-body::-webkit-scrollbar {
    width: 6px;
}

.form-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.form-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.form-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.text-danger {
    color: #f56c6c;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}
</style>
