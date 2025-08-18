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
                <!-- Primera fila: N° Interno, Partida Registral, Placa -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">
                                N° Interno
                                <small
                                    v-if="!recordId && form.numero_interno"
                                    class="text-muted"
                                    >(Autogenerado)</small
                                >
                            </label>
                            <div class="input-group">
                                <el-input
                                    v-model="form.numero_interno"
                                    :maxlength="8"
                                    :readonly="
                                        !recordId &&
                                            form.numero_interno &&
                                            !editingNumeroInterno
                                    "
                                    placeholder="N° Interno"
                                    uppercase
                                    size="small"
                                ></el-input>
                                <div
                                    class="input-group-append"
                                    v-if="!recordId"
                                >
                                    <el-button
                                        type="primary"
                                        size="mini"
                                        @click="regenerarNumeroInterno"
                                        title="Regenerar número interno"
                                        icon="el-icon-refresh"
                                        circle
                                    ></el-button>
                                </div>
                            </div>
                            <small
                                v-if="errors.numero_interno"
                                class="text-danger"
                                >{{ errors.numero_interno[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"
                                >Partida Registral</label
                            >
                            <el-input
                                v-model="form.partida_registral"
                                placeholder="Partida registral"
                                uppercase
                                size="small"
                            ></el-input>
                            <small
                                v-if="errors.partida_registral"
                                class="text-danger"
                                >{{ errors.partida_registral[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-5">
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
                                    :maxlength="7"
                                    placeholder="Ingrese la placa"
                                    uppercase
                                    size="small"
                                ></el-input>
                            </div>
                            <small v-if="errors.placa" class="text-danger">{{
                                errors.placa[0]
                            }}</small>
                        </div>
                    </div>
                </div>

                <!-- Segunda fila: Propietario, Categoría -->
                <div class="row">
                    <div class="col-md-7">
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
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Categoría</label>
                            <el-input
                                v-model="form.categoria"
                                placeholder="Ingrese la categoría"
                                uppercase
                                size="small"
                            ></el-input>
                            <small
                                v-if="errors.categoria"
                                class="text-danger"
                                >{{ errors.categoria[0] }}</small
                            >
                        </div>
                    </div>
                </div>

                <!-- Tercera fila: Marca, Modelo, Color -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label
                                class="control-label font-weight-bold text-info"
                            >
                                Marca
                                <a
                                    href="#"
                                    @click.prevent="mostrarFormularioMarca"
                                    >[+ Nuevo]</a
                                >
                            </label>
                            <el-select
                                v-model="form.marca_id"
                                filterable
                                placeholder="Seleccione marca"
                                @change="loadModelos"
                                size="small"
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
                            <label
                                class="control-label font-weight-bold text-info"
                            >
                                Modelo
                                <a
                                    href="#"
                                    @click.prevent="mostrarFormularioModelo"
                                    >[+ Nuevo]</a
                                >
                            </label>
                            <el-select
                                v-model="form.modelo_id"
                                filterable
                                placeholder="Seleccione modelo"
                                :disabled="!form.marca_id"
                                size="small"
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
                            <label class="control-label">Color</label>
                            <el-input
                                v-model="form.color"
                                placeholder="Ingrese el color"
                                size="small"
                            ></el-input>
                            <small v-if="errors.color" class="text-danger">{{
                                errors.color[0]
                            }}</small>
                        </div>
                    </div>
                </div>

                <!-- Cuarta fila: Motor N°, Ejes, N° Flota -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Motor N°</label>
                            <el-input
                                v-model="form.numero_motor"
                                placeholder="Número de motor"
                                uppercase
                                size="small"
                            ></el-input>
                            <small
                                v-if="errors.numero_motor"
                                class="text-danger"
                                >{{ errors.numero_motor[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Ejes</label>
                            <el-input-number
                                v-model="form.ejes"
                                :min="0"
                                :max="10"
                                size="small"
                                style="width: 100%"
                            ></el-input-number>
                            <small v-if="errors.ejes" class="text-danger">{{
                                errors.ejes[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">N° Flota</label>
                            <el-input
                                v-model="form.flota"
                                placeholder="N° Flota"
                                uppercase
                                size="small"
                            ></el-input>
                            <small v-if="errors.flota" class="text-danger">{{
                                errors.flota[0]
                            }}</small>
                        </div>
                    </div>
                </div>

                <!-- Quinta fila: Año, Asientos -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Año</label>
                            <el-date-picker
                                v-model="form.year"
                                type="year"
                                format="yyyy"
                                value-format="yyyy"
                                placeholder="Año"
                                size="small"
                                style="width: 100%"
                            ></el-date-picker>
                            <small v-if="errors.year" class="text-danger">{{
                                errors.year[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Asientos</label>
                            <el-input-number
                                v-model="form.asientos"
                                :min="0"
                                :max="100"
                                size="small"
                                style="width: 100%"
                            ></el-input-number>
                            <small v-if="errors.asientos" class="text-danger">{{
                                errors.asientos[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">CCN</label>
                            <el-input
                                v-model="form.ccn"
                                placeholder="CCN"
                                uppercase
                                size="small"
                            ></el-input>
                            <small v-if="errors.ccn" class="text-danger">{{
                                errors.ccn[0]
                            }}</small>
                        </div>
                    </div>
                </div>

                <!-- Sexta fila: Largo, Ancho, Alto -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Largo (m)</label>
                            <el-input-number
                                v-model="form.largo"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                :max="50"
                                size="small"
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
                                :max="10"
                                size="small"
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
                                :max="10"
                                size="small"
                                style="width: 100%"
                            ></el-input-number>
                            <small v-if="errors.alto" class="text-danger">{{
                                errors.alto[0]
                            }}</small>
                        </div>
                    </div>
                </div>

                <!-- Séptima fila: Peso Neto, Carga Útil -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Peso Neto (kg)</label>
                            <el-input-number
                                v-model="form.peso"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                :max="50000"
                                size="small"
                                style="width: 100%"
                            ></el-input-number>
                            <small v-if="errors.peso" class="text-danger">{{
                                errors.peso[0]
                            }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Carga Útil (kg)</label>
                            <el-input-number
                                v-model="form.carga_util"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                :max="50000"
                                size="small"
                                style="width: 100%"
                            ></el-input-number>
                            <small
                                v-if="errors.carga_util"
                                class="text-danger"
                                >{{ errors.carga_util[0] }}</small
                            >
                        </div>
                    </div>
                </div>

                <!-- Octava fila: Estado, Estado TUC, Fecha Ingreso -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Estado</label>
                            <el-select
                                v-model="form.estado"
                                placeholder="Seleccione un estado"
                                size="small"
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Estado TUC</label>
                            <el-select
                                v-model="form.estado_tuc_id"
                                placeholder="Seleccione un estado TUC"
                                size="small"
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
                                size="small"
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

        <!-- Modal para crear nueva marca -->
        <MarcasForm
            v-if="showDialogNewMarca"
            :showDialog.sync="showDialogNewMarca"
            :recordId="null"
            @close="closeDialogNewMarca"
        />

        <!-- Modal para crear nuevo modelo -->
        <ModelosForm
            v-if="showDialogNewModelo"
            :showDialog.sync="showDialogNewModelo"
            :recordId="null"
            :marca_id="form.marca_id"
            @close="closeDialogNewModelo"
        />
    </el-dialog>
</template>

<script>
import PropietariosForm from "../propietarios/form.vue";
import MarcasForm from "../marcas/form.vue";
import ModelosForm from "../modelos/form.vue";

import XInputPlate from "../../../../components/InputPlate.vue";

export default {
    components: {
        PropietariosForm,
        MarcasForm,
        ModelosForm,
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
                partida_registral: null,
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
            showDialogNewMarca: false,
            showDialogNewModelo: false,
            editingNumeroInterno: false,
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

        /**
         * Obtiene el siguiente número interno disponible
         */
        async getNextNumeroInterno() {
            try {
                const response = await this.$http.get(
                    `/${this.resource}/next-numero-interno`
                );
                return response.data.next_numero_interno || "";
            } catch (error) {
                console.error(
                    "Error al obtener siguiente número interno:",
                    error
                );
                // Si hay error, generar uno básico basado en timestamp
                return (
                    new Date()
                        .getFullYear()
                        .toString()
                        .slice(-2) + String(Date.now()).slice(-4)
                );
            }
        },

        /**
         * Regenera el número interno
         */
        async regenerarNumeroInterno() {
            try {
                this.form.numero_interno = await this.getNextNumeroInterno();
                this.$message.success("Número interno regenerado");
            } catch (error) {
                console.error("Error al regenerar número interno:", error);
                this.$message.error("Error al regenerar número interno");
            }
        },

        async create() {
            this.titleDialog = this.recordId
                ? "Editar Vehículo"
                : "Nuevo Vehículo";
            this.errors = {};

            // Inicializar formulario
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

            // Si es un nuevo vehículo, autogenerar número interno
            if (!this.recordId) {
                try {
                    this.form.numero_interno = await this.getNextNumeroInterno();
                } catch (error) {
                    console.error("Error al generar número interno:", error);
                    this.$message.warning(
                        "No se pudo autogenerar el número interno"
                    );
                }
            }

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
                        partida_registral: data.partida_registral || "",
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
        },

        /**
         * Muestra el formulario de marcas
         */
        mostrarFormularioMarca() {
            this.showDialogNewMarca = true;
        },

        /**
         * Maneja el cierre del formulario de marcas y actualiza la lista
         */
        closeDialogNewMarca() {
            this.showDialogNewMarca = false;

            // Recargar las marcas
            this.loadMarcas()
                .then(() => {
                    this.$message.success("Lista de marcas actualizada");
                })
                .catch(error => {
                    console.error("Error al recargar marcas:", error);
                    this.$message.error("Error al actualizar las marcas");
                });
        },

        /**
         * Muestra el formulario de modelos
         */
        mostrarFormularioModelo() {
            if (!this.form.marca_id) {
                this.$message.warning("Primero debe seleccionar una marca");
                return;
            }
            this.showDialogNewModelo = true;
        },

        /**
         * Maneja el cierre del formulario de modelos y actualiza la lista
         */
        closeDialogNewModelo() {
            this.showDialogNewModelo = false;

            // Recargar los modelos
            this.loadModelos()
                .then(() => {
                    this.$message.success("Lista de modelos actualizada");
                })
                .catch(error => {
                    console.error("Error al recargar modelos:", error);
                    this.$message.error("Error al actualizar los modelos");
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

.input-group {
    display: flex;
    align-items: stretch;
}

.input-group-append {
    margin-left: 8px;
    display: flex;
    align-items: center;
}

.input-group-append .el-button {
    height: 28px;
    width: 28px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
