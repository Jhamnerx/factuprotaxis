<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">
                Envío de mensajes WhatsApp API No Oficial
                <el-tooltip class="item" effect="dark" placement="top-start">
                    <i class="fa fa-info-circle"></i>
                    <div slot="content">
                        <b>Documentación:</b><br /><br />
                        <a href="#" class="text-color-white" target="_blank">
                            Consultar documentación de la API no oficial
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
                                'has-danger': errors.ws_unofficial_api_key
                            }"
                        >
                            <label class="control-label"
                                >API Key
                                <span class="text-danger">*</span></label
                            >
                            <el-input
                                v-model="form.ws_unofficial_api_key"
                            ></el-input>
                            <small
                                class="form-control-feedback"
                                v-if="errors.ws_unofficial_api_key"
                                v-text="errors.ws_unofficial_api_key[0]"
                            ></small>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div
                            class="form-group"
                            :class="{
                                'has-danger': errors.ws_unofficial_sender
                            }"
                        >
                            <label class="control-label"
                                >Número de Remitente
                                <span class="text-danger">*</span>
                            </label>
                            <el-input
                                v-model="form.ws_unofficial_sender"
                            ></el-input>
                            <small
                                class="form-control-feedback"
                                v-if="errors.ws_unofficial_sender"
                                v-text="errors.ws_unofficial_sender[0]"
                            ></small>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.ws_unofficial_url }"
                        >
                            <label class="control-label"
                                >URL de la API
                                <span class="text-danger">*</span>
                            </label>
                            <el-input
                                v-model="form.ws_unofficial_url"
                            ></el-input>
                            <small
                                class="form-control-feedback"
                                v-if="errors.ws_unofficial_url"
                                v-text="errors.ws_unofficial_url[0]"
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
        this.checkOfficialApi();
        this.$eventHub.$on(
            "whatsappApiActivated",
            this.handleOfficialApiActivated
        );
    },
    beforeDestroy() {
        this.$eventHub.$off(
            "whatsappApiActivated",
            this.handleOfficialApiActivated
        );
    },
    methods: {
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/store-whatsapp-unofficial`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("whatsappUnofficialApiActivated");
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
                ws_unofficial_api_key: null,
                ws_unofficial_sender: null,
                ws_unofficial_url: null
            };

            this.errors = {};
        },
        getData() {
            this.$http
                .get(`/${this.resource}/record-whatsapp-unofficial`)
                .then(response => {
                    this.form = response.data;
                });
        },
        checkOfficialApi() {
            this.$http
                .get(`/${this.resource}/record-whatsapp-api`)
                .then(response => {
                    if (
                        response.data.ws_api_token ||
                        response.data.ws_api_phone_number_id
                    ) {
                        this.$confirm(
                            "La API oficial de WhatsApp está activa. Si configuras esta API no oficial, se desactivará la API oficial. ¿Deseas continuar?",
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
        handleOfficialApiActivated() {
            // Limpiar los campos cuando la API oficial se activa
            this.form = {
                ws_unofficial_api_key: null,
                ws_unofficial_sender: null,
                ws_unofficial_url: null
            };

            this.$http
                .post(`/${this.resource}/store-whatsapp-unofficial`, this.form)
                .then(() => {
                    this.$message.info(
                        "La configuración de la API no oficial ha sido desactivada."
                    );
                });
        }
    }
};
</script>
