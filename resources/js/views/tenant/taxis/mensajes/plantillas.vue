<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Plantillas de Mensajes</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Tipo de Mensaje</label>
                        <select
                            v-model="form.tipo"
                            class="form-control"
                            @change="getPlantilla"
                        >
                            <option value="bienvenida"
                                >Mensaje de Bienvenida</option
                            >
                            <option value="bienvenida_conductor"
                                >Mensaje de Bienvenida al Conductor</option
                            >
                            <option value="registro"
                                >Mensaje de Registro</option
                            >
                            <option value="cumpleanos_propietario"
                                >Saludo por Cumpleaños (Propietario)</option
                            >
                            <option value="cumpleanos_conductor"
                                >Saludo por Cumpleaños (Conductor)</option
                            >
                            <option value="cumpleanos_encargado"
                                >Saludo por Cumpleaños (Encargado)</option
                            >
                            <option value="regalo_mes">Regalo de Mes</option>
                            <option value="vencimiento_licencia_conductor"
                                >Vencimiento de Licencia (Conductor)</option
                            >
                            <option value="vencimiento_licencia_encargado"
                                >Vencimiento de Licencia (Encargado)</option
                            >
                            <option value="vencimiento_licencia_propietario"
                                >Vencimiento de Licencia (Propietario)</option
                            >
                            <option value="vencimiento_soat"
                                >Vencimiento de SOAT</option
                            >
                            <option value="vencimiento_revision_tecnica"
                                >Vencimiento de Revisión Técnica</option
                            >
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Asunto</label>
                        <input
                            type="text"
                            v-model="form.asunto"
                            class="form-control"
                            :disabled="loading"
                        />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Descripción</label>
                <input
                    type="text"
                    v-model="form.descripcion"
                    class="form-control"
                    :disabled="loading"
                />
            </div>
            <div class="form-group">
                <label class="control-label">Contenido del Mensaje</label>
                <textarea
                    v-model="form.contenido"
                    class="form-control"
                    rows="10"
                    :disabled="loading"
                ></textarea>
            </div>
            <div class="form-group">
                <button
                    type="button"
                    class="btn btn-info mr-2"
                    @click="previewMessage"
                >
                    <i class="fa fa-eye"></i> Previsualizar mensaje
                </button>
            </div>
            <div v-if="showPreview" class="alert alert-info mt-3">
                <h5>Vista previa del mensaje</h5>
                <div
                    v-html="previewContent"
                    style="white-space: pre-line;"
                ></div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="my-0">Variables disponibles</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <code>[nombre]</code> - Nombre de la persona
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[fecha_vencimiento]</code> - Fecha de
                                    vencimiento
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[placa]</code> - Placa del vehículo
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[marca]</code> - Marca del vehículo
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[modelo]</code> - Modelo del vehículo
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[plan]</code> - Plan contratado
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[fecha_actual]</code> - Fecha actual
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[empresa]</code> - Nombre de la
                                    empresa
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[celular]</code> - Número de celular
                                </div>
                                <div class="col-md-3 mb-2">
                                    <code>[flota]</code> - Flota asignada
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="submit"
                    :disabled="loading"
                >
                    <template v-if="loading">
                        <i class="fa fa-spinner fa-spin"></i> Guardando...
                    </template>
                    <template v-else>
                        <i class="fa fa-save"></i> Guardar
                    </template>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: "mensajes",
            form: {
                id: null,
                tipo: "bienvenida",
                asunto: "",
                contenido: "",
                descripcion: "",
                estado: true
            },
            loading: false,
            errors: {},
            showPreview: false,
            previewContent: ""
        };
    },
    created() {
        this.getPlantilla();
    },
    methods: {
        async getPlantilla() {
            this.loading = true;
            const tipo = this.form.tipo;

            try {
                // Primero intentamos obtener la plantilla por tipo
                const { data } = await this.$http.get(
                    `/${this.resource}/tipo/${tipo}`
                );

                if (data.success) {
                    // Si encontramos la plantilla, la cargamos
                    this.form = {
                        id: data.data.id,
                        tipo: data.data.tipo,
                        asunto: data.data.asunto,
                        contenido: data.data.contenido,
                        descripcion: data.data.descripcion,
                        estado: data.data.estado
                    };
                } else {
                    // Si no existe la plantilla, buscamos en todas las plantillas
                    const response = await this.$http.get(
                        `/${this.resource}/records`
                    );
                    const plantilla = response.data.find(p => p.tipo === tipo);

                    if (plantilla) {
                        this.form = {
                            id: plantilla.id,
                            tipo: plantilla.tipo,
                            asunto: plantilla.asunto,
                            contenido: plantilla.contenido,
                            descripcion: plantilla.descripcion,
                            estado: plantilla.estado
                        };
                    } else {
                        // Si no existe ninguna plantilla, reiniciamos el formulario
                        this.form = {
                            id: null,
                            tipo: tipo,
                            asunto: "",
                            contenido: "",
                            descripcion: "",
                            estado: true
                        };
                    }
                }
            } catch (error) {
                console.error(error);
                this.$message.error(error.response.data.message);

                // En caso de error, al menos mantenemos el tipo seleccionado
                this.form = {
                    id: null,
                    tipo: tipo,
                    asunto: "",
                    contenido: "",
                    descripcion: "",
                    estado: true
                };
            } finally {
                this.loading = false;
            }
        },
        async submit() {
            this.loading = true;
            try {
                const { data } = await this.$http.post(
                    `/${this.resource}`,
                    this.form
                );
                if (data.success) {
                    this.$message.success(data.message);
                    this.form.id = data.id;
                } else {
                    this.$message.error(data.message);
                }
            } catch (error) {
                console.error(error);
                this.$message.error(
                    error.response.data.message ||
                        "Error al guardar la plantilla"
                );
            } finally {
                this.loading = false;
            }
        },

        previewMessage() {
            // Datos de prueba para la previsualización
            const testData = {
                nombre: "Juan Pérez",
                fecha_vencimiento: "31/12/2025",
                placa: "ABC-123",
                marca: "Toyota",
                modelo: "Corolla",
                plan: "Premium",
                fecha_actual: new Date().toLocaleDateString(),
                empresa: "Taxi San Pedro",
                celular: "999-888-777",
                flota: "Flota Central"
            };

            // Reemplazar las variables en el contenido
            let previewText = this.form.contenido;

            Object.keys(testData).forEach(key => {
                const regex = new RegExp(`\\[${key}\\]`, "g");
                previewText = previewText.replace(regex, testData[key]);
            });

            this.previewContent = previewText;
            this.showPreview = true;
        }
    }
};
</script>
