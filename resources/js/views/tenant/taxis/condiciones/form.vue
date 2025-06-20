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
                <div class="color-selection">
                    <el-color-picker v-model="form.color" color-format="hex" />
                    <div class="color-preview-container">
                        <div
                            class="color-preview"
                            :style="{ backgroundColor: form.color }"
                        ></div>
                        <span class="color-value">{{ form.color }}</span>
                    </div>
                </div>
            </el-form-item>
        </el-form>
        <span slot="footer">
            <el-button @click="close">Cancelar</el-button>
            <el-button
                type="primary"
                @click="submitForm"
                :loading="loading_submit"
            >
                {{ form.id ? "Actualizar" : "Guardar" }}
            </el-button>
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
            resource: "condiciones",
            form: {
                id: this.recordId || null,
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
            errors: {},
            loading: false,
            loading_submit: false
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
            this.loading = true;
            this.$http
                .get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    // Asignar datos recibidos al formulario y asegurar que el ID esté presente
                    this.form = response.data.data;
                    // Asegurar que el ID está correctamente asignado
                    this.form.id = this.form.id || this.recordId;

                    // Si el color no está en formato hexadecimal, convertirlo
                    if (this.form.color && !this.form.color.startsWith("#")) {
                        this.onColorChange(this.form.color);
                    }

                    console.log("Datos cargados:", this.form);
                })
                .catch(error => {
                    console.error("Error al cargar el registro:", error);
                    this.$message.error("Error al cargar el registro");
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        resetForm() {
            this.errors = {};
            this.form = {
                id: null, // Asegurar que el ID es null para crear uno nuevo
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

                // Asegurar que el color sea hexadecimal antes de enviar
                if (this.form.color && !this.form.color.startsWith("#")) {
                    this.onColorChange(this.form.color);
                }

                this.loading_submit = true;
                // Siempre usar POST, el controlador decidirá si crear o actualizar
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
                            this.$message.error(
                                "Por favor, corrija los errores del formulario"
                            );
                        } else {
                            console.error(
                                "Error al enviar el formulario:",
                                err
                            );
                            this.$message.error(
                                "Error al procesar su solicitud"
                            );
                        }
                    })
                    .finally(() => {
                        this.loading_submit = false;
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

.color-selection {
    display: flex;
    align-items: center;
    gap: 15px;
}

.color-preview-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.color-preview {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    border: 1px solid #dcdfe6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
}

.color-value {
    font-family: monospace;
    font-size: 14px;
    color: #606266;
}

:deep(.el-form-item__label) {
    font-weight: 500;
    color: #606266;
}

:deep(.el-textarea__inner) {
    font-size: 14px;
    padding: 8px 12px;
}

:deep(.el-dialog__footer) {
    padding: 16px 20px;
    border-top: 1px solid #ebeef5;
}

:deep(.el-dialog__header) {
    padding: 16px 20px;
    border-bottom: 1px solid #ebeef5;
    background-color: #f8f9fa;
}

:deep(.el-dialog__title) {
    font-weight: 600;
    font-size: 18px;
    color: #2c3e50;
}
</style>
