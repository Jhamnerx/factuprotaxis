<template>
    <el-dialog :visible.sync="showDialog" width="800px" :before-close="close">
        <span slot="title">{{ recordId ? "Editar Plan" : "Nuevo Plan" }}</span>
        <form autocomplete="off" @submit.prevent="submitForm">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Nombre<span class="text-danger">*</span></label>
                        <el-input
                            v-model="form.name"
                            maxlength="255"
                            placeholder="Nombre del plan"
                        />
                        <small
                            v-if="errors.name"
                            class="form-control-feedback"
                            v-text="errors.name[0]"
                        ></small>
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <el-input
                            v-model="form.slug"
                            maxlength="255"
                            placeholder="Slug único"
                            disabled
                        />
                        <small
                            v-if="errors.slug"
                            class="form-control-feedback"
                            v-text="errors.slug[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div
                        class="form-group d-flex align-items-center"
                        style="gap: 32px;"
                    >
                        <div>
                            <label class="mr-2">Activo</label>
                            <el-switch v-model="form.is_active" />
                        </div>
                        <div>
                            <label class="mr-2">Plan Socio?</label>
                            <el-switch
                                v-model="form.is_socio"
                                @change="onIsSocioChange"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                                :active-value="true"
                                :inactive-value="false"
                            />
                            <small
                                class="form-text text-muted"
                                v-if="form.is_socio"
                            >
                                Activado (se mostrarán características)
                            </small>
                            <small class="form-text text-muted" v-else>
                                Desactivado (se mostrarán descuentos)
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Descripción</label>
                        <el-input
                            v-model="form.description"
                            type="textarea"
                            placeholder="Descripción detallada del plan"
                        />
                        <small
                            v-if="errors.description"
                            class="form-control-feedback"
                            v-text="errors.description[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Precio</label>
                        <el-input-number
                            v-model="form.price"
                            :min="0"
                            :step="0.01"
                            placeholder="Precio"
                        />
                        <small
                            v-if="errors.price"
                            class="form-control-feedback"
                            v-text="errors.price[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Cuota de Registro</label>
                        <el-input-number
                            v-model="form.signup_fee"
                            :min="0"
                            :step="0.01"
                            placeholder="Cuota de Registro"
                        />
                        <small
                            v-if="errors.signup_fee"
                            class="form-control-feedback"
                            v-text="errors.signup_fee[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Moneda</label>
                        <el-select
                            v-model="form.currency"
                            placeholder="Selecciona una opción"
                        >
                            <el-option label="PEN" value="PEN" />
                            <el-option label="USD" value="USD" />
                        </el-select>
                        <small
                            v-if="errors.currency"
                            class="form-control-feedback"
                            v-text="errors.currency[0]"
                        ></small>
                    </div>
                </div>
                <div
                    v-if="form.invoice_interval !== 'indeterminate'"
                    class="col-md-6 col-12"
                >
                    <div class="form-group">
                        <label>Periodo de Facturación</label>
                        <el-input-number
                            v-model="form.invoice_period"
                            :min="1"
                            @change="onInvoicePeriodChange"
                        />
                        <small
                            v-if="errors.invoice_period"
                            class="form-control-feedback"
                            v-text="errors.invoice_period[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Intervalo de Facturación</label>
                        <el-select
                            v-model="form.invoice_interval"
                            @change="onInvoiceIntervalChange"
                            :disabled="form.is_socio"
                        >
                            <el-option
                                label="Mes"
                                value="month"
                                :disabled="form.is_socio"
                            />
                            <el-option label="Año" value="year" />
                            <el-option
                                label="Indeterminado"
                                value="indeterminate"
                                :disabled="form.is_socio"
                            />
                        </el-select>
                        <small
                            v-if="errors.invoice_interval"
                            class="form-control-feedback"
                            v-text="errors.invoice_interval[0]"
                        ></small>
                        <small
                            v-if="form.is_socio"
                            class="form-text text-muted"
                        >
                            Para planes socio, el intervalo siempre es anual
                        </small>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Orden de Clasificación</label>
                        <el-input-number
                            v-model="form.sort_order"
                            :min="0"
                            disabled
                        />
                        <small
                            v-if="errors.sort_order"
                            class="form-control-feedback"
                            v-text="errors.sort_order[0]"
                        ></small>
                    </div>
                </div>
                <div v-if="showFeatures" class="col-12 mt-2">
                    <label
                        >Características
                        <span class="badge badge-info">Plan Socio</span></label
                    >
                    <div
                        v-for="(feature, idx) in form.features || []"
                        :key="'feature-' + idx"
                        class="border rounded p-3 mb-2"
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
                        <div class="row mt-2">
                            <div class="col-12">
                                <el-input
                                    v-model="feature.description"
                                    placeholder="Descripción"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="col-12 mt-2">
                    <label
                        >Descuentos
                        <span class="badge badge-secondary"
                            >Plan Regular</span
                        ></label
                    >
                    <div
                        v-for="(discount, idx) in form.discounts"
                        :key="'discount-' + idx"
                        class="border rounded p-3 mb-2"
                    >
                        <div class="row align-items-center">
                            <div class="col-md-8 col-12">
                                <el-input v-model="discount.name" readonly />
                            </div>
                            <div class="col-md-4 col-12">
                                <el-input-number
                                    v-model="discount.value"
                                    :min="0"
                                    controls-position="right"
                                    :step="1"
                                />
                            </div>
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
                    @click.prevent="submitForm"
                >
                    {{ form.id ? "Actualizar" : "Guardar" }}
                </el-button>
            </div>
        </form>
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
            errors: {},
            form: {
                id: null,
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
            loading: false,
            loading_submit: false
        };
    },
    computed: {
        isSocioEnabled() {
            return Boolean(this.form.is_socio);
        },
        showFeatures() {
            // Este método determina si la sección de características debe mostrarse
            const isSocio = Boolean(this.form.is_socio);
            console.log("showFeatures computed - is_socio:", isSocio);
            return isSocio;
        }
    },
    watch: {
        async showDialog(val) {
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
        async fetchRecord() {
            this.loading = true;
            await this.$http
                .get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    this.form = response.data.data;
                    console.log("Fetched record:", this.form);
                    console.log(
                        "is_socio value:",
                        this.form.is_socio,
                        "type:",
                        typeof this.form.is_socio
                    );

                    // Asegurar que is_socio sea un valor booleano
                    if (typeof this.form.is_socio === "string") {
                        this.form.is_socio =
                            this.form.is_socio === "1" ||
                            this.form.is_socio.toLowerCase() === "true";
                    } else if (typeof this.form.is_socio === "number") {
                        this.form.is_socio = this.form.is_socio === 1;
                    } else {
                        this.form.is_socio = Boolean(this.form.is_socio);
                    }

                    // Asegurar que features sea siempre un array
                    if (!Array.isArray(this.form.features)) {
                        this.form.features = [];
                    }

                    // Si es plan socio pero no tiene características, inicializar con una característica por defecto
                    if (this.form.is_socio && this.form.features.length === 0) {
                        this.form.features = [
                            {
                                name: "Valido Hasta",
                                value:
                                    new Date().getFullYear() +
                                    parseInt(this.form.invoice_period || 1),
                                sort_order: 1,
                                resettable_period: parseInt(
                                    this.form.invoice_period || 1
                                ),
                                resettable_interval:
                                    this.form.invoice_interval || "year",
                                description: `Este es un plan socio válido por ${this
                                    .form.invoice_period || 1} años.`
                            }
                        ];
                    }

                    // Forzar actualización reactiva
                    this.$set(this.form, "is_socio", this.form.is_socio);
                    this.$set(this.form, "features", this.form.features);

                    console.log(
                        "After processing - is_socio:",
                        this.form.is_socio
                    );
                    console.log(
                        "After processing - features:",
                        this.form.features
                    );
                })
                .catch(error => {
                    console.error("Error fetching record:", error);
                    this.$message.error("Error al cargar el registro");
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        async resetForm() {
            // Obtener el último sort_order
            let sort_order = 1;
            try {
                const res = await this.$http.get(
                    `/${this.resource}/last-sort-order`
                );
                if (res.data && res.data.last_sort_order) {
                    sort_order = res.data.last_sort_order + 1;
                }
            } catch (e) {
                console.error("Error al obtener el último sort_order:", e);
                // Si falla, dejar sort_order en 1
            }

            this.errors = {};
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
                sort_order: sort_order,
                is_socio: false, // Inicializar explícitamente como booleano
                slug: "",
                features: [], // Inicializar como array vacío
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

            // Forzar la reactividad en los campos críticos
            this.$nextTick(() => {
                this.$set(this.form, "is_socio", false);
                this.$set(this.form, "features", []);
            });

            if (this.$refs.form) this.$refs.form.resetFields();
        },
        close() {
            this.$emit("close");
        },
        submitForm() {
            this.loading_submit = true;
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
                        console.log("error: " + err);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        addFeature() {
            this.form.features.push({ name: "", value: null, description: "" });
        },
        removeFeature(idx) {
            this.form.features.splice(idx, 1);
        },
        updatedIsSocio(val) {
            console.log("updatedIsSocio executing with value:", val);

            // Asegurarse de que el valor sea un booleano
            val = Boolean(val);

            // Asignamos directamente el valor con $set para garantizar la reactividad
            this.$set(this.form, "is_socio", val);

            if (val) {
                // Si es un plan socio, cambiamos intervalo a anual
                this.form.invoice_interval = "year";
                this.form.invoice_period = 1;

                // Primero asegurarnos que features sea un array
                if (!Array.isArray(this.form.features)) {
                    this.$set(this.form, "features", []);
                }

                // Inicializar con la característica por defecto
                const newFeatures = [
                    {
                        name: "Valido Hasta",
                        value:
                            new Date().getFullYear() +
                            parseInt(this.form.invoice_period || 1),
                        sort_order: 1,
                        resettable_period: parseInt(
                            this.form.invoice_period || 1
                        ),
                        resettable_interval: "year",
                        description: `Este es un plan socio válido por ${this
                            .form.invoice_period || 1} años.`
                    }
                ];

                // Actualizar reactivamente
                this.$set(this.form, "features", newFeatures);
            } else {
                // Si no es plan socio, volver a mensual
                this.form.invoice_interval = "month";
                this.form.invoice_period = 1;

                // Limpiar características
                this.$set(this.form, "features", []);
            }

            console.log("After update: form.is_socio =", this.form.is_socio);
            console.log("After update: form.features =", this.form.features);
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
                this.updateSocioFeatures();
            }
        },
        updateSocioFeatures() {
            // Actualiza la característica del plan socio basado en el periodo e intervalo actual
            const period = parseInt(this.form.invoice_period || 1);
            const interval = this.form.invoice_interval || "year";
            const currentYear = new Date().getFullYear();

            const newFeatures = this.form.features.map(feature => {
                if (feature.name === "Valido Hasta") {
                    const newValue =
                        interval === "year"
                            ? currentYear + period
                            : currentYear + 1;
                    const intervalText = interval === "year" ? "años" : "meses";

                    return {
                        ...feature,
                        value: newValue,
                        resettable_period: period,
                        resettable_interval: interval,
                        description: `Este es un plan socio válido por ${period} ${intervalText}.`
                    };
                }
                return feature;
            });

            // Actualizar reactivamente
            this.$set(this.form, "features", newFeatures);
        },
        onInvoiceIntervalChange(val) {
            console.log("onInvoiceIntervalChange called with:", val);

            if (val === "indeterminate") {
                this.form.invoice_period = 999;
            } else {
                this.form.invoice_period = 1;
            }

            // Si es plan socio, actualizar las características
            if (this.form.is_socio && this.form.features.length > 0) {
                this.updateSocioFeatures();
            }
        },
        onIsSocioChange(val) {
            console.log("onIsSocioChange called with:", val);

            // Llamamos a updatedIsSocio para manejar la lógica de cambio
            this.updatedIsSocio(val);

            // Forzar una actualización del DOM
            this.$nextTick(() => {
                console.log(
                    "After updatedIsSocio - features:",
                    this.form.features
                );
                console.log(
                    "After updatedIsSocio - is_socio:",
                    this.form.is_socio
                );
                console.log("showFeatures computed value:", this.showFeatures);
            });
        }
    }
};
</script>
