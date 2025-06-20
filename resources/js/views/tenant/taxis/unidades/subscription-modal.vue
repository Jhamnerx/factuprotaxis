<template>
    <el-dialog
        :visible.sync="showModal"
        width="500px"
        :before-close="closeModal"
        :title="type === 'create' ? 'Registrar suscripción' : 'Cambiar plan'"
    >
        <form autocomplete="off" @submit.prevent="saveSubscription">
            <div class="form-group">
                <label>Unidad</label>
                <el-input v-model="placa" disabled />
            </div>
            <div class="form-group">
                <label>Plan</label>
                <el-select
                    v-model="form.plan_id"
                    placeholder="Seleccione un plan"
                    filterable
                >
                    <el-option
                        v-for="plan in planes"
                        :key="plan.id"
                        :label="
                            plan.name + ' (' + plan.label_description + ') '
                        "
                        :value="plan.id"
                    />
                </el-select>
                <small
                    v-if="errors.plan_id"
                    class="form-control-feedback"
                    v-text="errors.plan_id[0]"
                ></small>
            </div>
            <div class="form-group" v-if="type === 'create'">
                <label>Fecha de inicio</label>
                <el-date-picker
                    v-model="form.fecha_inicio"
                    type="date"
                    format="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    :picker-options="datePickerOptions"
                    placeholder="Seleccione fecha"
                />
                <small
                    v-if="errors.fecha_inicio"
                    class="form-control-feedback"
                    v-text="errors.fecha_inicio[0]"
                ></small>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="closeModal">Cancelar</el-button>
                <el-button
                    type="primary"
                    :loading="loading_submit"
                    native-type="submit"
                    >{{
                        type === "create" ? "Registrar" : "Cambiar plan"
                    }}</el-button
                >
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    name: "SubscriptionModal",
    props: {
        showModal: { type: Boolean, required: true },
        vehiculo: { type: Object, default: null },
        type: { type: String, default: "create" }
    },
    data() {
        return {
            form: {
                vehiculo_id: null,
                plan_id: null,
                fecha_inicio: "",
                type: "create"
            },
            placa: "",
            planes: [],
            errors: {},
            loading_submit: false,
            datePickerOptions: {
                disabledDate(time) {
                    const now = new Date();
                    const min = new Date(
                        now.getFullYear() - 5,
                        now.getMonth(),
                        now.getDate()
                    );
                    return (
                        time.getTime() < min.getTime() ||
                        time.getTime() > now.getTime()
                    );
                }
            }
        };
    },
    watch: {
        showModal(val) {
            if (val && this.vehiculo) {
                this.openModal(this.vehiculo, this.type);
            }
        }
    },
    methods: {
        async openModal(vehiculo, type) {
            this.errors = {};
            this.form = {
                vehiculo_id: vehiculo.id,
                plan_id: null,
                fecha_inicio:
                    type === "create" ? this.formatDate(new Date()) : "",
                type: type
            };
            this.placa = vehiculo.placa;
            this.type = type;
            await this.fetchPlanes();
        },
        closeModal() {
            this.$emit("update:showModal", false);
            this.errors = {};
            this.form = {
                vehiculo_id: null,
                plan_id: null,
                fecha_inicio: "",
                type: "create"
            };
            this.placa = "";
        },
        async fetchPlanes() {
            const res = await this.$http.get("/unidades/planes/tables");
            this.planes = (res.data.planes || []).map(plan => {
                plan.label_description =
                    (plan.price <= 0
                        ? "Gratis"
                        : plan.currency + " " + plan.price) +
                    " " +
                    this.intervalLabel(plan.invoice_interval);
                return plan;
            });
        },
        intervalLabel(interval) {
            switch (interval) {
                case "day":
                    return "diario";
                case "week":
                    return "semanal";
                case "month":
                    return "mensual";
                case "year":
                    return "anual";
                default:
                    return interval;
            }
        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().slice(0, 10);
        },
        async saveSubscription() {
            this.errors = {};
            if (!this.form.plan_id) {
                this.errors.plan_id = ["Seleccione un plan"];
                return;
            }
            if (!this.placa) {
                this.errors.placa = ["La placa es obligatoria"];
                return;
            }
            if (this.form.type === "create" && !this.form.fecha_inicio) {
                this.errors.fecha_inicio = [
                    "La fecha de inicio es obligatoria"
                ];
                return;
            }
            this.loading_submit = true;
            try {
                const res = await this.$http.post(
                    "/unidades/subscription/create",
                    this.form
                );
                if (res.data.success) {
                    this.$message.success(
                        res.data.message || "Suscripción guardada"
                    );
                    this.$emit("saved");
                    this.closeModal();
                } else {
                    this.$message.error(res.data.message || "Error al guardar");
                    this.errors = res.data.errors || {};
                }
            } catch (e) {
                if (e.response && e.response.data) {
                    this.errors = e.response.data;
                } else {
                    this.$message.error("Error inesperado");
                }
            } finally {
                this.loading_submit = false;
            }
        }
    }
};
</script>
