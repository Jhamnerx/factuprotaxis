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
                                <div
                                    :class="{
                                        'has-danger':
                                            errors.identity_document_type_id
                                    }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Tipo Doc. Identidad
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-select
                                        v-model="form.identity_document_type_id"
                                        dusk="identity_document_type_id"
                                        filterable
                                        popper-class="el-select-identity_document_type"
                                    >
                                        <el-option
                                            v-for="option in identity_document_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.identity_document_type_id"
                                        class="form-control-feedback"
                                        v-text="
                                            errors.identity_document_type_id[0]
                                        "
                                    ></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.number }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Número
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >

                                    <div v-if="api_service_token != false">
                                        <x-input-service
                                            v-model="form.number"
                                            :identity_document_type_id="
                                                form.identity_document_type_id
                                            "
                                            @search="searchNumber"
                                        >
                                        </x-input-service>
                                    </div>
                                    <div v-else>
                                        <el-input
                                            v-model="form.number"
                                            :maxlength="maxLength"
                                            dusk="number"
                                        >
                                            <template
                                                v-if="
                                                    form.identity_document_type_id ===
                                                        '6' ||
                                                        form.identity_document_type_id ===
                                                            '1'
                                                "
                                            >
                                                <el-button
                                                    slot="append"
                                                    :loading="loading_search"
                                                    icon="el-icon-search"
                                                    type="primary"
                                                    @click.prevent="
                                                        searchCustomer
                                                    "
                                                >
                                                    <template
                                                        v-if="
                                                            form.identity_document_type_id ===
                                                                '6'
                                                        "
                                                    >
                                                        SUNAT
                                                    </template>
                                                    <template
                                                        v-if="
                                                            form.identity_document_type_id ===
                                                                '1'
                                                        "
                                                    >
                                                        RENIEC
                                                    </template>
                                                </el-button>
                                            </template>
                                        </el-input>
                                    </div>

                                    <small
                                        v-if="errors.number"
                                        class="form-control-feedback"
                                        v-text="errors.number[0]"
                                    ></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.name }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Nombre
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-input
                                        v-model="form.name"
                                        dusk="name"
                                    ></el-input>
                                    <small
                                        v-if="errors.name"
                                        class="form-control-feedback"
                                        v-text="errors.name[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div
                                    :class="{
                                        'has-danger': errors.fecha_nacimiento
                                    }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Fecha de Nacimiento</label
                                    >
                                    <el-date-picker
                                        v-model="form.fecha_nacimiento"
                                        type="date"
                                        placeholder="Seleccione fecha"
                                        format="dd/MM/yyyy"
                                        value-format="yyyy-MM-dd"
                                        :picker-options="{
                                            disabledDate(time) {
                                                return (
                                                    time.getTime() > Date.now()
                                                );
                                            }
                                        }"
                                        style="width: 100%"
                                    ></el-date-picker>
                                    <small
                                        v-if="errors.fecha_nacimiento"
                                        class="form-control-feedback"
                                        v-text="errors.fecha_nacimiento[0]"
                                    ></small>
                                </div>
                            </div>
                            <div v-if="form.state" class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Estado del Contribuyente</label
                                    >
                                    <template v-if="form.state == 'ACTIVO'">
                                        <el-alert
                                            :closable="false"
                                            :title="`${form.state}`"
                                            show-icon
                                            type="success"
                                        ></el-alert>
                                    </template>
                                    <template v-else>
                                        <el-alert
                                            :closable="false"
                                            :title="`${form.state}`"
                                            show-icon
                                            type="error"
                                        ></el-alert>
                                    </template>
                                </div>
                            </div>
                            <div v-if="form.condition" class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Condición del Contribuyente</label
                                    >
                                    <template v-if="form.condition == 'HABIDO'">
                                        <el-alert
                                            :closable="false"
                                            :title="`${form.condition}`"
                                            show-icon
                                            type="success"
                                        ></el-alert>
                                    </template>
                                    <template v-else>
                                        <el-alert
                                            :closable="false"
                                            :title="`${form.condition}`"
                                            show-icon
                                            type="error"
                                        ></el-alert>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Campos de Acceso al Sistema -->
                        <div class="row">
                            <!-- Correo electronico contacto -->
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.email }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Correo electrónico
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-input
                                        v-model="form.email"
                                        dusk="email"
                                        type="email"
                                        placeholder="ejemplo@correo.com"
                                    ></el-input>
                                    <small
                                        v-if="errors.email"
                                        class="form-control-feedback"
                                        v-text="errors.email[0]"
                                    ></small>
                                    <small class="text-muted">
                                        Este correo será usado para acceder al
                                        módulo de taxis
                                    </small>
                                </div>
                            </div>
                            <!-- Contraseña para acceso al sistema -->
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.password }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Contraseña para acceso al
                                        sistema</label
                                    >
                                    <el-input
                                        v-model="form.password"
                                        dusk="password"
                                        type="password"
                                        show-password
                                        :placeholder="
                                            recordId
                                                ? 'Dejar vacío para mantener la contraseña actual'
                                                : 'Ingrese la contraseña'
                                        "
                                    ></el-input>
                                    <small
                                        v-if="errors.password"
                                        class="form-control-feedback"
                                        v-text="errors.password[0]"
                                    ></small>
                                    <small class="text-muted">
                                        Esta contraseña permitirá al propietario
                                        acceder al módulo de taxis
                                    </small>
                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane class name="second">
                        <span slot="label">Dirección</span>
                        <div class="row">
                            <!-- País -->

                            <div class="col-md-3">
                                <div
                                    :class="{ 'has-danger': errors.country_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">País</label>
                                    <el-select
                                        v-model="form.country_id"
                                        dusk="country_id"
                                        filterable
                                    >
                                        <el-option
                                            v-for="option in countries"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.country_id"
                                        class="form-control-feedback"
                                        v-text="errors.country_id[0]"
                                    ></small>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div
                                    :class="{
                                        'has-danger': errors.location_id
                                    }"
                                    class="form-group"
                                >
                                    <label class="control-label">Ubigeo</label>
                                    <el-cascader
                                        v-model="form.location_id"
                                        :clearable="true"
                                        :options="locations"
                                        filterable
                                    ></el-cascader>
                                    <small
                                        v-if="errors.location_id"
                                        class="form-control-feedback"
                                        v-text="errors.location_id[0]"
                                    ></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.address }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Dirección</label
                                    >
                                    <el-input
                                        v-model="form.address"
                                        dusk="address"
                                    ></el-input>
                                    <small
                                        v-if="errors.address"
                                        class="form-control-feedback"
                                        v-text="errors.address[0]"
                                    ></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Teléfono 1 -->
                            <div class="col-md-4">
                                <div
                                    :class="{
                                        'has-danger': errors.telephone_1
                                    }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Teléfono 1</label
                                    >
                                    <el-input
                                        v-model="form.telephone_1"
                                        dusk="telephone_1"
                                        maxlength="20"
                                    ></el-input>
                                    <small
                                        v-if="errors.telephone_1"
                                        class="form-control-feedback"
                                        v-text="errors.telephone_1[0]"
                                    ></small>
                                </div>
                            </div>
                            <!-- Teléfono 2 -->
                            <div class="col-md-4">
                                <div
                                    :class="{
                                        'has-danger': errors.telephone_2
                                    }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Teléfono 2</label
                                    >
                                    <el-input
                                        v-model="form.telephone_2"
                                        dusk="telephone_2"
                                        maxlength="20"
                                    ></el-input>
                                    <small
                                        v-if="errors.telephone_2"
                                        class="form-control-feedback"
                                        v-text="errors.telephone_2[0]"
                                    ></small>
                                </div>
                            </div>
                            <!-- Teléfono 3 -->
                            <div class="col-md-4">
                                <div
                                    :class="{
                                        'has-danger': errors.telephone_3
                                    }"
                                    class="form-group"
                                >
                                    <label class="control-label"
                                        >Teléfono 3</label
                                    >
                                    <el-input
                                        v-model="form.telephone_3"
                                        dusk="telephone_3"
                                        maxlength="20"
                                    ></el-input>
                                    <small
                                        v-if="errors.telephone_3"
                                        class="form-control-feedback"
                                        v-text="errors.telephone_3[0]"
                                    ></small>
                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click="close">Cancelar</el-button>
                <el-button type="primary" @click="submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import { mapActions, mapState } from "vuex/dist/vuex.mjs";
