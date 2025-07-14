<template>
    <el-input
        :value="value"
        :maxlength="maxLength"
        @input="handleInput($event)"
        show-word-limit
        :placeholder="placeholder"
    >
        <el-select
            v-model="searchType"
            slot="prepend"
            style="width: 100px"
            @change="onTypeChange"
        >
            <el-option label="Licencia" value="licencia"></el-option>
            <el-option label="DNI" value="dni"></el-option>
        </el-select>
        <el-button
            type="primary"
            slot="append"
            :loading="loading"
            icon="el-icon-search"
            @click.prevent="clickSearch"
        >
            {{ buttonText }}
        </el-button>
    </el-input>
</template>

<script type="text/javascript">
export default {
    props: {
        value: {
            required: true,
            type: String,
            default: ""
        }
    },
    data() {
        return {
            loading: false,
            resource: "service",
            searchType: "licencia", // Por defecto licencia
            maxLength: 20,
            placeholder: "Número de licencia",
            buttonText: "LICENCIA"
        };
    },
    mounted() {
        this.$eventHub.$on("enableClickSearch", () => {
            this.clickSearch();
        });
    },
    methods: {
        onTypeChange() {
            // Limpiar el valor cuando cambie el tipo
            this.$emit("input", "");

            if (this.searchType === "dni") {
                this.maxLength = 8;
                this.placeholder = "Número de DNI (8 dígitos)";
                this.buttonText = "DNI";
            } else {
                this.maxLength = 20;
                this.placeholder = "Número de licencia";
                this.buttonText = "LICENCIA";
            }
        },
        handleInput(value) {
            if (this.searchType === "dni") {
                // Solo permitir números y máximo 8 caracteres para DNI
                const numericValue = value
                    .replace(/[^0-9]/g, "")
                    .substring(0, 8);
                this.$emit("input", numericValue);
            } else {
                // Para licencia, permitir alfanuméricos
                const alphanumericValue = value
                    .replace(/[^a-zA-Z0-9]/g, "")
                    .substring(0, 20);
                this.$emit("input", alphanumericValue.toUpperCase());
            }
        },
        clickSearch() {
            if (!this.value || this.value.trim() === "") {
                const fieldName =
                    this.searchType === "dni" ? "DNI" : "número de licencia";
                this.$message.warning(`Por favor ingrese un ${fieldName}`);
                return;
            }

            if (this.searchType === "dni" && this.value.length !== 8) {
                this.$message.warning(
                    "El DNI debe tener exactamente 8 dígitos"
                );
                return;
            }

            if (this.searchType === "licencia" && this.value.length < 3) {
                this.$message.warning(
                    "El número de licencia debe tener al menos 3 caracteres"
                );
                return;
            }

            this.loading = true;
            this.$http
                .get(`/${this.resource}/${this.searchType}/${this.value}`)
                .then(response => {
                    let res = response.data;
                    if (res.success) {
                        this.$emit("search", res.data);
                        this.$message.success(
                            "Datos encontrados correctamente"
                        );
                    } else {
                        this.$message.error(
                            res.message || "No se encontraron datos"
                        );
                    }
                })
                .catch(error => {
                    console.log(error.response);
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                    ) {
                        this.$message.error(error.response.data.mes