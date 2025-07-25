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
                            <small
                                v-if="errors.numero_interno"
                                class="text-danger"
                                >{{ errors.numero_interno[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">N° Flota</label>
                            <el-input
                                v-model="form.flota"
                                :maxlength="8"
                                placeholder="Ingrese el N° Flota"
                                uppercase
                            ></el-input>
                            <small v-if="errors.flota" class="text-danger">{{
                                errors.flota[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Placa</label>
                            <div v-if="api_service_token != false">
                                <x-input-plate
                                    v-model="form.placa"
                                    @search="searchPlaca"
                                ></x-input-plate>
                            </div>
                            <div v-else>
                                <el-input
                                    v-model="form.placa"
                                    :maxlength="8"
                                    placeholder="Ingrese la placa"
                                    uppercase
                                ></el-input>
                            </div>

                            <small v-if="errors.placa" class="text-danger">{{
                                errors.placa[0]
                            }}</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label
                                class="control-label font-weight-bold text-info"
                            >
                                Propietario
                                <a
                                    href="#"
                                    @click.prevent="
                                        mostrarFormularioPropietario
                                    "
                                    >[+ Nuevo]</a
                                >
                            </label>
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
                            <small
                                v-if="errors.propietario_id"
                                class="text-danger"
                                >{{ errors.propietario_id[0] }}</small
                            >
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
                            <small v-if="errors.marca_id" class="text-danger">{{
                                errors.marca_id[0]
                            }}</small>
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
                            <small
                                v-if="errors.modelo_id"
                                class="text-danger"
                                >{{ errors.modelo_id[0] }}</small
                            >
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
                            <small v-if="errors.year" class="text-danger">{{
                                errors.year[0]
                            }}</small>
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
                            <small v-if="errors.color" class="text-danger">{{
                                errors.color[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Número de Motor</label>
                            <el-input
                                v-model="form.numero_motor"
                                placeholder="Ingrese el número de motor"
                            ></el-input>
                            <small
                                v-if="errors.numero_motor"
                                class="text-danger"
                                >{{ errors.numero_motor[0] }}</small
                            >
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
                            <small
                                v-if="errors.fecha_ingreso"
                                class="text-danger"
                                >{{ errors.fecha_ingreso[0] }}</small
                            >
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
                            <small v-if="errors.estado" class="text-danger">{{
                                errors.estado[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Estado TUC</label>
                            <el-select
                                v-model="form.estado_tuc_id"
                                placeholder="Seleccione un estado TUC"
                                style="width: 100%"
                            >
                                <el-option
                                    v-for="(estadoTuc, index) in condiciones"
                                    :key="index"
                                    :label="estadoTuc.descripcion"
                                    :value="estadoTuc.id"
                                ></el-option>
                            </el-select>
                            <small
                                v-if="errors.estado_tuc_id"
                                class="text-danger"
                                >{{ errors.estado_tuc_id[0] }}</small
                            >
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
                            <small v-if="errors.largo" class="text-danger">{{
                                errors.largo[0]
                            }}</small>
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
                            <small v-if="errors.ancho" class="text-danger">{{
                                errors.ancho[0]
                            }}</small>
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
                            <small v-if="errors.alto" class="text-danger">{{
                                errors.alto[0]
                            }}</small>
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
                            <small v-if="errors.peso" class="text-danger">{{
                                errors.peso[0]
                            }}</small>
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
                            <small
                                v-if="errors.carga_util"
                                class="text-danger"
                                >{{ errors.carga_util[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">CCN</label>
                            <el-input
                                v-model="form.ccn"
                                placeholder="Ingrese el CCN"
                            ></el-input>
                            <small v-if="errors.ccn" class="text-danger">{{
                                errors.ccn[0]
                            }}</small>
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
                            <small v-if="errors.ejes" class="text-danger">{{
                                errors.ejes[0]
                            }}</small>
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
                            <small v-if="errors.asientos" class="text-danger">{{
                                errors.asientos[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Categoría</label>
                            <el-input
                                v-model="form.categoria"
                                placeholder="Ingrese la categoría"
                            ></el-input>
                            <small
                                v-if="errors.categoria"
                                class="text-danger"
                                >{{ errors.categoria[0] }}</small
                            >
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
        <!-- Modal para crear nuevo propietario -->
        <PropietariosForm
            v-if="showDialogNewPropietario"
            :api_service_token="api_service_token"
            :showDialog.sync="showDialogNewPropietario"
            :recordId="null"
            @close="closeDialogNewPropietario"
        />
    </el-dialog>
</template>

<script>
import PropietariosForm from "../propietarios/form.vue";

import XInputPlate from "../../../../components/InputPlate.vue";

export default {
    components: {
        PropietariosForm,
        XInputPlate
    },
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
                flota: null,
                placa: null,
                propietario_id: null,
                marca_id: null,
                modelo_id: null,
                color: null,
                year: null,
                numero_motor: null,
                fecha_ingreso: null,
                estado: "ACTIVO",
                estado_tuc_id: null,
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
            propietariosAnteriores: [],
            marcas: [],
            modelos: [],
            condiciones: [],
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
            showDialogNewPropietario: false,
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
                this.condiciones = data.condiciones || [];
            } catch (error) {
                console.error("Error al cargar tablas:", error);
                this.$message.error("Error al cargar datos de referencia");
            }
        },
        async create() {
            this.titleDialog = this.recordId
                ? "Editar Vehículo"
                : "Nuevo Vehículo";
            this.errors = {};
            this.form = {
                id: null,
                numero_interno: "",
                flota: null,
                placa: null,
                propietario_id: null,
                marca_id: null,
                modelo_id: null,
                color: null,
                year: null,
                numero_motor: null,
                fecha_ingreso: new Date().toISOString().slice(0, 10),
                estado: "ACTIVO",
                estado_tuc_id: null,
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

            this.$http
                .get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    // Obtener datos del vehículo de la respuesta
                    const data = response.data.data || response.data;
                    console.log("Datos del vehículo:", data);

                    // Asignar directamente las propiedades del objeto recibido al formulario
                    this.form = {
                        id: this.recordId,
                        // Asignar propiedades con valores predeterminados si no existen
                        numero_interno: data.numero_interno || "",
                        flota: data.flota || "",
                        placa: data.placa || "",
                        chasis: data.chasis || "",
                        propietario_id: data.propietario_id || null,
                        marca_id: data.marca_id || null,
                        modelo_id: data.modelo_id || null,
                        color: data.color || "",
                        year: data.year ? data.year.toString() : null,
                        numero_motor: data.numero_motor || "",
                        fecha_ingreso: data.fecha_ingreso
                            ? data.fecha_ingreso.substring(0, 10)
                            : null,
                        estado: data.estado || "ACTIVO",
                        estado_tuc_id: data.estado_tuc_id || null,
                        largo: data.largo || null,
                        ancho: data.ancho || null,
                        alto: data.alto || null,
                        peso: data.peso || null,
                        carga_util: data.carga_util || null,
                        ccn: data.ccn || "",
                        ejes: data.ejes || null,
                        asientos: data.asientos || null,
                        categoria: data.categoria || ""
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
                        this.loadModelos();
                    }
                })
                .catch(error => {
                    console.error("Error al cargar el registro:", error);
                    this.$message.error(
                        "Error al cargar los datos del vehículo"
                    );
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        /**
         * Procesa los datos de la placa buscada y los asigna al formulario
         * @param {Object} data - Datos de la placa
         */
        async searchPlaca(data) {
            if (!data) return;

            console.log("Datos recibidos de la búsqueda de placa:", data);

            // Asignar datos básicos al formulario
            this.form.serie = data.serie || "";
            this.form.color = data.color || "";
            this.form.motor = data.motor || "";
            this.form.chasis = data.vin || data.serie || "";
            this.form.numero_motor = data.motor || "";

            // Buscar la marca por nombre
            if (data.marca) {
                try {
                    const marcaResponse = await this.$http.get(
                        `marcas/buscar?nombre=${encodeURIComponent(data.marca)}`
                    );

                    if (marcaResponse.data) {
                        // Encontramos la marca, asignamos su ID
                        const marca = marcaResponse.data.data[0];
                        console.log(
                            `Marca encontrada: ${marca.nombre} (ID: ${
                                marca.id
                            })`
                        );

                        this.form.marca_id = marca.id;

                        // Cargar los modelos de esta marca
                        await this.loadModelos();

                        // Buscar el modelo por nombre si existe
                        if (data.modelo && this.modelos.length > 0) {
                            // Primero intentamos encontrar una coincidencia exacta (ignorando mayúsculas/minúsculas)
                            const modeloEncontrado = this.modelos.find(
                                m =>
                                    m.nombre.toLowerCase() ===
                                    data.modelo.toLowerCase()
                            );

                            if (modeloEncontrado) {
                                this.form.modelo_id = modeloEncontrado.id;
                            } else {
                                // Si no hay coincidencia exacta, buscamos en la API
                                try {
                                    const modeloResponse = await this.$http.get(
                                        `modelos/buscar?nombre=${encodeURIComponent(
                                            data.modelo
                                        )}&marca_id=${this.form.marca_id}`
                                    );

                                    if (
                                        modeloResponse.data &&
                                        modeloResponse.data.length > 0
                                    ) {
                                        const modelo = modeloResponse.data[0];
                                        this.form.modelo_id = modelo.id;
                                    } else {
                                        console.log(
                                            `No se encontró el modelo: ${
                                                data.modelo
                                            }`
                                        );
                                    }
                                } catch (error) {
                                    console.error(
                                        "Error al buscar el modelo:",
                                        error
                                    );
                                }
                            }
                        }
                    } else {
                        console.log(`No se encontró la marca: ${data.marca}`);
                    }
                } catch (error) {
                    console.error("Error al buscar la marca:", error);
                }
            }

            this.$message.success("Datos del vehículo cargados correctamente");
        },
        async submit() {
            this.loading = true;
            this.errors = {};

            await this.$http
                .post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);

                        this.$eventHub.$emit("reloadData");

                        // Si es una nueva unidad (no está editando), preguntar si desea crear contrato
                        if (!this.form.id && response.data.id) {
                            this.preguntarCrearContrato(response.data.id);
                        } else {
                            this.close();
                        }
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

        /**
         * Pregunta al usuario si desea crear un contrato para la unidad recién creada
         */
        preguntarCrearContrato(vehiculoId) {
            this.$confirm(
                "¿Desea crear un contrato para esta unidad?",
                "Crear Contrato",
                {
                    confirmButtonText: "Sí, crear contrato",
                    cancelButtonText: "No, ahora no",
                    type: "question",
                    center: true
                }
            )
                .then(() => {
                    // Abrir página de contratos con el vehículo preseleccionado
                    this.abrirCrearContrato(vehiculoId);
                    this.close();
                })
                .catch(() => {
                    // El usuario canceló, solo cerrar el formulario
                    this.close();
                });
        },

        /**
         * Abre la página de contratos para crear un nuevo contrato con el vehículo preseleccionado
         */
        abrirCrearContrato(vehiculoId) {
            // Usar router para navegar a la página de contratos con el vehículo preseleccionado
            const url = `/contratos?vehiculo_id=${vehiculoId}`;
            window.open(url, "_blank");
        },
        close() {
            this.$emit("update:showDialog", false);
            this.$emit("close");
        },

        /**
         * Muestra el formulario de propietarios
         */
        mostrarFormularioPropietario() {
            // Guardar la lista actual de propietarios para comparar después
            this.propietariosAnteriores = [...this.propietarios];
            console.log(
                "Propietarios antes de abrir el modal:",
                this.propietariosAnteriores
            );

            // Mostrar el diálogo
            this.showDialogNewPropietario = true;
        },
        /**
         * Maneja el cierre del formulario de propietarios y actualiza la lista
         */ closeDialogNewPropietario() {
            console.log("Cerrando diálogo de nuevo propietario");
            this.showDialogNewPropietario = false;

            // Guardar los IDs de propietarios anteriores para comparación
            const idsAnteriores = this.propietariosAnteriores.map(p => p.id);

            // Recargar todas las tablas (incluidos los propietarios)
            this.loadTables()
                .then(() => {
                    console.log(
                        "Tablas recargadas, propietarios:",
                        this.propietarios
                    );

                    // Buscar si hay algún propietario nuevo (que no existía en la lista anterior)
                    if (
                        this.propietariosAnteriores.length <
                        this.propietarios.length
                    ) {
                        // Buscar el propietario con ID que no existía en la lista anterior
                        const nuevoPropietario = this.propietarios.find(
                            p => !idsAnteriores.includes(p.id)
                        );

                        // Si encontramos el nuevo propietario, seleccionarlo
                        if (nuevoPropietario) {
                            this.form.propietario_id = nuevoPropietario.id;
                            this.$message.success(
                                `Propietario "${
                                    nuevoPropietario.name
                                }" creado y seleccionado`
                            );
                        }
                    }
                })
                .catch(error => {
                    console.error("Error al recargar tablas:", error);
                    this.$message.error("Error al actualizar los datos");
                });
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

.text-danger {
    color: #f56c6c;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}
</style>
