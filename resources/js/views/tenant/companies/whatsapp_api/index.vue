<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">
                Envío de mensajes WhatsApp Cloud Api
                <el-tooltip class="item" effect="dark" placement="top-start">
                    <i class="fa fa-info-circle"></i>
                    <div slot="content">
                        <b>Documentación:</b><br /><br />
                        <a
                            href="https://docs.google.com/document/d/1BW6EQBPH-JQNwoUEQQaFndRteTpNLLVM7w9YIqhKzdM/edit?usp=sharing"
                            class="text-color-white"
                            target="_blank"
                        >
                            https://docs.google.com/document/d/1BW6EQBPH-JQNwoUEQQaFndRteTpNLLVM7w9YIqhKzdM/edit?usp=sharing
                        </a>
                    </div>
                </el-tooltip>
            </h3>
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="row pt-1">
                    <div class="col-md-12 mt-3">
                        <div
                            class="form-group"
                            :class="{
                                'has-danger': errors.ws_api_phone_number_id
                            }"
                        >
                            <label class="control-label"
                                >Identificador de número de teléfono
                                <span class="text-danger">*</span></label
                            >
                            <el-input
                                v-model="form.ws_api_phone_number_id"
                            ></el-input>
                            <small
                                class="form-control-feedback"
                                v-if="errors.ws_api_phone_number_id"
                                v-text="errors.ws_api_phone_number_id[0]"
                            ></small>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.ws_api_token }"
                        >
                            <label class="control-label"
                                >Token de acceso
                                <span class="text-danger">*</span>
                            </label>
                            <el-input
                                v-model="form.ws_api_token"
                                show-password
                            ></el-input>
                            <small
                                class="form-control-feedback"
                                v-if="errors.ws_api_token"
                                v-text="errors.ws_api_token[0]"
                            ></small>
                        </div>
                    </div>
                </div>

                <div class="form-actions text-right pt-2">
                    <el-button
                        type="primary"
                        native-type="submit"
                        :loading="loading_submit"
                        >Guardar</el-button
                    >
                </div>
            </form>
        </div>
    </div>
</template>

<style>
.text-color-white {
    color: #fff !important;
}
</style>

<script>
export default {
    data() {
        return {
            resource: "companies",
            recordId: null,
            form: {},
            errors: {},
            loading_submit: false
        };
    },
    created() {
        this.initForm();
        this.getData();
        this.checkUnofficialApi();
        this.$eventHub.$on(
            "whatsappUnofficialApiActivated",
            this.handleUnofficialApiActivated
        );
    },
    beforeDestroy() {
        this.$eventHub.$off(
            "whatsappUnofficialApiActivated",
            this.handleUnofficialApiActivated
        );
    },
    methods: {
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/store-whatsapp-api`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("whatsappApiActivated");
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
                .then(() => {
                    this.loading_submit = false;
                });
        },
        initForm() {
            this.form = {
                ws_api_token: null,
                ws_api_phone_number_id: null
            };

            this.errors = {};
        },
        getData() {
            this.$http
                .get(`/${this.resource}/record-whatsapp-api`)
                .then(response => {
                    this.form = response.data;
                });
        },
        checkUnofficialApi() {
            this.$http
                .get(`/${this.resource}/record-whatsapp-unofficial`)
                .then(response => {
                    if (
                        response.data.ws_unofficial_api_key ||
                        response.data.ws_unofficial_sender ||
                        response.data.ws_unofficial_url
                    ) {
                        this.$confirm(
                            "La API no oficial de WhatsApp está activa. Si configuras esta API, se desactivará la API no oficial. ¿Deseas continuar?",
                            "Atención",
                            {
                                confirmButtonText: "Continuar",
                                cancelButtonText: "Cancelar",
                                type: "warning"
                            }
                        )
                            .then(() => {
                                // El usuario confirmó continuar
                            })
                            .catch(() => {
                                // El usuario canceló
                                this.$router.go(-1);
                            });
                    }
                });
        },
        handleUnofficialApiActivated() {
            // Limpiar los campos cuando la API no oficial se activa
            this.form = {
                ws_api_token: null,
                ws_api_phone_number_id: null
            };

            this.$http
                .post(`/${this.resource}/store-whatsapp-api`, this.form)
                .then(() => {
                    this.$message.info(
                        "La configuración de la API oficial ha sido desactivada."
                    );
                });
        }
    }
};
</script>
