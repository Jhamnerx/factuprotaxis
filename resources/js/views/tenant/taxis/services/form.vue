<template>
    <form @submit.prevent="clickSave">
        <div class="row">
            <!-- Vehículo -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Vehículo*:</strong></label>
                    <select
                        class="form-control"
                        v-model="form.device_id"
                        :class="{ 'is-invalid': !validation.device_id }"
                        required
                    >
                        <option value="">-- Seleccionar Vehículo --</option>
                        <option
                            v-for="vehiculo in vehiculos"
                            :key="vehiculo.id"
                            :value="vehiculo.id"
                        >
                            {{ vehiculo.placa }} -
                            {{ vehiculo.numero_interno }} ({{
                                vehiculo.propietario
                            }})
                        </option>
                    </select>
                    <div v-if="!validation.device_id" class="invalid-feedback">
                        Por favor seleccione un vehículo
                    </div>
                </div>
            </div>

            <!-- Tipo de Servicio -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Tipo de Servicio*:</strong></label>
                    <select
                        class="form-control"
                        v-model="form.name"
                        :class="{ 'is-invalid': !validation.name }"
                        required
                    >
                        <option value="">-- Seleccionar Tipo --</option>
                        <option
                            v-for="(label, key) in tiposServicios"
                            :key="key"
                            :value="key"
                        >
                            {{ label }}
                        </option>
                    </select>
                    <div v-if="!validation.name" class="invalid-feedback">
                        Por favor seleccione un tipo de servicio
                    </div>
                </div>
            </div>
        </div>

        <!-- Nombre personalizado -->
        <div class="form-group">
            <label><strong>Nombre personalizado:</strong></label>
            <input
                type="text"
                class="form-control"
                v-model="form.custom_name"
                placeholder="Nombre personalizado para este servicio"
            />
        </div>

        <!-- Expira el -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Expira por:</strong></label>
                    <select class="form-control" v-model="form.expiration_by">
                        <option value="days">Días</option>
                        <option value="kilometers">Kilómetros</option>
                        <option value="hours">Horas</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Intervalo:</strong></label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="form.interval"
                        min="1"
                        placeholder="1"
                    />
                </div>
            </div>
        </div>

        <!-- Último Servicio -->
        <div class="form-group">
            <label><strong>Último servicio:</strong></label>
            <input
                type="text"
                class="form-control"
                v-model="form.last_service"
                placeholder="Descripción del último servicio realizado"
            />
        </div>

        <!-- Generar evento cuando falten -->
        <div class="form-group">
            <label><strong>Generar evento cuando falten:</strong></label>
            <input
                type="number"
                class="form-control"
                v-model="form.trigger_event_left"
                min="0"
                placeholder="30"
            />
            <small class="text-muted"
                >Días antes del vencimiento para generar alerta</small
            >
        </div>

        <!-- Renovar servicio al expirar -->
        <div class="form-check mb-3">
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

        <!-- Fechas -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Fecha de Vencimiento*:</strong></label>
                    <input
                        type="date"
                        class="form-control"
                        v-model="form.expires_date"
                        :class="{ 'is-invalid': !validation.expires_date }"
                        required
                    />
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
                    <input
                        type="date"
                        class="form-control"
                        v-model="form.remind_date"
                    />
                </div>
            </div>
        </div>

        <!-- Odómetro y Horas -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Odómetro actual:</strong></label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="form.current_odometer"
                        step="0.01"
                        placeholder="0.00"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Horas actuales del motor:</strong></label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="form.current_hours"
                        min="0"
                        placeholder="0"
                    />
                </div>
            </div>
        </div>

        <!-- Email y Celular -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Email:</strong></label>
                    <input
                        type="email"
                        class="form-control"
                        v-model="form.email"
                        placeholder="ejemplo@correo.com"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Celular:</strong></label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.mobile_phone"
                        placeholder="987654321"
                    />
                    <small class="text-muted"
                        >Teléfono para notificaciones WhatsApp</small
                    >
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label><strong>Descripción:</strong></label>
            <textarea
                class="form-control"
                v-model="form.description"
                rows="3"
                placeholder="Descripción adicional del servicio..."
            ></textarea>
        </div>

        <div class="form-group text-right">
            <button
                type="button"
                class="btn btn-secondary mr-2"
                @click="$emit('close')"
            >
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary" :disabled="saving">
                <i class="fa fa-save" v-if="!saving"></i>
                <i class="fa fa-spinner fa-spin" v-if="saving"></i>
                {{ saving ? "Guardando..." : "Guardar" }}
            </button>
        </div>
    </form>
</template>

<script>
export default {
    props: ["recordId"],
    data() {
        return {
            saving: false,
            form: {
                id: null,
                device_id: null,
                name: null,
                custom_name: "",
                expiration_by: "days",
                interval: 1,
                last_service: "",
                trigger_event_left: 30,
                renew_after_expiration: false,
                expires_date: "",
                remind_date: "",
                current_odometer: null,
                current_hours: null,
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
    mounted() {
        this.getTables();
        if (this.recordId) {
            this.getRecord();
        }
    },
    methods: {
        async getTables() {
            try {
                const response = await this.$http.get(
                    "/vehicle-services/tables"
                );
                const data = response.data;
                this.vehiculos = data.vehiculos;
                this.tiposServicios = data.tiposServicios;
            } catch (error) {
                console.error("Error al cargar tablas:", error);
            }
        },
        async getRecord() {
            try {
                const response = await this.$http.get(
                    `/vehicle-services/record/${this.recordId}`
                );
                this.form = { ...this.form, ...response.data };
            } catch (error) {
                console.error("Error al cargar registro:", error);
                this.$message({
                    message: "Error al cargar el registro",
                    type: "error"
                });
            }
        },
        async clickSave() {
            if (!this.validateForm()) {
                return;
            }

            this.saving = true;
            try {
                const response = await this.$http.post(
                    "/vehicle-services",
                    this.form
                );
                if (response.data.success) {
                    this.$message({
                        message: response.data.message,
                        type: "success"
                    });
                    this.$emit("close");
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
                    const errors = error.response.data.errors;
                    let errorMsg = "Errores de validación:\n";
                    Object.keys(errors).forEach(key => {
                        errorMsg += `- ${errors[key][0]}\n`;
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
</style>
