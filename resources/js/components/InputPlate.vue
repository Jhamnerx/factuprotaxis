<template>
    <el-input
        :value="value"
        :maxlength="maxLength"
        @input="handleInput($event)"
        show-word-limit
        style="text-transform: uppercase"
    >
        <template v-if="buttonText">
            <el-button
                type="primary"
                slot="append"
                :loading="loading"
                icon="el-icon-search"
                @click.prevent="clickSearch"
                >{{ buttonText }}
            </el-button>
        </template>
    </el-input>
</template>
<script type="text/javascript">
export default {
    props: {
        value: {
            required: false,
            type: String,
            default: ""
        }
    },
    data() {
        return {
            loading: false,
            resource: "service",
            maxLength: 10,
            buttonText: "BUSCAR"
        };
    },
    created() {},
    mounted() {
        this.$eventHub.$on("enableClickSearch", () => {
            this.clickSearch();
        });
    },

    methods: {
        handleInput(value) {
            // Convertir siempre a mayúsculas y manejar valores nulos o undefined
            const upperValue = value ? value.toUpperCase() : "";
            this.$emit("input", upperValue);
        },
        validatePlateFormat(plate) {
            const pattern = /^([A-Z]{3}-\d{3}|[A-Z]\d[A-Z]-\d{3}|\d{6})$/i;
            return pattern.test(plate.trim());
        },
        formatPlateForRequest(plate) {
            // Eliminar el guion si existe
            return plate.replace(/-/g, "");
        },
        clickSearch() {
            // Asegurar que value siempre sea un string
            const plateValue = this.value || "";

            if (!plateValue.trim()) {
                this.$message.warning("Por favor ingrese una placa");
                return;
            }

            // Verificar formato de placa
            if (!this.validatePlateFormat(plateValue)) {
                this.$message.error(
                    "Formato de placa inválido. Use ABC123, ABC-123 o AB-1234"
                );
                return;
            }

            // Formatear placa para la petición (quitar guiones)
            const formattedPlate = this.formatPlateForRequest(plateValue);

            this.loading = true;
            this.$http
                .get(`/${this.resource}/placa/${formattedPlate}`)
                .then(response => {
                    let res = response.data;
                    if (res.success) {
                        this.$emit("search", res.data);
                    } else {
                        this.$message.error(res.message);
                    }
                })
                .catch(error => {
                    console.log(error.response);
                })
                .then(() => {
                    this.loading = false;
                });
        }
    }
};
</script>
