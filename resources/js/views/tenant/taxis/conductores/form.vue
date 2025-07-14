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

                        <!-- Sección de Licencias -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">
                                        Licencias de Conducir
                                        <el-button
                                            type="primary"
                                            size="mini"
                                            icon="el-icon-plus"
                                            @click="addLicense"
                                            :disabled="loading_submit"
                                        >
                                            Agregar Licencia
                                        </el-button>
                                    </label>

                                    <div
                                        v-if="
                                            form.licencias &&
                                                form.licencias.length > 0
                                        "
                                    >
                                        <div
                                            v-for="(licencia,
                                            index) in form.licencias"
                                            :key="index"
                                            class="license-item border p-3 mb-3 rounded"
                                        >
                                            <div class="row mb-2">
                                                <div class="col-md-11">
                                                    <h6>
                                                        Licencia {{ index + 1 }}
                                                    </h6>
                                                </div>
                                                <div
                                                    class="col-md-1 text-right"
                                                >
                                                    <el-button
                                                        type="danger"
                                                        size="mini"
                                                        icon="el-icon-delete"
                                                        @click="
                                                            removeLicense(index)
                                                        "
                                                        :disabled="
                                                            loading_submit
                                                        "
                                                    ></el-button>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Número</label
                                                        >
                                                        <el-input
                                                            v-model="
                                                                licencia.numero
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
                                                        <label
                                                            class="control-label"
                                                            >Categoría</label
                                                        >
                                                        <el-input
                                                            v-model="
                                                                licencia.categoria
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
                                                        <label
                                                            class="control-label"
                                                            >Estado</label
                                                        >
                                                        <el-select
                                                            v-model="
                                                                licencia.estado
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
                                                        <label
                                                            class="control-label"
                                                            >Fecha de
                                                            Expedición</label
                                                        >
                                                        <el-date-picker
                                                            v-model="
                                                                licencia.fecha_expedicion_date
                                                            "
                                                            type="date"
                                                            placeholder="Seleccionar fecha"
                                                            format="dd/MM/yyyy"
                                                            value-format="dd/MM/yyyy"
                                                            :disabled="
                                                                loading_submit
                                                            "
                                                            style="width: 100%"
                                                            @change="
                                                                updateFechaExpedicion(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                        ></el-date-picker>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Fecha de
                                                            Vencimiento</label
                                                        >
                                                        <el-date-picker
                                                            v-model="
                                                                licencia.fecha_vencimiento_date
                                                            "
                                                            type="date"
                                                            placeholder="Seleccionar fecha"
                                                            format="dd/MM/yyyy"
                                                            value-format="dd/MM/yyyy"
                                                            :disabled="
                                                                loading_submit
                                                            "
                                                            style="width: 100%"
                                                            @change="
                                                                updateFechaVencimiento(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                        ></el-date-picker>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Restricciones</label
                                                        >
                                                        <el-input
                                                            v-model="
                                                                licencia.restricciones
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

                                    <div
                                        v-else
                                        class="text-muted text-center p-3 border rounded"
                                    >
                                        No hay licencias registradas. Haga clic
                                        en "Agregar Licencia" para comenzar.
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
                licencias: [],
                address: "",
                telephone_1: "",
                telephone_2: "",
                telephone_3: "",
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
                        // Preparar licencias para la edición
                        let licencias = response.data.licencias || [];
                        if (licencias.length > 0) {
                            licencias = licencias.map(licencia => ({
                                ...licencia,
                                fecha_expedicion_date:
                                    licencia.fecha_expedicion,
                                fecha_vencimiento_date:
                                    licencia.fecha_vencimiento
                            }));
                        }

                        this.form = {
                            id: response.data.id,
                            name: response.data.name,
                            number: response.data.number,
                            licencias: licencias,
                            address: response.data.address,
                            telephone_1: response.data.telephone_1,
                            telephone_2: response.data.telephone_2,
                            telephone_3: response.data.telephone_3,
                            enabled: response.data.enabled
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

            // Preparar licencias antes del envío
            this.prepareLicenciasForSubmit();

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
                        this.errors = error.response.data.errors;
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
        addLicense() {
            this.form.licencias.push({
                numero: "",
                categoria: "",
                fecha_expedicion: "",
                fecha_vencimiento: "",
                fecha_expedicion_date: null,
                fecha_vencimiento_date: null,
                estado: "VIGENTE",
                restricciones: "SIN RESTRICCIONES"
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

                // Asignar número de documento si viene en la respuesta
                if (data.numero_documento) {
                    this.form.number = data.numero_documento;
                }

                // Procesar licencias si vienen en la respuesta
                if (
                    data.licencia &&
                    Array.isArray(data.licencia) &&
                    data.licencia.length > 0
                ) {
                    // Limpiar licencias existentes
                    this.form.licencias = [];

                    // Agregar las licencias de la respuesta
                    data.licencia.forEach(licenciaData => {
                        this.form.licencias.push({
                            numero: licenciaData.numero || "",
                            categoria: licenciaData.categoria || "",
                            fecha_expedicion:
                                licenciaData.fecha_expedicion || "",
                            fecha_vencimiento:
                                licenciaData.fecha_vencimiento || "",
                            fecha_expedicion_date:
                                licenciaData.fecha_expedicion || null,
                            fecha_vencimiento_date:
                                licenciaData.fecha_vencimiento || null,
                            estado: licenciaData.estado || "VIGENTE",
                            restricciones:
                                licenciaData.restricciones ||
                                "SIN RESTRICCIONES"
                        });
                    });

                    this.$message.success(
                        `Se encontraron ${
                            data.licencia.length
                        } licencia(s) para el conductor`
                    );
                } else {
                    this.$message.info(
                        "No se encontraron licencias asociadas al documento"
                    );
                }
            }
        },
        removeLicense(index) {
            this.$confirm(
                "¿Está seguro de eliminar esta licencia?",
                "Confirmar",
                {
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    type: "warning"
                }
            )
                .then(() => {
                    this.form.licencias.splice(index, 1);
                })
                .catch(() => {
                    // Usuario canceló
                });
        },
        updateFechaExpedicion(index, date) {
            if (date) {
                this.form.licencias[index].fecha_expedicion = date;
            }
        },
        updateFechaVencimiento(index, date) {
            if (date) {
                this.form.licencias[index].fecha_vencimiento = date;
            }
        },
        prepareLicenciasForSubmit() {
            // Preparar las licencias para envío al servidor
            if (this.form.licencias) {
                this.form.licencias.forEach(licencia => {
                    // Asegurar que las fechas estén en formato string
                    if (licencia.fecha_expedicion_date) {
                        licencia.fecha_expedicion =
                            licencia.fecha_expedicion_date;
                    }
                    if (licencia.fecha_vencimiento_date) {
                        licencia.fecha_vencimiento =
                            licencia.fecha_vencimiento_date;
                    }

                    // Limpiar campos de fecha temporal
                    delete licencia.fecha_expedicion_date;
                    delete licencia.fecha_vencimiento_date;
                });
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
