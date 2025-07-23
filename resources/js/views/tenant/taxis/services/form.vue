<template>
    <el-dialog
        :visible.sync="dialogVisible"
        :close-on-click-modal="false"
        :show-close="false"
        :close-on-press-escape="false"
        :title="titleDialog"
        width="65%"
        @close="close"
        @open="create"
    >
        <form @submit.prevent="submit" autocomplete="off">
            <div class="row">
                <!-- Vehículo -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Vehículo*:</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                    ><i class="fas fa-car-side"></i
                                ></span>
                            </div>
                            <el-select
                                v-model="form.device_id"
                                filterable
                                remote
                                autocomplete="off"
                                placeholder="Busque por placa o número interno"
                                :remote-method="searchRemoteVehicles"
                                :loading="loading_search"
                                class="flex-grow-1"
                                @change="changeVehicle"
                                :class="{ 'is-invalid': !validation.device_id }"
                            >
                                <el-option
                                    v-for="vehiculo in vehiculos"
                                    :key="vehiculo.id"
                                    :label="
                                        `${vehiculo.placa} - ${
                                            vehiculo.numero_interno
                                        } (${vehiculo.propietario.name})`
                                    "
                                    :value="vehiculo.id"
                                >
                                </el-option>
                            </el-select>
                        </div>
                        <div
                            v-if="!validation.device_id"
                            class="invalid-feedback"
                        >
                            Por favor seleccione un vehículo
                        </div>
                    </div>
                </div>

                <!-- Tipo de Servicio -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Tipo de Servicio*:</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                    ><i class="fas fa-wrench"></i
                                ></span>
                            </div>
                            <el-select
                                v-model="form.name"
                                autocomplete="off"
                                placeholder="Seleccione el tipo de servicio"
                                class="flex-grow-1"
                                :class="{ 'is-invalid': !validation.name }"
                            >
                                <el-option
                                    label="Mantenimiento"
                                    value="mantenimiento"
                                ></el-option>
                                <el-option
                                    label="SOAT"
                                    value="soat"
                                ></el-option>
                                <el-option
                                    label="AFOCAT"
                                    value="afocat"
                                ></el-option>
                                <el-option
                                    label="Revisión Técnica"
                                    value="revision_tecnica"
                                ></el-option>
                            </el-select>
                        </div>
                        <div v-if="!validation.name" class="invalid-feedback">
                            Por favor seleccione un tipo de servicio
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Nombre personalizado -->
                <div class="col-md-7">
                    <div class="form-group">
                        <label><strong>Nombre personalizado:</strong></label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="form.custom_name"
                            placeholder="Nombre personalizado para este servicio"
                        />
                    </div>
                </div>

                <!-- Generar evento cuando falten -->
                <div class="col-md-5">
                    <div class="form-group">
                        <label
                            ><strong
                                >Generar evento cuando falten:</strong
                            ></label
                        >
                        <el-input-number
                            v-model="form.trigger_event_left"
                            :min="0"
                            :max="365"
                            placeholder="30"
                            @change="calcularFechaRecordatorio"
                            class="w-75"
                        >
                        </el-input-number>
                        <small class="text-muted d-block"
                            >Días antes del vencimiento para generar
                            alerta</small
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Renovar servicio al expirar -->
                    <div class="form-check mb-3 pl-3">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="form.renew_after_expiration"
                            id="renewService"
                        />
                        <label class="form-check-label" for="renewService">
                            Renovar servicio al expirar
                        </label>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Fecha de Vencimiento*:</strong></label>
                        <el-date-picker
                            v-model="form.expires_date"
                            type="date"
                            placeholder="Seleccione fecha"
                            format="dd/MM/yyyy"
                            value-format="yyyy-MM-dd"
                            :class="{ 'is-invalid': !validation.expires_date }"
                            @change="calcularFechaRecordatorio"
                            style="width: 100%"
                        ></el-date-picker>
                        <div
                            v-if="!validation.expires_date"
                            class="invalid-feedback"
                        >
                            Por favor ingrese la fecha de vencimiento
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Fecha de Recordatorio:</strong></label>
                        <el-date-picker
                            v-model="form.remind_date"
                            type="date"
                            placeholder="Se calcula automáticamente"
                            format="dd/MM/yyyy"
                            value-format="yyyy-MM-dd"
                            style="width: 100%"
                            :disabled="true"
                        ></el-date-picker>
                        <small class="text-muted"
                            >Se calcula automáticamente según los días de
                            alerta</small
                        >
                    </div>
                </div>
            </div>

            <!-- Email y Celular -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Email:</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                    ><i class="fas fa-envelope"></i
                                ></span>
                            </div>
                            <input
                                type="email"
                                class="form-control"
                                v-model="form.email"
                                placeholder="ejemplo@correo.com"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Celular:</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                    ><i class="fas fa-mobile-alt"></i
                                ></span>
                            </div>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.mobile_phone"
                                placeholder="987654321"
                                maxlength="9"
                            />
                        </div>
                        <small class="text-muted"
                            >Teléfono para notificaciones WhatsApp</small
                        >
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><strong>Descripción:</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend align-items-start">
                                <span class="input-group-text h-100"
                                    ><i class="fas fa-comment-alt"></i
                                ></span>
                            </div>
                            <textarea
                                class="form-control"
                                v-model="form.description"
                                rows="3"
                                placeholder="Descripción adicional del servicio..."
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group text-right mt-4">
                <button
                    type="button"
                    class="btn btn-secondary mr-2 px-4"
                    @click="close"
                >
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button
                    type="submit"
                    class="btn btn-primary px-4"
                    :disabled="saving"
                >
                    <i class="fa fa-save" v-if="!saving"></i>
                    <i class="fa fa-spinner fa-spin" v-if="saving"></i>
                    {{ saving ? "Guardando..." : "Guardar" }}
                </button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ["recordId", "showDialog"],
    computed: {
        dialogVisible: {
            get() {
                return this.showDialog;
            },
            set(val) {
                this.$emit("update:showDialog", val);
            }
        }
    },
    data() {
        return {
            saving: false,
            loading_search: false,
            titleDialog: "Servicios de Vehículos",
            resource: "vehicle-services",
            errors: {},
            form: {
                id: null,
                device_id: null,
                name: null,
                custom_name: "",
                trigger_event_left: 30,
                renew_after_expiration: false,
                expires_date: "",
                remind_date: "",
                mobile_phone: "",
                email: "",
                description: ""
            },
            validation: {
                device_id: null,
                name: null,
                expires_date: null
            },
            vehiculos: [],
            tiposServicios: {}
        };
    },
    async created() {
        await this.initForm();
        await this.getTables();
    },
    methods: {
        async getTables() {
            try {
                const response = await this.$http.get(
                    "/vehicle-services/tables"
                );
                const data = response.data;
                this.vehiculos = data.vehiculos;
                // Ya no usamos tiposServicios porque ahora son opciones estáticas
            } catch (error) {
                console.error("Error al cargar tablas:", error);
            }
        },
        searchRemoteVehicles(input) {
            if (input.length > 0) {
                this.loading_search = true;
                let parameters = `input=${input}`;

                this.$http
                    .get(`/vehicle-services/search/vehiculos?${parameters}`)
                    .then(response => {
                        this.vehiculos = response.data;
                        this.loading_search = false;
                    })
                    .catch(error => {
                        console.error(error);
                        this.loading_search = false;
                    });
            }
        },
        changeVehicle() {
            const vehiculo = this.vehiculos.find(
                v => v.id === this.form.device_id
            );
            if (vehiculo) {
                // Aquí puedes hacer lo que necesites cuando se cambia el vehículo
                // Por ejemplo, guardar información adicional del vehículo en el formulario
                this.form.vehiculo = vehiculo;
            }
        },
        calcularFechaRecordatorio() {
            if (this.form.expires_date && this.form.trigger_event_left) {
                // Crear una nueva fecha basada en la fecha de vencimiento
                const fechaVencimiento = new Date(this.form.expires_date);
                // Restar los días especificados en trigger_event_left
                fechaVencimiento.setDate(
                    fechaVencimiento.getDate() - this.form.trigger_event_left
                );
                // Formatear la fecha al formato yyyy-MM-dd
                this.form.remind_date = fechaVencimiento
                    .toISOString()
                    .split("T")[0];
            }
        },
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                device_id: null,
                name: null,
                custom_name: "",
                trigger_event_left: 30,
                renew_after_expiration: false,
                expires_date: "",
                remind_date: "",
                mobile_phone: "",
                email: "",
                description: ""
            };
            this.validation = {
                device_id: null,
                name: null,
                expires_date: null
            };
        },

        async create() {
            this.titleDialog = this.recordId
                ? "Editar Servicio de Vehículo"
                : "Crear Servicio de Vehículo";

            if (this.recordId) {
                try {
                    const response = await this.$http.get(
                        `/${this.resource}/record/${this.recordId}`
                    );

                    this.form = response.data.data;

                    // Si el registro ya tiene un vehículo seleccionado, asegurarse de que esté en la lista
                    if (this.form.device_id && response.data.data.vehiculo) {
                        const vehiculoEncontrado = this.vehiculos.find(
                            v => v.id === this.form.device_id
                        );
                        if (!vehiculoEncontrado) {
                            this.vehiculos.push(response.data.data.vehiculo);
                        }
                    }
                } catch (error) {
                    console.error("Error al cargar registro:", error);
                    this.$message({
                        message: "Error al cargar el registro",
                        type: "error"
                    });
                }
            }
        },

        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        },

        async submit() {
            if (!this.validateForm()) {
                return;
            }

            this.saving = true;
            try {
                const response = await this.$http.post(
                    `/${this.resource}`,
                    this.form
                );
                if (response.data.success) {
                    this.$message({
                        message: response.data.message,
                        type: "success"
                    });
                    this.$eventHub.$emit("reloadData");
                    this.close();
                } else {
                    this.$message({
                        message: response.data.message,
                        type: "error"
                    });
                }
            } catch (error) {
                console.error("Error al guardar:", error);
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.errors
                ) {
                    this.errors = error.response.data.errors;
                    let errorMsg = "Errores de validación:\n";
                    Object.keys(this.errors).forEach(key => {
                        errorMsg += `- ${this.errors[key][0]}\n`;
                    });
                    this.$message({
                        message: errorMsg,
                        type: "error"
                    });
                } else {
                    this.$message({
                        message: "Error al guardar el registro",
                        type: "error"
                    });
                }
            } finally {
                this.saving = false;
            }
        },
        validateForm() {
            this.validation = {
                device_id: this.form.device_id ? true : false,
                name: this.form.name ? true : false,
                expires_date: this.form.expires_date ? true : false
            };

            return Object.values(this.validation).every(v => v === true);
        }
    }
};
</script>

<style scoped>
.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.invalid-feedback {
    display: block;
}

.form-group {
    margin-bottom: 1.25rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control,
.el-select,
.el-date-picker {
    border-radius: 4px;
}

/* Mejoras en el formulario */
label strong {
    color: #495057;
}

.btn {
    font-weight: 500;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
}

.el-input-number {
    width: 100%;
}

.form-check {
    margin-top: 0.5rem;
}

/* Ocultar mensajes de autocompletado del navegador */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active,
select:-webkit-autofill,
select:-webkit-autofill:hover,
select:-webkit-autofill:focus,
select:-webkit-autofill:active {
    transition: background-color 5000s ease-in-out 0s;
    -webkit-box-shadow: 0 0 0px 1000px white inset !important;
}
</style>
