<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible="showDialog"
        append-to-body
        class="pt-0"
        top="7vh"
        width="70%"
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">N° Interno</label>
                            <el-input
                                v-model="form.numero_interno"
                                :maxlength="8"
                                placeholder="Ingrese el N° Interno"
                                uppercase
                            ></el-input>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Placa</label>
                            <el-input
                                v-model="form.placa"
                                :maxlength="8"
                                placeholder="Ingrese la placa"
                                uppercase
                            ></el-input>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Propietario</label>
                            <el-select
                                v-model="form.propietario_id"
                                filterable
                                placeholder="Seleccione un propietario"
                                remote
                                :remote-method="buscarPropietarios"
                                :loading="loadingPropietarios"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="item in propietarios"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Marca</label>
                            <el-select
                                v-model="form.marca_id"
                                filterable
                                placeholder="Seleccione una marca"
                                @change="loadModelos"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="item in marcas"
                                    :key="item.id"
                                    :label="item.nombre"
                                    :value="item.id"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Modelo</label>
                            <el-select
                                v-model="form.modelo_id"
                                filterable
                                placeholder="Seleccione un modelo"
                                :disabled="!form.marca_id"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="item in modelos"
                                    :key="item.id"
                                    :label="item.nombre"
                                    :value="item.id"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Año</label>
                            <el-date-picker
                                v-model="form.year"
                                type="year"
                                format="yyyy"
                                value-format="yyyy"
                                placeholder="Seleccionar año"
                                style="width: 100%"
                            ></el-date-picker>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Color</label>
                            <el-input
                                v-model="form.color"
                                placeholder="Ingrese el color"
                            ></el-input>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Número de Motor</label>
                            <el-input
                                v-model="form.numero_motor"
                                placeholder="Ingrese el número de motor"
                            ></el-input>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"
                                >Fecha de Ingreso</label
                            >
                            <el-date-picker
                                v-model="form.fecha_ingreso"
                                type="date"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd"
                                placeholder="Seleccionar fecha"
                                style="width: 100%"
                            ></el-date-picker>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Estado</label>
                            <el-select
                                v-model="form.estado"
                                placeholder="Seleccione un estado"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="(estado, index) in estados"
                                    :key="index"
                                    :label="estado"
                                    :value="estado"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Estado TUC</label>
                            <el-select
                                v-model="form.estado_tuc"
                                placeholder="Seleccione un estado TUC"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="(estadoTuc, index) in estadosTuc"
                                    :key="index"
                                    :label="estadoTuc"
                                    :value="estadoTuc"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Largo (m)</label>
                            <el-input-number
                                v-model="form.largo"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Ancho (m)</label>
                            <el-input-number
                                v-model="form.ancho"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Alto (m)</label>
                            <el-input-number
                                v-model="form.alto"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Peso (kg)</label>
                            <el-input-number
                                v-model="form.peso"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Carga Útil (kg)</label>
                            <el-input-number
                                v-model="form.carga_util"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">CCN</label>
                            <el-input
                                v-model="form.ccn"
                                placeholder="Ingrese el CCN"
                            ></el-input>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Ejes</label>
                            <el-input-number
                                v-model="form.ejes"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Asientos</label>
                            <el-input-number
                                v-model="form.asientos"
                                :min="0"
                                style="width: 100%"
                            ></el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Categoría</label>
                            <el-input
                                v-model="form.categoria"
                                placeholder="Ingrese la categoría"
                            ></el-input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click="close">Cancelar</el-button>
                <el-button
                    native-type="submit"
                    type="primary"
                    :loading="loading"
                >
                    {{ form.id ? "Actualizar" : "Guardar" }}
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "recordId", "api_service_token"],
    data() {
        return {
            titleDialog: "Vehículo",
            loading: false,
            resource: "unidades",
            errors: {},
            form: {
                id: null,
                numero_interno: null,
                placa: null,
                propietario_id: null,
                marca_id: null,
                modelo_id: null,
                color: null,
                year: null,
                numero_motor: null,
                fecha_ingreso: null,
                estado: "ACTIVO",
                estado_tuc: null,
                largo: null,
                ancho: null,
                alto: null,
                peso: null,
                carga_util: null,
                ccn: null,
                ejes: null,
                asientos: null,
                categoria: null,
                user_id: null
            },
            propietarios: [],
            marcas: [],
            modelos: [],
            loadingPropietarios: false,
            estadosTuc: [
                "TUC",
                "RECIBO",
                "TRAMITE BAJA",
                "PAGO LOGO",
                "NO REGISTRADO",
                "DE BAJA",
                "LIBRE"
            ],
            estados: [
                "ACTIVO",
                "DE BAJA",
                "DE BAJA POR PAGO",
                "SUSPECION POR TRABAJO",
                "RETIRO"
            ]
        };
    },
    async created() {
        // Cargar las tablas de referencia (propietarios, marcas)
        await this.loadTables();
    },
    methods: {
        async loadTables() {
            try {
                const response = await this.$http.get(
                    `/${this.resource}/tables`
                );
                const data = response.data;
                this.propietarios = data.propietarios || [];
                this.marcas = data.marcas || [];
            } catch (error) {
                console.error("Error al cargar tablas:", error);
                this.$message.error("Error al cargar datos de referencia");
            }
        },
        async create() {
            this.titleDialog = this.recordId
                ? "Editar Vehículo"
                : "Nuevo Vehículo";
            this.form = {
                id: null,
                numero_interno: "",
                placa: null,
                propietario_id: null,
                marca_id: null,
                modelo_id: null,
                color: null,
                year: null,
                numero_motor: null,
                fecha_ingreso: new Date().toISOString().slice(0, 10),
                estado: "ACTIVO",
                estado_tuc: null,
                largo: null,
                ancho: null,
                alto: null,
                peso: null,
                carga_util: null,
                ccn: null,
                ejes: null,
                asientos: null,
                categoria: null,
                user_id: null
            };

            // Cargar los datos si estamos editando
            if (this.recordId) {
                await this.initForm();
            }

            // Asegurarse de que las marcas estén cargadas
            if (this.marcas.length === 0) {
                await this.loadMarcas();
            }
        },
        async loadMarcas() {
            try {
                const response = await this.$http.get(`/marcas/records`);
                this.marcas = response.data.data || [];
            } catch (error) {
                console.error("Error al cargar marcas:", error);
            }
        },
        async loadModelos() {
            if (!this.form.marca_id) {
                this.modelos = [];
                this.form.modelo_id = null;
                return;
            }

            try {
                const response = await this.$http.get(
                    `/modelos/por-marca/${this.form.marca_id}`
                );
                this.modelos = response.data || [];
            } catch (error) {
                console.error("Error al cargar modelos:", error);
                this.$message.error("Error al cargar modelos");
            }
        },
        async buscarPropietarios(query) {
            if (query.length < 2) return;

            this.loadingPropietarios = true;
            try {
                const response = await this.$http.get(
                    `/propietarios/search?term=${query}`
                );
                this.propietarios = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.loadingPropietarios = false;
            }
        },
        async initForm() {
            this.loading = true;
            try {
                const { data } = await this.$http.get(
                    `/${this.resource}/record/${this.recordId}`
                );

                this.form = {
                    id: this.recordId,
                    numero_interno: data.numero_interno,
                    placa: data.placa,
                    chasis: data.chasis,
                    propietario_id: data.propietario_id,
                    marca_id: data.marca_id,
                    modelo_id: data.modelo_id,
                    color: data.color,
                    year: data.year ? data.year.toString() : null,
                    numero_motor: data.numero_motor,
                    fecha_ingreso: data.fecha_ingreso
                        ? data.fecha_ingreso.substring(0, 10)
                        : null,
                    estado: data.estado,
                    estado_tuc: data.estado_tuc,
                    largo: data.largo,
                    ancho: data.ancho,
                    alto: data.alto,
                    peso: data.peso,
                    carga_util: data.carga_util,
                    ccn: data.ccn,
                    ejes: data.ejes,
                    asientos: data.asientos,
                    categoria: data.categoria
                };

                // Cargar el propietario en la lista de propietarios si no está
                if (
                    data.propietario &&
                    this.propietarios.findIndex(
                        p => p.id === data.propietario.id
                    ) === -1
                ) {
                    this.propietarios = [
                        ...this.propietarios,
                        data.propietario
                    ];
                }

                // Cargar los modelos de la marca seleccionada
                if (data.marca_id) {
                    await this.loadModelos();
                }
            } catch (error) {
                console.error("Error al cargar el registro:", error);
                this.$message.error("Error al cargar los datos del vehículo");
            } finally {
                this.loading = false;
            }
        },
        async submit() {
            this.loading = true;

            await this.$http
                .post(`/${this.resource}`, this.form)
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
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        close() {
            this.$emit("update:showDialog", false);
            this.$emit("close");
        }
    }
};
</script>

<style scoped>
.dialog-large .el-dialog__body {
    padding: 20px 30px;
}

.form-body {
    max-height: 60vh;
    overflow-y: auto;
    padding-right: 10px;
}

.form-body::-webkit-scrollbar {
    width: 6px;
}

.form-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.form-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.form-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
