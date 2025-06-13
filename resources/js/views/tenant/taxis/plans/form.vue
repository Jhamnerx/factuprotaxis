<template>
    <el-dialog :visible.sync="showDialog" width="800px" :before-close="close">
        <span slot="title">{{ recordId ? "Editar Plan" : "Nuevo Plan" }}</span>
        <el-form
            :model="form"
            :rules="rules"
            ref="form"
            label-width="160px"
            @submit.native.prevent
        >
            <div class="row">
                <!-- Nombre y Slug -->
                <div class="col-md-6 col-12">
                    <el-form-item label="Nombre" prop="name">
                        <el-input
                            v-model="form.name"
                            maxlength="255"
                            placeholder="Nombre del plan"
                        />
                    </el-form-item>
                    <el-form-item label="Slug" prop="slug">
                        <el-input
                            v-model="form.slug"
                            maxlength="255"
                            placeholder="Slug único"
                            :disabled="true"
                        />
                    </el-form-item>
                </div>
                <!-- Switches Activo y Socio en la misma fila -->
                <div class="col-md-6 col-12 d-flex align-items-center">
                    <el-form-item label-width="0">
                        <div
                            class="d-flex flex-row align-items-center w-100"
                            style="gap: 32px;"
                        >
                            <div class="d-flex flex-row align-items-center">
                                <span class="mr-2">Activo</span>
                                <el-switch v-model="form.is_active" />
                            </div>
                            <div class="d-flex flex-row align-items-center">
                                <span class="mr-2">Plan Socio?</span>
                                <el-switch
                                    v-model="form.is_socio"
                                    @change="onIsSocioChange"
                                />
                            </div>
                        </div>
                    </el-form-item>
                </div>
                <!-- Descripción -->
                <div class="col-12">
                    <el-form-item label="Descripción" prop="description">
                        <el-input
                            v-model="form.description"
                            type="textarea"
                            placeholder="Descripción detallada del plan"
                        />
                    </el-form-item>
                </div>
                <!-- Precio y Cuota de Registro -->
                <div class="col-md-6 col-12">
                    <el-form-item label="Precio" prop="price">
                        <el-input-number
                            v-model="form.price"
                            :min="0"
                            :step="0.01"
                            placeholder="Precio"
                        />
                    </el-form-item>
                </div>
                <div class="col-md-6 col-12">
                    <el-form-item label="Cuota de Registro" prop="signup_fee">
                        <el-input-number
                            v-model="form.signup_fee"
                            :min="0"
                            :step="0.01"
                            placeholder="Cuota de Registro"
                        />
                    </el-form-item>
                </div>
                <!-- Moneda -->
                <div class="col-md-6 col-12">
                    <el-form-item label="Moneda" prop="currency">
                        <el-select
                            v-model="form.currency"
                            placeholder="Selecciona una opción"
                        >
                            <el-option label="PEN" value="PEN" />
                            <el-option label="USD" value="USD" />
                        </el-select>
                    </el-form-item>
                </div>
                <!-- Periodo de Facturación -->
                <div
                    v-if="form.invoice_interval !== 'indeterminate'"
                    class="col-md-6 col-12"
                >
                    <el-form-item
                        label="Periodo de Facturación"
                        prop="invoice_period"
                    >
                        <el-input-number
                            v-model="form.invoice_period"
                            :min="1"
                            @change="onInvoicePeriodChange"
                        />
                    </el-form-item>
                </div>
                <!-- Intervalo de Facturación -->
                <div class="col-md-6 col-12">
                    <el-form-item
                        label="Intervalo de Facturación"
                        prop="invoice_interval"
                    >
                        <el-select
                            v-model="form.invoice_interval"
                            :clearable="false"
                            @change="onInvoiceIntervalChange"
                        >
                            <el-option label="Mes" value="month" />
                            <el-option label="Año" value="year" />
                            <el-option
                                label="Indeterminado"
                                value="indeterminate"
                            />
                        </el-select>
                    </el-form-item>
                </div>
                <!-- Orden de Clasificación -->
                <div class="col-md-6 col-12">
                    <el-form-item
                        label="Orden de Clasificación"
                        prop="sort_order"
                    >
                        <el-input-number v-model="form.sort_order" :min="0" />
                    </el-form-item>
                </div>
                <!-- Sección para Features o Descuentos -->
                <div v-if="form.is_socio" class="col-12">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Características</label
                    >
                    <div
                        v-for="(feature, idx) in form.features"
                        :key="'feature-' + idx"
                        class="border rounded p-3 mb-2 feature-row"
                    >
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <el-input
                                    v-model="feature.name"
                                    placeholder="Nombre"
                                    readonly
                                />
                            </div>
                            <div class="col-md-6 col-12">
                                <el-input-number
                                    v-model="feature.value"
                                    placeholder="Valor"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="col-md-8 col-12">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Descuentos</label
                    >
                    <div
                        v-for="(discount, idx) in form.discounts"
                        :key="'discount-' + idx"
                        class="border rounded p-3 mb-2 discount-row"
                    >
                        <div class="row align-items-center">
                            <div class="col-md-8 col-12">
                                <el-input v-model="discount.name" readonly />
                            </div>
                            <div
                                class="col-md-4 col-12 d-flex justify-content-end"
                            >
                                <el-input-number
                                    v-model="discount.value"
                                    :min="0"
                                    controls-position="right"
                                    :step="1"
                                    class="discount-input-number"
                                    style="width: 100%; min-width: 90px;"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
        <span slot="footer">
            <el-button @click="close">Cancelar</el-button>
            <el-button type="primary" @click="submitForm">Guardar</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    name: "PlanForm",
    props: {
        showDialog: { type: Boolean, required: true },
        recordId: { type: [Number, null], default: null }
    },
    data() {
        return {
            resource: "planes",
            form: {
                name: "",
                description: "",
                is_active: true,
                price: 0.0,
                signup_fee: 0.0,
                currency: "PEN",
                invoice_period: 1,
                invoice_interval: "month",
                active_subscribers_limit: null,
                sort_order: 0,
                is_socio: false,
                slug: "",
                features: [],
                discounts: [
                    {
                        name: "Descuento por pago anual",
                        slug: "descuento-anual",
                        value: 0,
                        months: 12
                    },
                    {
                        name: "Descuento por pago semestral",
                        slug: "descuento-semestral",
                        value: 0,
                        months: 6
                    },
                    {
                        name: "Descuento por pago trimestral",
                        slug: "descuento-trimestral",
                        value: 0,
                        months: 3
                    },
                    {
                        name: "Descuento por inicio de mes",
                        slug: "descuento-inicio-mes",
                        value: 0,
                        months: 1
                    }
                ]
            },
            rules: {
                name: [
                    {
                        required: true,
                        message: "El nombre es obligatorio",
                        trigger: "blur"
                    }
                ],
                price: [
                    {
                        required: true,
                        type: "number",
                        min: 0,
                        message: "Precio inválido",
                        trigger: "blur"
                    }
                ],
                signup_fee: [
                    {
                        required: true,
                        type: "number",
                        min: 0,
                        message: "Cuota inválida",
                        trigger: "blur"
                    }
                ],
                currency: [
                    {
                        required: true,
                        message: "Moneda obligatoria",
                        trigger: "change"
                    }
                ],
                invoice_period: [
                    {
                        required: true,
                        type: "number",
                        min: 1,
                        message: "Periodo inválido",
                        trigger: "blur"
                    }
                ],
                invoice_interval: [
                    {
                        required: true,
                        message: "Intervalo obligatorio",
                        trigger: "change"
                    }
                ],
                sort_order: [
                    {
                        required: true,
                        type: "number",
                        min: 0,
                        message: "Orden inválido",
                        trigger: "blur"
                    }
                ],
                features: [{ type: "array" }],
                discounts: [{ type: "array" }]
            },
            loading: false
        };
    },
    watch: {
        showDialog(val) {
            if (val && this.recordId) {
                this.fetchRecord();
            } else if (val && !this.recordId) {
                this.resetForm();
            }
        },
        "form.name"(val) {
            if (val) {
                this.form.slug = this.slugify(val);
            } else {
                this.form.slug = "";
            }
        }
    },
    methods: {
        fetchRecord() {
            this.$http.get(`/planes/record/${this.recordId}`).then(res => {
                this.form = Object.assign({}, res.data.data);
            });
        },
        resetForm() {
            this.form = {
                name: "",
                description: "",
                is_active: true,
                price: 0.0,
                signup_fee: 0.0,
                currency: "PEN",
                invoice_period: 1,
                invoice_interval: "month",
                active_subscribers_limit: null,
                sort_order: 0,
                is_socio: false,
                slug: "",
                features: [],
                discounts: [
                    {
                        name: "Descuento por pago anual",
                        slug: "descuento-anual",
                        value: 0,
                        months: 12
                    },
                    {
                        name: "Descuento por pago semestral",
                        slug: "descuento-semestral",
                        value: 0,
                        months: 6
                    },
                    {
                        name: "Descuento por pago trimestral",
                        slug: "descuento-trimestral",
                        value: 0,
                        months: 3
                    },
                    {
                        name: "Descuento por inicio de mes",
                        slug: "descuento-inicio-mes",
                        value: 0,
                        months: 1
                    }
                ]
            };
            if (this.$refs.form) this.$refs.form.resetFields();
        },
        close() {
            this.$emit("close");
        },
        submitForm() {
            this.$refs.form.validate(valid => {
                if (!valid) return;
                this.loading = true;
                this.$http
                    .post(`/${this.resource}`, this.form)
                    .then(res => {
                        if (res.data.success) {
                            this.$message.success(res.data.message);
                            this.$eventHub.$emit("reloadData");
                            this.close();
                        } else {
                            this.$message.error(res.data.message);
                        }
                    })
                    .catch(err => {
                        if (err.response && err.response.status === 422) {
                            this.errors = err.response.data;
                        } else {
                            console.log(err);
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            });
        },
        addFeature() {
            this.form.features.push({ name: "", value: null, description: "" });
        },
        removeFeature(idx) {
            this.form.features.splice(idx, 1);
        },
        updatedIsSocio(val) {
            if (val) {
                this.form.invoice_interval = "year";
                this.form.invoice_period = 1;
                this.form.features = [
                    {
                        name: "Valido Hasta",
                        value: new Date().getFullYear() + 1,
                        sort_order: 1,
                        resettable_period: 1,
                        resettable_interval: "year",
                        description: "Este es un plan socio válido por 1 años."
                    }
                ];
            } else {
                this.form.invoice_interval = "month";
                this.form.invoice_period = 1;
                this.form.features = [];
            }
        },
        slugify(text) {
            return text
                .toString()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "") // Elimina acentos
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/(^-|-$)+/g, "");
        },
        onInvoicePeriodChange(val) {
            // Si es plan socio, actualiza la feature 'Valido Hasta' y descripción
            if (this.form.is_socio && this.form.features.length > 0) {
                this.form.features = this.form.features.map(feature => {
                    if (feature.name === "Valido Hasta") {
                        feature.value =
                            new Date().getFullYear() + parseInt(val);
                        feature.resettable_period = parseInt(val);
                        feature.description =
                            "Este es un plan socio válido por " +
                            val +
                            " años.";
                    }
                    return feature;
                });
            }
        },
        onInvoiceIntervalChange(val) {
            if (val === "indeterminate") {
                this.form.invoice_period = 999;
            } else {
                this.form.invoice_period = 1;
            }
        },
        onIsSocioChange(val) {
            this.updatedIsSocio(val);
        }
    }
};
</script>

<style scoped>
.feature-row,
.discount-row {
    display: flex;
    align-items: stretch;
    margin-bottom: 8px;
}
.block {
    display: block;
}
.text-sm {
    font-size: 0.875rem;
}
.font-medium {
    font-weight: 500;
}
.text-gray-700 {
    color: #374151;
}
.border {
    border: 1px solid #e5e7eb;
}
.rounded {
    border-radius: 0.375rem;
}
.p-3 {
    padding: 0.75rem;
}
.mb-2 {
    margin-bottom: 0.5rem;
}
.mt-2 {
    margin-top: 0.5rem;
}
.d-flex {
    display: flex !important;
}
.flex-row {
    flex-direction: row !important;
}
.align-items-center {
    align-items: center !important;
}
.w-100 {
    width: 100% !important;
}
.mr-2 {
    margin-right: 0.5rem !important;
}
.discount-input-number >>> .el-input__inner {
    text-align: right;
    font-weight: 500;
    font-size: 1rem;
    background: #f8fafc;
}
.discount-row {
    background: #f8fafc;
}
.justify-content-end {
    justify-content: flex-end !important;
}
</style>
