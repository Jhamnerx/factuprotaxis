<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible.sync="showDialog"
        append-to-body
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-lg-4 col-sm-6 align-self-end">
                        <div
                            :class="{ 'has-danger': errors.vehiculo_id }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Vehículo
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
                                placeholder="Escriba el nombre o número de documento del vehículo"
                                popper-class="el-select-vehicles"
                                remote
                                @change="changeVehicle"
                            >
                                <el-option
                                    v-for="option in vehiculos"
                                    :key="option.id"
                                    :label="
                                        option.placa +
                                            ' - ' +
                                            (option.propietario
                                                ? option.propietario.name
                                                : '')
                                    "
                                    :value="option.id"
                                ></el-option>
                            </el-select>
                            <small
                                v-if="errors.vehiculo_id"
                                class="form-control-feedback"
                                v-text="errors.vehiculo_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 align-self-end">
                        <div
                            :class="{ 'has-danger': errors.fecha_emision }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Fecha Emisión
                                <span class="text-danger">*</span></label
                            >
                            <el-date-picker
                                v-model="form.fecha_emision"
                                :clearable="false"
                                type="date"
                                value-format="yyyy-MM-dd"
                            ></el-date-picker>
                            <small
                                v-if="errors.fecha_emision"
                                class="form-control-feedback"
                                v-text="errors.fecha_emision[0]"
                            ></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Observaciones</label>
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
                    >Guardar</el-button
                >
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
            titleDialog: "Constancia de Baja",
            loading: false,
            resource: "constancias",
            form: {
                id: null,
                vehiculo_id: null,
                vehiculo: null,
                estado: "emitida",
                fecha_emision: new Date().toISOString().slice(0, 10),
                observaciones: ""
            },
            vehiculos: [],
            propietarios: [],
            errors: {}
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
    methods: {
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                vehiculo_id: null,
                vehiculo: null,
                estado: "emitida",
                fecha_emision: new Date().toISOString().slice(0, 10),
                observaciones: ""
            };
        },
        async opened() {},
        async create() {
            this.titleDialog = this.recordId
                ? "Editar Constancia de Baja"
                : "Nueva Constancia de Baja";
            if (this.recordId) {
                this.$http
                    .get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data;
                    });
            }
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        },
        async submit() {
            // Validaciones requeridas antes de enviar
            if (!this.form.vehiculo_id) {
                this.$message.error("El campo Vehículo es obligatorio.");
                return;
            }
            if (!this.form.fecha_emision) {
                this.$message.error(
                    "El campo Fecha de Emisión es obligatorio."
                );
                return;
            }
            this.loading_submit = true;
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
            }
        },
        changeVehicle() {
            let vehiculo = this.vehiculos.find(
                v => v.id === this.form.vehiculo_id
            );
            let propietario =
                vehiculo && vehiculo.propietario ? vehiculo.propietario : null;
            if (vehiculo) {
                this.form.vehiculo = vehiculo;
                this.form.propietario = propietario;
            } else {
                this.form.vehiculo = null;
                this.form.propietario = null;
            }
        }
    }
};
</script>
