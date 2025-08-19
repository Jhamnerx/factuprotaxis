<template>
    <div class="row">
        <div class="page-header pr-0 col-12">
            <h2>
                <a href="#"><i class="fas fa-cogs"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Página Web Taxis</span></li>
            </ol>
        </div>

        <div class="col-lg-6 col-md-12 pt-2 pt-md-0">
            <div>
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="my-0">Configuración de Página Web Taxis</h3>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" @submit.prevent="submit">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"
                                                >Titulo Servicio</label
                                            >
                                            <el-input
                                                v-model="form.title_services"
                                            ></el-input>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sección de Servicios -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h4>Servicios</h4>
                                            <el-button
                                                type="primary"
                                                icon="el-icon-plus"
                                                @click="addService"
                                                >Agregar Servicio</el-button
                                            >
                                            <div
                                                v-for="(service,
                                                index) in form.services"
                                                :key="index"
                                                class="card mt-3"
                                            >
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <div
                                                                class="form-group"
                                                            >
                                                                <label
                                                                    class="control-label"
                                                                    >Nombre del
                                                                    Servicio</label
                                                                >
                                                                <el-input
                                                                    v-model="
                                                                        service.name
                                                                    "
                                                                ></el-input>
                                                            </div>
                                                            <div
                                                                class="form-group"
                                                            >
                                                                <label
                                                                    class="control-label"
                                                                    >Descripción</label
                                                                >
                                                                <el-input
                                                                    type="textarea"
                                                                    v-model="
                                                                        service.description
                                                                    "
                                                                    :rows="3"
                                                                ></el-input>
                                                            </div>
                                                            <div
                                                                class="form-group"
                                                            >
                                                                <label
                                                                    class="control-label"
                                                                    >Imagen</label
                                                                >
                                                                <el-upload
                                                                    class="upload-demo"
                                                                    :action="
                                                                        `/${resource}/uploads`
                                                                    "
                                                                    :headers="
                                                                        headers
                                                                    "
                                                                    :data="{
                                                                        type: `service_image_${index}`
                                                                    }"
                                                                    :show-file-list="
                                                                        false
                                                                    "
                                                                    :on-success="
                                                                        (
                                                                            response,
                                                                            file,
                                                                            fileList
                                                                        ) =>
                                                                            successUpload(
                                                                                response,
                                                                                file,
                                                                                fileList,
                                                                                index
                                                                            )
                                                                    "
                                                                    :on-error="
                                                                        errorUpload
                                                                    "
                                                                >
                                                                    <el-button
                                                                        size="small"
                                                                        type="primary"
                                                                        >Subir
                                                                        imagen</el-button
                                                                    >
                                                                    <div
                                                                        slot="tip"
                                                                        class="el-upload__tip"
                                                                    >
                                                                        Solo
                                                                        archivos
                                                                        jpg/png
                                                                        con un
                                                                        tamaño
                                                                        menor de
                                                                        2MB
                                                                    </div>
                                                                </el-upload>
                                                                <div
                                                                    v-if="
                                                                        service.image
                                                                    "
                                                                    class="mt-2"
                                                                >
                                                                    <img
                                                                        :src="
                                                                            service.image_url
                                                                        "
                                                                        style="max-height: 100px"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <el-button
                                                                type="danger"
                                                                icon="el-icon-delete"
                                                                @click="
                                                                    removeService(
                                                                        index
                                                                    )
                                                                "
                                                            ></el-button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección Acerca de -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h4>Acerca de Nosotros</h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Título
                                                            Principal</label
                                                        >
                                                        <el-input
                                                            v-model="
                                                                form.about.title
                                                            "
                                                            placeholder="Ej: Líderes en Gestión de Empresas de Taxis"
                                                        ></el-input>
                                                        <small
                                                            class="text-muted"
                                                            >Puedes usar HTML
                                                            como &lt;br&gt; para
                                                            saltos de línea y
                                                            &lt;span
                                                            class="text-primary"&gt;
                                                            para texto
                                                            destacado</small
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Subtítulo/Badge</label
                                                        >
                                                        <el-input
                                                            v-model="
                                                                form.about
                                                                    .subtitle
                                                            "
                                                            placeholder="Ej: Nuestra Historia"
                                                        ></el-input>
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Texto</label
                                                        >
                                                        <el-input
                                                            type="textarea"
                                                            v-model="
                                                                form.about.text
                                                            "
                                                            :rows="5"
                                                        ></el-input>
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label"
                                                            >Imagen</label
                                                        >
                                                        <el-upload
                                                            class="upload-demo"
                                                            :action="
                                                                `/${resource}/uploads`
                                                            "
                                                            :headers="headers"
                                                            :data="{
                                                                type:
                                                                    'about_image'
                                                            }"
                                                            :show-file-list="
                                                                false
                                                            "
                                                            :on-success="
                                                                successUploadAbout
                                                            "
                                                            :on-error="
                                                                errorUpload
                                                            "
                                                        >
                                                            <el-button
                                                                size="small"
                                                                type="primary"
                                                                >Subir
                                                                imagen</el-button
                                                            >
                                                            <div
                                                                slot="tip"
                                                                class="el-upload__tip"
                                                            >
                                                                Solo archivos
                                                                jpg/png con un
                                                                tamaño menor de
                                                                2MB
                                                            </div>
                                                        </el-upload>
                                                        <div
                                                            v-if="
                                                                form.about.image
                                                            "
                                                            class="mt-2"
                                                        >
                                                            <img
                                                                :src="
                                                                    form.about
                                                                        .image_url
                                                                "
                                                                style="max-height: 100px"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección Imagen de Contacto -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h4>Imagen de Contacto</h4>
                                            <el-upload
                                                class="upload-demo"
                                                :action="`/${resource}/uploads`"
                                                :headers="headers"
                                                :data="{
                                                    type: 'contact_image'
                                                }"
                                                :show-file-list="false"
                                                :on-success="
                                                    successUploadContact
                                                "
                                                :on-error="errorUpload"
                                            >
                                                <el-button
                                                    size="small"
                                                    type="primary"
                                                    >Subir imagen</el-button
                                                >
                                                <div
                                                    slot="tip"
                                                    class="el-upload__tip"
                                                >
                                                    Solo archivos jpg/png con un
                                                    tamaño menor de 2MB
                                                </div>
                                            </el-upload>
                                            <div
                                                v-if="form.contact_image"
                                                class="mt-2"
                                            >
                                                <img
                                                    :src="
                                                        form.contact_image_url
                                                    "
                                                    style="max-height: 100px"
                                                />
                                            </div>
                                        </div>
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
            </div>
        </div>

        <!-- Segunda columna para Client Says y Why Choose -->
        <div class="col-lg-6 col-md-12 pt-2 pt-md-0">
            <div class="card mb-4">
                <div class="card-header bg-info">
                    <h3 class="my-0">Testimonios de Clientes</h3>
                </div>
                <div class="card-body">
                    <form autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Opiniones de Clientes</h4>
                                        <el-button
                                            type="primary"
                                            icon="el-icon-plus"
                                            @click="addClientSay"
                                        >
                                            Agregar Testimonio
                                        </el-button>
                                        <div
                                            v-for="(clientSay,
                                            index) in form.client_says"
                                            :key="'client-' + index"
                                            class="card mt-3"
                                        >
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label"
                                                                >Nombre del
                                                                Cliente</label
                                                            >
                                                            <el-input
                                                                v-model="
                                                                    clientSay.name
                                                                "
                                                            ></el-input>
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label"
                                                                >Texto del
                                                                Testimonio</label
                                                            >
                                                            <el-input
                                                                type="textarea"
                                                                v-model="
                                                                    clientSay.text
                                                                "
                                                                :rows="3"
                                                            ></el-input>
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label"
                                                                >Imagen del
                                                                Cliente</label
                                                            >
                                                            <el-upload
                                                                class="upload-demo"
                                                                :action="
                                                                    `/${resource}/uploads`
                                                                "
                                                                :headers="
                                                                    headers
                                                                "
                                                                :data="{
                                                                    type: `client_image_${index}`
                                                                }"
                                                                :show-file-list="
                                                                    false
                                                                "
                                                                :on-success="
                                                                    (
                                                                        response,
                                                                        file,
                                                                        fileList
                                                                    ) =>
                                                                        successUploadClient(
                                                                            response,
                                                                            file,
                                                                            fileList,
                                                                            index
                                                                        )
                                                                "
                                                                :on-error="
                                                                    errorUpload
                                                                "
                                                            >
                                                                <el-button
                                                                    size="small"
                                                                    type="primary"
                                                                    >Subir
                                                                    imagen</el-button
                                                                >
                                                                <div
                                                                    slot="tip"
                                                                    class="el-upload__tip"
                                                                >
                                                                    Solo
                                                                    archivos
                                                                    jpg/png con
                                                                    un tamaño
                                                                    menor de 2MB
                                                                </div>
                                                            </el-upload>
                                                            <div
                                                                v-if="
                                                                    clientSay.image
                                                                "
                                                                class="mt-2"
                                                            >
                                                                <img
                                                                    :src="
                                                                        clientSay.image_url
                                                                    "
                                                                    style="max-height: 100px"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <el-button
                                                            type="danger"
                                                            icon="el-icon-delete"
                                                            @click="
                                                                removeClientSay(
                                                                    index
                                                                )
                                                            "
                                                        ></el-button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="my-0">¿Por qué Elegirnos?</h3>
                </div>
                <div class="card-body">
                    <form autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Razones para Elegirnos</h4>
                                        <div
                                            v-for="(reason,
                                            index) in form.why_choose"
                                            :key="'reason-' + index"
                                            class="card mt-3"
                                        >
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label"
                                                                >Título</label
                                                            >
                                                            <el-input
                                                                v-model="
                                                                    reason.title
                                                                "
                                                            ></el-input>
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                class="control-label"
                                                                >Descripción</label
                                                            >
                                                            <el-input
                                                                type="textarea"
                                                                v-model="
                                                                    reason.description
                                                                "
                                                                :rows="2"
                                                            ></el-input>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
    computed: {
        ...mapState(["config"])
    },
    data() {
        return {
            loading_submit: false,
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            },
            resource: "web-taxis",
            errors: {},
            form: {
                id: null,
                title_services: "",
                services: [],
                about: {
                    title: "",
                    subtitle: "",
                    text: "",
                    image: null
                },
                contact_image: null,
                client_says: [],
                why_choose: [
                    { title: "", description: "" },
                    { title: "", description: "" },
                    { title: "", description: "" }
                ]
            }
        };
    },
    async created() {
        await this.initForm();
        await this.getRecord();
    },
    methods: {
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                title_services: "",
                services: [],
                about: {
                    title: "",
                    subtitle: "",
                    text: "",
                    image: null,
                    image_url: null
                },
                contact_image: null,
                contact_image_url: null,
                client_says: [],
                why_choose: [
                    { title: "", description: "" },
                    { title: "", description: "" },
                    { title: "", description: "" }
                ]
            };
        },
        async getRecord() {
            await this.$http
                .get(`/${this.resource}/record`)
                .then(response => {
                    if (response.data !== "") {
                        this.form = response.data.data;
                        console.log("Datos cargados:", this.form);
                    }
                })
                .catch(error => {
                    console.error(error);
                    this.$message.error("Error al cargar los datos");
                });
        },
        addService() {
            this.form.services.push({
                name: "",
                description: "",
                image: null
            });
        },
        removeService(index) {
            this.form.services.splice(index, 1);
        },
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.$message.error("Error al guardar los datos");
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        successUpload(response, file, fileList, index) {
            if (response.success) {
                this.$message.success(response.message);
                this.form.services[index].image = response.name;
            } else {
                this.$message.error(
                    response.message || "Error al subir la imagen"
                );
            }
        },
        successUploadAbout(response, file, fileList) {
            if (response.success) {
                this.$message.success(response.message);
                this.form.about.image = response.name;
            } else {
                this.$message.error(
                    response.message || "Error al subir la imagen"
                );
            }
        },
        successUploadContact(response, file, fileList) {
            if (response.success) {
                this.$message.success(response.message);
                this.form.contact_image = response.name;
            } else {
                this.$message.error(
                    response.message || "Error al subir la imagen"
                );
            }
        },
        addClientSay() {
            this.form.client_says.push({
                name: "",
                text: "",
                image: null,
                image_url: null
            });
        },
        removeClientSay(index) {
            this.form.client_says.splice(index, 1);
        },
        successUploadClient(response, file, fileList, index) {
            if (response.success) {
                this.$message.success(response.message);
                this.form.client_says[index].image = response.name;
                // Establecemos temporalmente la URL para mostrarla en la vista previa
                this.form.client_says[index].image_url = URL.createObjectURL(
                    file.raw
                );
            } else {
                this.$message.error(
                    response.message || "Error al subir la imagen"
                );
            }
        },
        errorUpload(error) {
            this.$message.error("Error al subir el archivo");
        }
    }
};
</script>
