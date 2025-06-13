<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible.sync="showDialog"
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
                    <div class="col-lg-3 col-sm-6 align-self-end">
                        <div
                            :class="{
                                'has-danger': errors.vehiculo_id
                            }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Vehiculo
                                <span class="text-danger">*</span></label
                            >
                            <el-select
                                v-model="form.vehiculo_id"
                                :loading="loading_search"
                                :remote-method="searchRemoteVehicles"
                                class="border-left rounded-left border-info"
                                dusk="vehicle_id"
                                filterable
                                @focus="focus_on_vehicle = true"
                                @blur="focus_on_vehicle = false"
                                placeholder="Escriba el nombre o número de documento del vehiculo"
                                popper-class="el-select-vehicles"
                                remote
                                @change="changeVehicle"
                                @keyup.enter.native="keyupVehicle"
                            >
                                <el-option
                                    v-for="option in vehiculos"
                                    :key="option.id"
                                    :label="
                                        option.placa +
                                            ' - ' +
                                            option.propietario.name
                                    "
                                    :value="option.id"
                                ></el-option>
                            </el-select>
                            <small
                                v-if="errors.vehicle_id"
                                class="form-control-feedback"
                                v-text="errors.vehicle_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 align-self-end">
                        <div
                            :class="{
                                'has-danger': errors.tipo_permiso
                            }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Tipo Permiso
                                <el-tooltip
                                    class="item"
                                    content="Tipo de permiso otorgado al vehiculo"
                                    effect="dark"
                                    placement="top-end"
                                >
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model="form.tipo_permiso"></el-input>
                            <small
                                v-if="errors.tipo_permiso"
                                class="form-control-feedback"
                                v-text="errors.tipo_permiso[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 align-self-end">
                        <div
                            :class="{
                                'has-danger': errors.fecha_inicio
                            }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Fec. Inicio
                                <span class="text-danger">*</span></label
                            >
                            <el-date-picker
                                v-model="form.fecha_inicio"
                                :clearable="false"
                                type="date"
                                value-format="yyyy-MM-dd"
                            ></el-date-picker>
                            <small
                                v-if="errors.fecha_inicio"
                                class="form-control-feedback"
                                v-text="errors.fecha_inicio[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 align-self-end">
                        <div
                            :class="{
                                'has-danger': errors.fecha_fin
                            }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Fec. Fin
                                <span class="text-danger">*</span></label
                            >
                            <el-date-picker
                                v-model="form.fecha_fin"
                                :clearable="false"
                                type="date"
                                value-format="yyyy-MM-dd"
                            ></el-date-picker>
                            <small
                                v-if="errors.fecha_fin"
                                class="form-control-feedback"
                                v-text="errors.fecha_fin[0]"
                            ></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div
                        :class="{
                            'has-danger': errors.motivo
                        }"
                        class="form-group col-md-12"
                    >
                        <label class="control-label"
                            >Motivo <span class="text-danger">*</span></label
                        >
                        <el-input
                            type="textarea"
                            v-model="form.motivo"
                        ></el-input>
                        <small
                            v-if="errors.motivo"
                            class="form-control-feedback"
                            v-text="errors.motivo[0]"
                        ></small>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">
                            Personas Autorizadas
                            <span class="text-danger">*</span>
                            <el-tooltip
                                class="item"
                                content="Agregue personas autorizadas para este permiso"
                                effect="dark"
                                placement="top-end"
                            >
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </label>
                        <div class="row">
                            <div class="col-md-5">
                                <el-input
                                    v-model="nuevaPersona.nombre"
                                    placeholder="Nombre"
                                ></el-input>
                            </div>
                            <div class="col-md-5">
                                <el-input
                                    v-model="nuevaPersona.documento"
                                    placeholder="Documento"
                                    maxlength="8"
                                ></el-input>
                            </div>
                            <div class="col-md-2">
                                <el-button
                                    type="primary"
                                    @click="agregarPersona"
                                    >Agregar</el-button
                                >
                            </div>
                        </div>
                        <!-- Tabla HTML en vez de el-table -->
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="persona in form.personas_autorizadas"
                                    :key="persona.id"
                                >
                                    <td>{{ persona.nombre }}</td>
                                    <td>{{ persona.documento }}</td>
                                    <td>
                                        <el-button
                                            type="danger"
                                            size="mini"
                                            @click="quitarPersona(persona.id)"
                                            >Quitar</el-button
                                        >
                                    </td>
                                </tr>
                                <tr
                                    v-if="
                                        !form.personas_autorizadas ||
                                            form.personas_autorizadas.length ===
                                                0
                                    "
                                >
                                    <td colspan="3" class="text-center">
                                        No hay personas autorizadas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <small
                            v-if="errors.personas_autorizadas"
                            class="form-control-feedback"
                            v-text="errors.personas_autorizadas[0]"
                        ></small>
                    </div>
                </div>
                <div class="row">
                    <div
                        :class="{
                            'has-danger': errors.observaciones
                        }"
                        class="form-group col-md-12"
                    >
                        <label class="control-label">
                            Observaciones
                            <el-tooltip
                                class="item"
                                content="Observaciones adicionales sobre el permiso"
                                effect="dark"
                                placement="top-end"
                            >
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </label>
                        <el-input
                            type="textarea"
                            v-model="form.observaciones"
                        ></el-input>
                        <small
                            v-if="errors.observaciones"
                            class="form-control-feedback"
                            v-text="errors.observaciones[0]"
                        ></small>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button class="second-buton" @click.prevent="close()"
                    >Cancelar</el-button
                >
                <el-button
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary"
                    >Guardar
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
            loading_submit: false,
            loading_search: false,
            titleDialog: "Permiso de Unidad",
            loading: false,
            resource: "permisos",
            form: {
                id: null,
                vehiculo_id: null,
                tipo_permiso: "",
                fecha_inicio: "",
                fecha_fin: "",
                motivo: "",
                observaciones: "",
                personas_autorizadas: [] // Debe ser array
            },
            vehiculos: [],
            propietarios: [],
            errors: {},
            nextPersonaId: 1, // Para id incremental
            nuevaPersona: {}
        };
    },
    async created() {
        await this.initForm();
        await this.$http
            .get(`/${this.resource}/tables`)
            .then(response => {
                this.vehiculos = response.data.vehiculos;
                this.propietarios = response.data.propietarios;
            })
            .finally(() => {});
    },
    computed: {},
    methods: {
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                vehiculo_id: null,
                propietario: null,
                vehiculo: null,
                tipo_permiso: "",
                fecha_inicio: new Date().toISOString().slice(0, 10),
                fecha_fin: new Date().toISOString().slice(0, 10),
                motivo: "",
                observaciones: "",
                nuevaPersona: {},
                personas_autorizadas: [] // Debe ser array
            };
            this.personaIdCounter = 1;
            this.nuevaPersona = { nombre: "", documento: "" };
        },
        async opened() {},
        async create() {
            this.titleDialog = this.recordId
                ? "Editar Permiso de Unidad"
                : "Crear Permiso de Unidad";

            if (this.recordId) {
                this.$http
                    .get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data;
                        // Si ya hay personas, setear el contador al máximo + 1
                        if (
                            Array.isArray(this.form.personas_autorizadas) &&
                            this.form.personas_autorizadas.length > 0
                        ) {
                            this.personaIdCounter =
                                Math.max(
                                    ...this.form.personas_autorizadas.map(
                                        p => p.id
                                    )
                                ) + 1;
                        } else {
                            this.personaIdCounter = 1;
                        }
                    })
                    .then(() => {});
            }
        },

        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        },
        getValidationError() {
            if (!this.form.vehiculo_id) {
                return "El campo Vehículo es obligatorio.";
            }
            if (!this.form.tipo_permiso) {
                return "El campo Tipo Permiso es obligatorio.";
            }
            if (!this.form.fecha_inicio) {
                return "El campo Fecha de Inicio es obligatorio.";
            }
            if (!this.form.fecha_fin) {
                return "El campo Fecha de Fin es obligatorio.";
            }
            if (!this.form.motivo) {
                return "El campo Motivo es obligatorio.";
            }
            if (
                !Array.isArray(this.form.personas_autorizadas) ||
                this.form.personas_autorizadas.length < 1
            ) {
                return "Debe agregar al menos una persona autorizada.";
            }
            return null;
        },
        async submit() {
            const errorMsg = this.getValidationError();
            if (errorMsg) {
                this.$message.error(errorMsg);
                return;
            }
            this.loading_submit = true;
            console.log("Submitting form:", this.form);
            await this.$http
                .post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                        this.close();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        searchRemoteVehicles(input) {
            if (input.length > 0) {
                this.loading_search = true;
                let parameters = `input=${input}`;

                this.$http
                    .get(`/${this.resource}/search/vehiculos?${parameters}`)
                    .then(response => {
                        this.vehiculos = response.data.vehiculos;
                        this.loading_search = false;
                    })
                    .catch(error => {
                        console.error(error);
                    })
                    .finally(() => {
                        this.loading_search = false;
                    });
            } else {
            }
        },
        changeVehicle() {
            let vehiculo = this.vehiculos.find(
                v => v.id === this.form.vehiculo_id
            );

            let propietario = _.find(this.propietarios, {
                id: vehiculo.propietario_id
            });

            if (vehiculo) {
                this.form.vehiculo = vehiculo;
                this.form.propietario = propietario;
            } else {
                this.form.vehiculo = null;
                this.form.propietario = null;
            }
        },
        agregarPersona() {
            if (!this.nuevaPersona.nombre || !this.nuevaPersona.documento) {
                this.$message.error("Debe ingresar nombre y documento");
                return;
            }
            // Validación: documento debe ser de 8 dígitos y solo números
            const doc = this.nuevaPersona.documento;

            if (!/^\d{8}$/.test(doc)) {
                this.$message.error(
                    "El documento debe tener exactamente 8 dígitos numéricos"
                );
                return;
            }

            if (!Array.isArray(this.form.personas_autorizadas)) {
                this.form.personas_autorizadas = [];
            }
            this.form.personas_autorizadas.push({
                id: this.personaIdCounter++,
                nombre: this.nuevaPersona.nombre,
                documento: this.nuevaPersona.documento
            });
            this.nuevaPersona.nombre = "";
            this.nuevaPersona.documento = "";
        },
        quitarPersona(id) {
            console.log("Removing persona with id:", id);
            console.log("Current personas:", this.form.personas_autorizadas);

            this.form.personas_autorizadas = this.form.personas_autorizadas.filter(
                p => p.id !== id
            );
        },
        addPersonaAutorizada() {
            this.form.personas_autorizadas.push({
                id: this.nextPersonaId++,
                nombre: "",
                documento: ""
            });
        },
        removePersonaAutorizada(id) {
            this.form.personas_autorizadas = this.form.personas_autorizadas.filter(
                p => p.id !== id
            );
        },
        resetForm() {
            this.errors = {};
            this.form = {
                id: null,
                vehiculo_id: null,
                propietario: null,
                vehiculo: null,
                tipo_permiso: "",
                fecha_inicio: new Date().toISOString().slice(0, 10),
                fecha_fin: new Date().toISOString().slice(0, 10),
                motivo: "",
                observaciones: "",
                personas_autorizadas: [] // Debe ser array
            };
            this.personaIdCounter = 1;
            this.nuevaPersona = { nombre: "", documento: "" };
        }
    }
};
</script>
