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
                <div class="row">
                    <div class="col-md-6">
                        <div
                            :class="{ 'has-danger': errors.nombre }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Nombre
                                <span class="text-danger">*</span></label
                            >
                            <el-input
                                v-model="form.nombre"
                                dusk="nombre"
                            ></el-input>
                            <small
                                v-if="errors.nombre"
                                class="form-control-feedback"
                                v-text="errors.nombre[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div
                            :class="{ 'has-danger': errors.marca_id }"
                            class="form-group"
                        >
                            <label class="control-label"
                                >Marca <span class="text-danger">*</span></label
                            >
                            <el-select
                                v-model="form.marca_id"
                                filterable
                                placeholder="Seleccione una marca"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="option in marcas"
                                    :key="option.id"
                                    :label="option.nombre"
                                    :value="option.id"
                                ></el-option>
                            </el-select>
                            <small
                                v-if="errors.marca_id"
                                class="form-control-feedback"
                                v-text="errors.marca_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Estado</label>
                            <el-switch
                                v-model="form.enabled"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                            ></el-switch>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary"
                    @click.prevent="submit"
                >
                    {{ form.id ? "Actualizar" : "Guardar" }}
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import { mapActions, mapState } from "vuex/dist/vuex.mjs";

export default {
    props: ["showDialog", "recordId", "marca_id"],
    data() {
        return {
            titleDialog: "Modelo",
            loading: false,
            loading_submit: false,
            resource: "modelos",
            errors: {},
            form: {},
            marcas: []
        };
    },
    async created() {
        this.loadConfiguration();
        await this.initForm();
    },
    computed: {
        ...mapState(["config"])
    },
    watch: {
        marca_id(newVal) {
            if (newVal && !this.recordId) {
                this.form.marca_id = newVal;
            }
        }
    },
    methods: {
        ...mapActions(["loadConfiguration"]),
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                nombre: null,
                marca_id: this.marca_id || null,
                enabled: true
            };
        },
        async opened() {
            await this.loadMarcas();

            if (this.recordId) {
                this.loading = true;
                await this.fetchRecord();
            }
        },
        create() {
            this.initForm();
        },
        async loadMarcas() {
            await this.$http.get(`/${this.resource}/tables`).then(response => {
                this.marcas = response.data.marcas;
            });
        },
        async fetchRecord() {
            await this.$http
                .get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    this.form = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        async submit() {
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
                        console.error(error);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        }
    }
};
</script>
