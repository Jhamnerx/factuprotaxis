<template>
    <el-dialog :visible.sync="showDialog" width="500px" :before-close="close">
        <span slot="title">{{
            recordId ? "Editar Condición" : "Nueva Condición"
        }}</span>
        <el-form
            :model="form"
            :rules="rules"
            ref="form"
            label-width="120px"
            @submit.native.prevent
        >
            <el-form-item label="Descripción" prop="descripcion">
                <el-input
                    v-model="form.descripcion"
                    maxlength="255"
                    type="textarea"
                />
            </el-form-item>
            <el-form-item label="Color" prop="color">
                <el-color-picker v-model="form.color" />
                <span class="ml-2">{{ form.color }}</span>
            </el-form-item>
        </el-form>
        <span slot="footer">
            <el-button @click="close">Cancelar</el-button>
            <el-button type="primary" @click="submitForm">Guardar</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    name: "CondicionesForm",
    props: {
        showDialog: { type: Boolean, required: true },
        recordId: { type: [Number, null], default: null }
    },
    data() {
        return {
            form: {
                color: "#409EFF",
                descripcion: ""
            },
            rules: {
                descripcion: [
                    {
                        required: true,
                        message: "La descripción es obligatoria",
                        trigger: "blur"
                    }
                ],
                color: [
                    {
                        required: true,
                        message: "El color es obligatorio",
                        trigger: "change"
                    }
                ]
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
        }
    },
    methods: {
        fetchRecord() {
            this.$http.get(`/condiciones/record/${this.recordId}`).then(res => {
                this.form = Object.assign({}, res.data.data);
            });
        },
        resetForm() {
            this.form = {
                color: "#409EFF",
                descripcion: ""
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
                let url = "/condiciones";
                let method = this.recordId ? "put" : "post";
                let data = Object.assign({}, this.form);
                if (this.recordId) data.id = this.recordId;
                this.$http[method](url, data)
                    .then(res => {
                        if (res.data.success) {
                            this.$message.success(res.data.message);
                            this.$eventHub.$emit("reloadData");
                            this.close();
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(err => {
                        if (err.response.status === 422) {
                            this.errors = err.response.data;
                        } else {
                            console.log(err);
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            });
        }
    }
};
</script>

<style scoped>
.el-color-picker {
    vertical-align: middle;
}
</style>
