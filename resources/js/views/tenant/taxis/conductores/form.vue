<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible="showDialog"
        :append-to-body="true"
        @close="close"
        @open="create"
        @opened="opened"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <el-tabs v-model="activeName">
                    <el-tab-pane label="Datos Generales" name="general">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Nombre completo</label
                                    >
                                    <el-input
                                        v-model="form.name"
                                        placeholder="Ingrese el nombre completo"
                                        :class="{ 'is-invalid': errors.name }"
                                        :disabled="loading_submit"
                                    ></el-input>
                                    <div
                                        v-if="errors.name"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Buscar Conductor</label
                                    >
                                    <x-input-service-conductor
                                        v-model="form.number"
                                        @search="searchNumber"
                                    >
                                    </x-input-service-conductor>
                                    <div
                                        v-if="errors.number"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.number[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Fecha de Nacimiento</label
                                    >
                                    <el-date-picker
                                        v-model="form.fecha_nacimiento"
                                        type="date"
                                        placeholder="Seleccionar fecha de nacimiento"
                                        format="dd/MM/yyyy"
                                        value-format="yyyy-MM-dd"
                                        :class="{
                                            'is-invalid':
                                                errors.fecha_nacimiento
                                        }"
                                        :disabled="loading_submit"
                                        style="width: 100%"
                                        :picker-options="{
                                            disabledDate(time) {
                                                return (
                                                    time.getTime() > Date.now()
                                                );
                                            }
                                        }"
                                    ></el-date-picker>
                                    <div
                                        v-if="errors.fecha_nacimiento"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.fecha_nacimiento[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Licencia -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">
                                        Licencia de Conducir
                                    </label>

                                    <div
                                        class="license-item border p-3 mb-3 rounded"
                                    >
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Número</label
                                                    >
                                                    <el-input
                                                        v-model="
                                                            form.licencia.numero
                                                        "
                                                        placeholder="Número de licencia"
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                    ></el-input>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Categoría</label
                                                    >
                                                    <el-input
                                                        v-model="
                                                            form.licencia
                                                                .categoria
                                                        "
                                                        placeholder="Ej: A I, A II, B I"
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                    ></el-input>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Estado</label
                                                    >
                                                    <el-select
                                                        v-model="
                                                            form.licencia.estado
                                                        "
                                                        placeholder="Seleccionar estado"
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                        style="width: 100%"
                                                    >
                                                        <el-option
                                                            label="VIGENTE"
                                                            value="VIGENTE"
                                                        ></el-option>
                                                        <el-option
                                                            label="VENCIDA"
                                                            value="VENCIDA"
                                                        ></el-option>
                                                        <el-option
                                                            label="SUSPENDIDA"
                                                            value="SUSPENDIDA"
                                                        ></el-option>
                                                    </el-select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Fecha de
                                                        Expedición</label
                                                    >
                                                    <el-date-picker
                                                        v-model="
                                                            form.licencia
                                                                .fecha_expedicion
                                                        "
                                                        type="date"
                                                        placeholder="Seleccionar fecha de expedición"
                                                        format="dd/MM/yyyy"
                                                        value-format="yyyy-MM-dd"
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                        style="width: 100%"
                                                        :picker-options="{
                                                            disabledDate(time) {
                                                                return (
                                                                    time.getTime() >
                                                                    Date.now()
                                                                );
                                                            }
                                                        }"
                                                    ></el-date-picker>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Fecha de
                                                        Vencimiento</label
                                                    >
                                                    <el-date-picker
                                                        v-model="
                                                            form.licencia
                                                                .fecha_vencimiento
                                                        "
                                                        type="date"
                                                        placeholder="Seleccionar fecha de vencimiento"
                                                        format="dd/MM/yyyy"
                                                        value-format="yyyy-MM-dd"
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                        style="width: 100%"
                                                    ></el-date-picker>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Restricciones</label
                                                    >
                                                    <el-input
                                                        v-model="
                                                            form.licencia
                                                                .restricciones
                                                        "
                                                        placeholder="Ej: SIN RESTRICCIONES"
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                    ></el-input>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Dirección</label
                                    >
                                    <el-input
                                        v-model="form.address"
                                        placeholder="Ingrese la dirección"
                                        :class="{
                                            'is-invalid': errors.address
                                        }"
                                        :disabled="loading_submit"
                                    ></el-input>
                                    <div
                                        v-if="errors.address"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.address[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Teléfono 1</label
                                    >
                                    <el-input
                                        v-model="form.telephone_1"
                                        placeholder="Teléfono principal"
                                        :class="{
                                            'is-invalid': errors.telephone_1
                                        }"
                                        :disabled="loading_submit"
                                    ></el-input>
                                    <div
                                        v-if="errors.telephone_1"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.telephone_1[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Teléfono 2</label
                                    >
                                    <el-input
                                        v-model="form.telephone_2"
                                        placeholder="Teléfono secundario"
                                        :class="{
                                            'is-invalid': errors.telephone_2
                                        }"
                                        :disabled="loading_submit"
                                    ></el-input>
                                    <div
                                        v-if="errors.telephone_2"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.telephone_2[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Teléfono 3</label
                                    >
                                    <el-input
                                        v-model="form.telephone_3"
                                        placeholder="Teléfono adicional"
                                        :class="{
                                            'is-invalid': errors.telephone_3
                                        }"
                                        :disabled="loading_submit"
                                    ></el-input>
                                    <div
                                        v-if="errors.telephone_3"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.telephone_3[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Correo Electrónico</label
                                    >
                                    <el-input
                                        v-model="form.email"
                                        placeholder="Ingrese el correo electrónico"
                                        :class="{ 'is-invalid': errors.email }"
                                        :disabled="loading_submit"
                                        type="email"
                                    ></el-input>
                                    <div
                                        v-if="errors.email"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Contraseña para acceso al
                                        sistema</label
                                    >
                                    <el-input
                                        v-model="form.password"
                                        placeholder="Dejar vacío para mantener la contraseña actual"
                                        :class="{
                                            'is-invalid': errors.password
                                        }"
                                        :disabled="loading_submit"
                                        type="password"
                                        show-password
                                    ></el-input>
                                    <div
                                        v-if="errors.password"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.password[0] }}
                                    </div>
                                    <small class="text-muted">
                                        Esta contraseña permitirá al conductor
                                        acceder al módulo de taxis
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <el-checkbox
                                        v-model="form.enabled"
                                        :disabled="loading_submit"
                                    >
                                        Habilitado
                                    </el-checkbox>
                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click="close" :disabled="loading_submit"
                    >Cancelar</el-button
                >
                <el-button
                    type="primary"
                    @click="submit"
                    :loading="loading_submit"
                >
                    Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "recordId"],
    data() {
        return {
            form: {},
            resource: "conductores",
            activeName: "general",
            errors: {},
            titleDialog: "Nuevo Conductor",
            loading_submit: false
        };
    },
    async created() {
        await this.initForm();
        await this.$http.get(`/${this.resource}/tables`).then(response => {
            // Datos adicionales si son necesarios
        });
    },
    methods: {
        initForm() {
            this.errors = {};
            this.form = {
                name: "",
                number: "",
                fecha_nacimiento: null,
                licencia: {
                    numero: "",
                    categoria: "",
                    fecha_expedicion: "",
                    fecha_vencimiento: "",
                    estado: "VIGENTE",
                    restricciones: ""
                },
                address: "",
                telephone_1: "",
                telephone_2: "",
                telephone_3: "",
                email: "",
                password: "",
                enabled: true
            };
        },
        opened() {
            // Lógica cuando se abre el diálogo
        },
        create() {
            this.titleDialog = this.recordId
                ? "Editar Conductor"
                : "Nuevo Conductor";
            if (this.recordId) {
                this.fetchRecord();
            }
        },
        fetchRecord() {
            if (this.recordId) {
                this.$http
                    .get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        // Preparar licencia para la edición
                        let licencia = response.data.data.licencia || {};
                        if (licencia.numero) {
                            licencia = {
                                numero: licencia.numero || "",
                                categoria: licencia.categoria || "",
                                fecha_expedicion:
                                    licencia.fecha_expedicion || "",
                                fecha_vencimiento:
                                    licencia.fecha_vencimiento || "",
                                estado: licencia.estado || "VIGENTE",
                                restricciones: licencia.restricciones || ""
                            };
                        } else {
                            licencia = {
                                numero: "",
                                categoria: "",
                                fecha_expedicion: "",
                                fecha_vencimiento: "",
                                estado: "VIGENTE",
                                restricciones: ""
                            };
                        }

                        this.form = {
                            id: response.data.data.id,
                            name: response.data.data.name,
                            number: response.data.data.number,
                            fecha_nacimiento:
                                response.data.data.fecha_nacimiento || null,
                            licencia: licencia,
                            address: response.data.data.address,
                            telephone_1: response.data.data.telephone_1,
                            telephone_2: response.data.data.telephone_2,
                            telephone_3: response.data.data.telephone_3,
                            enabled: response.data.data.enabled
                        };
                    })
                    .catch(error => {
                        console.error(error);
                        this.$message.error(
                            "Error al cargar los datos del conductor"
                        );
                    });
            } else {
                this.initForm();
            }
        },
        async submit() {
            this.loading_submit = true;
            this.errors = {};

            // Preparar licencia antes del envío
            this.prepareLicenciaForSubmit();

            await this.$http
                .post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.close();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data;
                        this.loading_submit = false;
                    } else {
                        this.$message.error(
                            "Error inesperado al guardar el conductor"
                        );
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        searchNumber(data) {
            if (data) {
                // Asignar datos básicos del conductor
                if (data.nombres && data.apellido_paterno) {
                    const nombreCompleto = `${data.nombres} ${
                        data.apellido_paterno
                    } ${data.apellido_materno || ""}`.trim();
                    this.form.name = nombreCompleto;
                }

                if (data.name) {
                    this.form.name = data.name;
                    this.form.address = data.address || "";
                }

                // Asignar número de documento si viene en la respuesta
                if (data.numero_documento) {
                    this.form.number = data.numero_documento;
                }

                // Procesar licencia si viene en la respuesta
                if (
                    data.licencia &&
                    Array.isArray(data.licencia) &&
                    data.licencia.length > 0
                ) {
                    // Tomar solo la primera licencia
                    const licenciaData = data.licencia[0];
                    this.form.licencia = {
                        numero: licenciaData.numero || "",
                        categoria: licenciaData.categoria || "",
                        fecha_expedicion: licenciaData.fecha_expedicion || "",
                        fecha_vencimiento: licenciaData.fecha_vencimiento || "",
                        estado: licenciaData.estado || "VIGENTE",
                        restricciones: licenciaData.restricciones || ""
                    };

                    this.$message.success(
                        "Se encontró licencia para el conductor"
                    );
                } else {
                    this.$message.info(
                        "No se encontró licencia asociada al documento"
                    );
                }
            }
        },
        prepareLicenciaForSubmit() {
            // La licencia ya está en el formato correcto
            // Solo verificar que tenga los campos necesarios
            if (this.form.licencia) {
                // Asegurar que los campos estén presentes
                if (!this.form.licencia.numero) {
                    this.form.licencia.numero = "";
                }
                if (!this.form.licencia.categoria) {
                    this.form.licencia.categoria = "";
                }
                if (!this.form.licencia.fecha_expedicion) {
                    this.form.licencia.fecha_expedicion = "";
                }
                if (!this.form.licencia.fecha_vencimiento) {
                    this.form.licencia.fecha_vencimiento = "";
                }
                if (!this.form.licencia.estado) {
                    this.form.licencia.estado = "VIGENTE";
                }
                if (!this.form.licencia.restricciones) {
                    this.form.licencia.restricciones = "";
                }
            }
        },
        close() {
            this.$emit("update:showDialog", false);
            this.$emit("close");
            this.initForm();
        }
    }
};
</script>

<style scoped>
.license-item {
    background-color: #f9f9f9;
    border: 1px solid #e1e1e1;
}

.license-item:hover {
    background-color: #f5f5f5;
}

.license-item h6 {
    color: #409eff;
    margin-bottom: 0;
}

.invalid-feedback {
    display: block;
    color: #f56c6c;
    font-size: 12px;
    margin-top: 4px;
}

.is-invalid .el-input__inner {
    border-color: #f56c6c;
}
</style>