import { serviceNumber } from "../../../../mixins/functions";
export default {
    mixins: [serviceNumber],
    props: ["showDialog", "recordId", "document_type_id"],
    data() {
        return {
            form: {},
            api_service_token: false,
            resource: "propietarios",
            countries: [],
            provinces: [],
            districts: [],
            locations: [],
            identity_document_types: [],
            activeName: "general",
            errors: {},
            titleDialog: "Nuevo Propietario",
            loading_submit: false
        };
    },
    async created() {
        this.loadConfiguration();
        await this.initForm();
        await this.$http
            .get(`/${this.resource}/tables`)
            .then(response => {
                this.countries = response.data.countries;
                this.identity_document_types =
                    response.data.identity_document_types;
                this.locations = response.data.locations;
                this.api_service_token = response.data.api_service_token;
                this.all_departments = response.data.departments;
                this.all_provinces = response.data.provinces;
                this.all_districts = response.data.districts;
            })
            .finally(() => {
                if (this.api_service_token === false) {
                    if (this.config.api_service_token !== undefined) {
                        this.api_service_token = this.config.api_service_token;
                    }
                }
            });
    },
    computed: {
        ...mapState(["config"]),
        maxLength: function() {
            if (this.form.identity_document_type_id === "6") {
                return 11;
            }
            if (this.form.identity_document_type_id === "1") {
                return 8;
            }
        }
    },
    methods: {
        ...mapActions(["loadConfiguration"]),
        initForm() {
            this.errors = {};
            this.form = {
                condition: null,
                state: null,
                identity_document_type_id: "6",
                number: "",
                name: null,
                fecha_nacimiento: null,
                telephone_1: null,
                telephone_2: null,
                telephone_3: null,
                email: null,
                password: null,
                country_id: "PE",
                location_id: [],
                department_id: null,
                province_id: null,
                district_id: null,
                address: "",
                enabled: true,
                establishment_code: "0000"
            };
        },
        async opened() {
            if (this.input_person) {
                if (
                    this.form.number.length === 8 ||
                    this.form.number.length === 11
                ) {
                    if (this.api_service_token != false) {
                        await this.$eventHub.$emit("enableClickSearch");
                    } else {
                        this.searchCustomer();
                    }
                }
            }
        },
        create() {
            this.titleDialog = this.recordId
                ? "Editar Propietario"
                : "Nuevo Propietario";
            if (this.recordId) {
                this.fetchRecord();
            } else {
                //  this.initForm();
            }
        },
        fetchRecord() {
            if (this.recordId) {
                this.$http
                    .get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data;
                        // Limpiar la contraseña para no mostrarla en modo edición
                        this.form.password = "";
                        this.filterProvinces();
                        this.filterDistricts();
                    });
            } else {
                this.initForm();
            }
        },
        validateDigits() {
            const pattern_number = new RegExp("^[0-9]+$", "i");

            if (this.form.identity_document_type_id === "6") {
                if (this.form.number.length !== 11) {
                    return {
                        success: false,
                        message: `El campo número debe tener 11 dígitos.`
                    };
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    };
                }
            }

            if (this.form.identity_document_type_id === "1") {
                if (this.form.number.length !== 8) {
                    return {
                        success: false,
                        message: `El campo número debe tener 8 dígitos.`
                    };
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    };
                }
            }

            if (["4", "7", "0"].includes(this.form.identity_document_type_id)) {
                const pattern = new RegExp("^[A-Z0-9\-]+$", "i");

                if (!pattern.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número no cumple con el formato establecido`
                    };
                }
            }

            return {
                success: true
            };
        },
        validateEmail() {
            if (this.form.email) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(this.form.email)) {
                    return {
                        success: false,
                        message:
                            "El formato del correo electrónico no es válido."
                    };
                }
            }
            return {
                success: true
            };
        },
        validateRequiredFields() {
            // Validar email requerido
            if (!this.form.email || this.form.email.trim() === "") {
                return {
                    success: false,
                    message: "El correo electrónico es requerido."
                };
            }

            // Validar contraseña requerida solo en nuevos registros
            if (
                !this.recordId &&
                (!this.form.password || this.form.password.trim() === "")
            ) {
                return {
                    success: false,
                    message:
                        "La contraseña es requerida para nuevos propietarios."
                };
            }

            // Validar que los campos obligatorios estén presentes
            if (!this.form.name || this.form.name.trim() === "") {
                return {
                    success: false,
                    message: "El nombre es requerido."
                };
            }

            if (!this.form.number || this.form.number.trim() === "") {
                return {
                    success: false,
                    message: "El número de documento es requerido."
                };
            }

            return {
                success: true
            };
        },
        async submit() {
            let val_digits = await this.validateDigits();
            if (!val_digits.success) {
                return this.$message.error(val_digits.message);
            }

            let val_required = await this.validateRequiredFields();
            if (!val_required.success) {
                return this.$message.error(val_required.message);
            }

            let val_email = await this.validateEmail();
            if (!val_email.success) {
                return this.$message.error(val_email.message);
            }

            // Validar location_id
            if (
                !this.form.location_id ||
                !Array.isArray(this.form.location_id) ||
                this.form.location_id.length !== 3
            ) {
                return this.$message.error(
                    "Debe seleccionar un ubigeo válido (departamento, provincia y distrito)."
                );
            }

            // Verificar que los valores de location_id no sean nulos o vacíos
            if (this.form.location_id.some(item => !item)) {
                return this.$message.error(
                    "Los valores de ubigeo no pueden estar vacíos. Seleccione departamento, provincia y distrito."
                );
            }

            this.loading_submit = true;

            // Preparar datos para el envío
            let formData = { ...this.form };

            // Si la contraseña está vacía en modo edición, eliminarla del objeto
            if (this.recordId && !formData.password) {
                delete formData.password;
            }

            await this.$http
                .post(`/${this.resource}`, formData)
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
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data;
                    } else if (
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                    ) {
                        // Mostrar mensaje de error detallado si existe
                        this.$message.error(
                            `Error: ${error.response.data.message}`
                        );
                        console.error("Error detallado:", error.response.data);
                    } else {
                        // Mostrar mensaje genérico si no hay detalles
                        this.$message.error(
                            "Ha ocurrido un error al guardar los datos."
                        );
                        console.error("Error:", error);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        searchCustomer() {
            this.searchServiceNumberByType();
        },
        searchNumber(data) {
            //cambios apiperu
            this.form.name = data.name;
            this.form.trade_name = data.trade_name;

            // Asignar location_id si viene en la respuesta
            if (
                data.location_id &&
                Array.isArray(data.location_id) &&
                data.location_id.length === 3
            ) {
                this.form.location_id = data.location_id;
            }

            this.form.address = data.address;

            // Asignar department_id, province_id, district_id si vienen en la respuesta
            if (data.department_id) {
                this.form.department_id = data.department_id;
            }

            if (data.province_id) {
                this.form.province_id = data.province_id;
            }

            if (data.district_id) {
                this.form.district_id = data.district_id;
            }

            this.form.condition = data.condition;
            this.form.state = data.state;
        },
        close() {
            this.$emit("update:showDialog", false);
            this.$emit("close");
            this.initForm();
        }
    }
};
</script>
