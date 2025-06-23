<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible="showDialog"
        :append-to-body="true"
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <el-tabs v-model="activeTab">
                    <el-tab-pane label="Datos Generales" name="general">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Tipo
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-select
                                        v-model="form.tipo"
                                        placeholder="Seleccione tipo"
                                        @change="onTipoChange"
                                    >
                                        <el-option
                                            v-for="(label, value) in tipos"
                                            :key="value"
                                            :label="label"
                                            :value="value"
                                        />
                                    </el-select>
                                    <small
                                        v-if="errors.tipo"
                                        class="form-control-feedback"
                                        v-text="errors.tipo[0]"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6" v-if="form.tipo === 'baja'">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Tipo de baja
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-select
                                        v-model="form.tipo_baja"
                                        placeholder="Seleccione tipo de baja"
                                    >
                                        <el-option
                                            label="Por pérdida de vínculo contractual por declaración jurada"
                                            value="vinculo"
                                        />
                                        <el-option
                                            label="Con constancia"
                                            value="constancia"
                                        />
                                        <el-option
                                            label="Independiente"
                                            value="independiente"
                                        />
                                    </el-select>
                                    <small
                                        v-if="errors.tipo_baja"
                                        class="form-control-feedback"
                                        v-text="errors.tipo_baja[0]"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div
                                class="col-md-6"
                                v-if="
                                    form.tipo === 'registro' ||
                                        form.tipo === 'baja' ||
                                        form.tipo === 'correccion_datos'
                                "
                            >
                                <div class="form-group">
                                    <label class="control-label"
                                        >Vehículo
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-select
                                        v-model="vehiculoSeleccionado"
                                        filterable
                                        remote
                                        reserve-keyword
                                        placeholder="Buscar vehículo por placa o propietario"
                                        :remote-method="searchRemoteVehiculo"
                                        :loading="loadingVehiculos"
                                        @change="changeVehiculo"
                                    >
                                        <el-option
                                            v-for="v in vehiculos"
                                            :key="v.id"
                                            :label="
                                                v.placa +
                                                    ' - ' +
                                                    (v.propietario
                                                        ? v.propietario.name
                                                        : '')
                                            "
                                            :value="v.id"
                                        />
                                    </el-select>
                                    <small
                                        v-if="errors.vehiculo"
                                        class="form-control-feedback"
                                        v-text="errors.vehiculo[0]"
                                    />
                                </div>
                            </div>
                            <div
                                class="col-md-6"
                                v-if="
                                    form.tipo_baja === 'constancia' &&
                                        vehiculoSeleccionado
                                "
                            >
                                <div class="form-group">
                                    <label class="control-label"
                                        >Constancia
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <el-select
                                        v-model="form.constancia_id"
                                        placeholder="Seleccione una constancia"
                                        filterable
                                        remote
                                        reserve-keyword
                                        :remote-method="searchRemoteConstancias"
                                        :loading="loadingConstancias"
                                    >
                                        <el-option
                                            v-for="constancia in constancias"
                                            :key="constancia.id"
                                            :label="
                                                constancia.vehiculo.placa +
                                                    ' - ' +
                                                    constancia.fecha_emision
                                            "
                                            :value="constancia.id"
                                        />
                                    </el-select>
                                    <small
                                        v-if="errors.constancia_id"
                                        class="form-control-feedback"
                                        v-text="errors.constancia_id[0]"
                                    />
                                </div>
                            </div>
                            <div
                                class="col-md-6"
                                v-if="form.tipo === 'emision'"
                            >
                                <div class="form-group">
                                    <label class="control-label"
                                        >Seleccionar unidades</label
                                    >
                                    <el-select
                                        v-model="form.unidades_emision"
                                        multiple
                                        filterable
                                        remote
                                        reserve-keyword
                                        placeholder="Buscar y seleccionar unidades"
                                        :remote-method="searchRemoteVehiculo"
                                        :loading="loadingVehiculos"
                                    >
                                        <el-option
                                            v-for="v in vehiculos"
                                            :key="v.id"
                                            :label="
                                                v.placa +
                                                    ' - ' +
                                                    (v.propietario
                                                        ? v.propietario.name
                                                        : '')
                                            "
                                            :value="v.id"
                                        />
                                    </el-select>
                                    <el-checkbox
                                        v-model="selectAllEmision"
                                        @change="toggleSelectAllEmision"
                                        >Seleccionar todas</el-checkbox
                                    >
                                </div>
                            </div>
                        </div>
                        <div
                            class="row"
                            v-if="
                                form.tipo === 'registro' ||
                                    form.tipo === 'baja' ||
                                    form.tipo === 'correccion_datos'
                            "
                        >
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Propietario actual</label
                                    >
                                    <el-input
                                        v-model="propietarioActual"
                                        disabled
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Descripción</label
                                    >
                                    <el-input
                                        type="textarea"
                                        v-model="form.descripcion"
                                    />
                                    <small
                                        v-if="errors.descripcion"
                                        class="form-control-feedback"
                                        v-text="errors.descripcion[0]"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Motivo</label>
                                    <el-input
                                        type="textarea"
                                        v-model="form.motivo"
                                    />
                                    <small
                                        v-if="errors.motivo"
                                        class="form-control-feedback"
                                        v-text="errors.motivo[0]"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Adjuntar PDF(s)</label
                                    >
                                    <el-upload
                                        ref="upload"
                                        :action="`/${resource}/upload`"
                                        :data="{ type: 'solicitudes' }"
                                        :file-list="fileList"
                                        :headers="headers"
                                        :auto-upload="true"
                                        :multiple="true"
                                        :show-file-list="true"
                                        :on-success="onSuccess"
                                        :on-remove="handleRemoveFile"
                                        :on-change="handleChangeFile"
                                    >
                                        <el-button size="small" type="primary"
                                            >Seleccionar PDF</el-button
                                        >
                                    </el-upload>
                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane
                        label="Vehículos adicionales"
                        name="detalle"
                        v-if="form.tipo === 'emision'"
                    >
                        <el-table
                            :data="vehiculosSeleccionadosEmision"
                            style="width: 100%"
                            v-if="vehiculosSeleccionadosEmision.length"
                        >
                            <el-table-column prop="placa" label="Placa" />
                            <el-table-column
                                prop="propietario.name"
                                label="Propietario"
                                :formatter="
                                    row =>
                                        row.propietario && row.propietario.name
                                            ? row.propietario.name
                                            : ''
                                "
                            />
                        </el-table>
                        <div v-else class="text-muted">
                            No hay unidades seleccionadas.
                        </div>
                    </el-tab-pane>
                    <el-tab-pane
                        label="Correcciones"
                        name="correcciones"
                        v-if="form.tipo === 'correccion_datos'"
                    >
                        <div v-if="vehiculoSeleccionado">
                            <div
                                v-for="(corr, idx) in form.correcciones"
                                :key="idx"
                                class="mb-2"
                            >
                                <div class="row">
                                    <div class="col-md-4">
                                        <el-select
                                            v-model="corr.campo"
                                            placeholder="Campo a corregir"
                                            size="mini"
                                            class="mb-1"
                                        >
                                            <el-option
                                                label="Número Interno"
                                                value="numero_interno"
                                            />
                                            <el-option
                                                label="Placa"
                                                value="placa"
                                            />
                                            <el-option
                                                label="Largo"
                                                value="largo"
                                            />
                                            <el-option
                                                label="Ancho"
                                                value="ancho"
                                            />
                                            <el-option
                                                label="Alto"
                                                value="alto"
                                            />
                                            <el-option
                                                label="Peso"
                                                value="peso"
                                            />
                                            <el-option
                                                label="Carga Útil"
                                                value="carga_util"
                                            />
                                            <el-option
                                                label="CCN"
                                                value="ccn"
                                            />
                                            <el-option
                                                label="Número de Motor"
                                                value="numero_motor"
                                            />
                                            <el-option
                                                label="Ejes"
                                                value="ejes"
                                            />
                                            <el-option
                                                label="Asientos"
                                                value="asientos"
                                            />
                                            <el-option
                                                label="Categoría"
                                                value="categoria"
                                            />
                                            <el-option
                                                label="Marca"
                                                value="marca_id"
                                            />
                                            <el-option
                                                label="Modelo"
                                                value="modelo_id"
                                            />
                                            <el-option
                                                label="Color"
                                                value="color"
                                            />
                                        </el-select>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            v-model="corr.valor_anterior"
                                            placeholder="Valor anterior"
                                            size="mini"
                                            class="mb-1"
                                            disabled
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <el-input
                                            v-model="corr.valor_nuevo"
                                            placeholder="Valor nuevo"
                                            size="mini"
                                            class="mb-1"
                                        />
                                    </div>
                                    <div
                                        class="col-md-1 d-flex align-items-center"
                                    >
                                        <el-button
                                            type="danger"
                                            icon="el-icon-delete"
                                            size="mini"
                                            @click="removeCorreccion(idx)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <el-button
                                type="primary"
                                size="mini"
                                @click="addCorreccion"
                                >Agregar corrección</el-button
                            >
                        </div>
                        <div v-else class="text-muted">
                            Seleccione un vehículo para agregar correcciones.
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click="close">Cancelar</el-button>
                <el-button type="primary" @click="submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "recordId"],
    data() {
        return {
            loading_submit: false,
            activeTab: "general",
            titleDialog: "Nueva Solicitud",
            resource: "solicitudes",
            headers: headers_token,
            form: {
                id: this.recordId || null,
                tipo: "",
                descripcion: null,
                motivo: null,
                tipo_baja: null,
                constancia_id: null,
                unidades_emision: [],
                correcciones: [],
                observaciones: null,
                estado: "pendiente",
                fecha: new Date().toISOString().slice(0, 10),
                documentos_adjuntos: [],
                documentos_adjuntos_originales: [],
                detalle: [],
                documentos_eliminados: []
                // Agrega aquí cualquier otro campo individual que uses en el backend
            },
            tipos: {
                registro: "Registro de Unidad",
                baja: "Baja de Unidad",
                emision: "Emisión de TUC",
                correccion_datos: "Corrección de Datos"
            },
            vehiculos: [],
            all_vehiculos: [],
            constancias: [],
            propietarioActual: "",
            fileList: [],
            vehiculoDetalleId: null,
            loadingVehiculos: false,
            loadingConstancias: false,
            selectAllEmision: false,
            errors: {},
            vehiculoSeleccionado: null
        };
    },
    computed: {
        vehiculosSeleccionadosEmision() {
            return this.vehiculos.filter(v =>
                this.form.unidades_emision.includes(v.id)
            );
        }
    },
    watch: {
        "form.tipo_baja"(newVal) {
            if (newVal !== "constancia") {
                this.form.constancia_id = null;
                this.constancias = [];
            } else if (newVal === "constancia" && this.vehiculoSeleccionado) {
                // Si cambia a constancia y ya hay un vehículo seleccionado, cargar las constancias
                this.searchRemoteConstancias("");
            }
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`).then(response => {
            this.vehiculos = response.data.vehiculos;
            this.all_vehiculos = response.data.vehiculos;
            //this.constancias = response.data.constancias_baja;
        });
        await this.fetchVehiculos();
        if (this.recordId) this.fetchRecord();
    },
    methods: {
        initForm() {
            this.form = {
                id: this.recordId,
                tipo: "",
                descripcion: null,
                motivo: null,
                tipo_baja: null,
                constancia_id: null,
                unidades_emision: [],
                correcciones: [],
                observaciones: null,
                estado: "pendiente",
                fecha: new Date().toISOString().slice(0, 10),
                documentos_adjuntos: [],
                documentos_adjuntos_originales: [],
                detalle: [],
                documentos_eliminados: []
                // Agrega aquí cualquier otro campo individual que uses en el backend
            };
            this.propietarioActual = "";
            this.fileList = [];
            this.vehiculoDetalleId = null;
        },
        async getTables() {
            await this.$http.get(`/${this.resource}/tables`).then(response => {
                this.vehiculos = response.data.vehiculos;
                this.all_vehiculos = response.data.vehiculos;
            });
        },
        create() {
            this.titleDialog = this.recordId
                ? "Editar Solicitud"
                : "Nueva Solicitud";
            if (this.recordId) {
                this.fetchRecord();
            }
        },
        fetchVehiculos(query = "") {
            this.loadingVehiculos = true;
            this.$http
                .get("/unidades/search", { params: { q: query } })
                .then(r => {
                    this.vehiculos = r.data.data;
                })
                .finally(() => {
                    this.loadingVehiculos = false;
                });
        },
        onTipoChange() {
            this.form.tipo_baja = null;
            this.vehiculoSeleccionado = null;
            this.propietarioActual = null;
            this.form.descripcion = null;
            this.form.motivo = null;
            this.form.unidades_emision = [];
            this.form.correcciones = [];
            this.selectAllEmision = false;
        },
        toggleSelectAllEmision(val) {
            if (val) {
                this.form.unidades_emision = this.vehiculos.map(v => v.id);
            } else {
                this.form.unidades_emision = [];
            }
        },
        searchRemoteVehiculo(query) {
            if (query && query.length > 0) {
                this.loadingVehiculos = true;
                this.$http
                    .get("/unidades/search", { params: { q: query } })
                    .then(r => {
                        this.vehiculos = r.data.data;
                    })
                    .finally(() => {
                        this.loadingVehiculos = false;
                    });
            } else {
                this.vehiculos = this.all_vehiculos;
                this.loadingVehiculos = false;
            }
        },
        searchRemoteConstancias(query) {
            console.log(
                "Buscando constancias con query:",
                query,
                "para vehículo:",
                this.vehiculoSeleccionado
            );

            if (this.vehiculoSeleccionado) {
                this.loadingConstancias = true;

                // Enviar el ID del vehículo seleccionado como parámetro v
                this.$http
                    .get("/constancias/search", {
                        params: {
                            q: query,
                            v: this.vehiculoSeleccionado
                        }
                    })
                    .then(r => {
                        console.log("Constancias recibidas:", r.data.data);
                        this.constancias = r.data.data;
                    })
                    .catch(error => {
                        console.error("Error al cargar constancias:", error);
                        this.constancias = [];
                    })
                    .finally(() => {
                        this.loadingConstancias = false;
                    });
            } else {
                // Si no hay vehículo seleccionado, limpiar la lista de constancias
                console.log(
                    "No hay vehículo seleccionado, no se cargan constancias"
                );
                this.constancias = [];
                this.loadingConstancias = false;
            }
        },
        changeVehiculo(val) {
            const v = this.vehiculos.find(x => x.id === val);
            this.propietarioActual =
                v && v.propietario ? v.propietario.name : "";

            // Si es tipo constancia, resetear el valor de constancia_id y cargar constancias
            if (this.form.tipo_baja === "constancia") {
                this.form.constancia_id = null;
                // Usar searchRemoteConstancias con string vacío para cargar todas las constancias disponibles
                this.searchRemoteConstancias("");
            }
            // Para todos los tipos, siempre que se seleccione un vehículo, agregarlo a detalle (si no existe)
            if (v && !this.form.detalle.some(d => d.id === v.id)) {
                this.form.detalle = [
                    {
                        id: v.id,
                        placa: v.placa,
                        vehiculo: v, // Guardar el JSON completo del vehículo
                        propietario: v.propietario || null, // Guardar el JSON completo del propietario
                        correcciones:
                            this.form.tipo === "correccion_datos"
                                ? this.form.correcciones
                                : undefined
                    }
                ];
            } else if (v) {
                // Si ya existe, actualizar datos
                const idx = this.form.detalle.findIndex(d => d.id === v.id);
                if (idx !== -1) {
                    this.form.detalle[idx].vehiculo = v;
                    this.form.detalle[idx].propietario = v.propietario || null;
                    if (this.form.tipo === "correccion_datos") {
                        this.form.detalle[
                            idx
                        ].correcciones = this.form.correcciones;
                    }
                }
            }
        },
        addCorreccion() {
            this.form.correcciones.push({
                campo: "",
                valor_anterior: "",
                valor_nuevo: ""
            });
        },
        removeCorreccion(idx) {
            this.form.correcciones.splice(idx, 1);
        },
        fetchRecord() {
            this.$http
                .get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    // Cargar datos principales
                    this.form = { ...this.form, ...response.data.data };
                    // Guardar copia de los documentos originales para comparar en edición
                    this.form.documentos_adjuntos_originales = (
                        response.data.data.documentos_adjuntos || []
                    ).map(doc => ({ ...doc }));

                    // Cargar detalle
                    if (
                        response.data.data.detalle &&
                        Array.isArray(response.data.data.detalle)
                    ) {
                        this.form.detalle = response.data.data.detalle.map(
                            det => ({
                                ...det,
                                vehiculo: det.vehiculo || det,
                                propietario: det.propietario || null,
                                correcciones: det.correcciones || []
                            })
                        );
                    }
                    // Selección de vehículo principal o múltiple según tipo
                    if (
                        this.form.tipo === "registro" ||
                        this.form.tipo === "baja" ||
                        this.form.tipo === "correccion_datos"
                    ) {
                        if (this.form.detalle.length > 0) {
                            this.vehiculoSeleccionado = this.form.detalle[0].vehiculo_id;
                            this.propietarioActual =
                                this.form.detalle[0].propietario || "";
                            if (this.form.tipo === "correccion_datos") {
                                this.form.correcciones =
                                    this.form.detalle[0].correcciones || [];
                            }
                        }
                    } else if (this.form.tipo === "emision") {
                        this.form.unidades_emision = this.form.detalle.map(
                            d => d.vehiculo_id
                        );
                    }
                    // Cargar archivos adjuntos
                    this.fileList = (this.form.documentos_adjuntos || []).map(
                        doc => ({
                            name: doc.nombre || doc,
                            url:
                                doc.ruta && doc.ruta.startsWith("public")
                                    ? `/storage/${doc.ruta.replace(
                                          /^public[\\/]/,
                                          ""
                                      )}`
                                    : `/storage/${doc.ruta || doc}`
                        })
                    );
                });
        },
        onVehiculoChange(val) {
            const v = this.vehiculos.find(x => x.id === val);
            this.propietarioActual =
                v && v.propietario ? v.propietario.name : "";
        },
        addVehiculoDetalle() {
            const v = this.vehiculos.find(x => x.id === this.vehiculoDetalleId);
            if (v && !this.form.detalle.some(d => d.id === v.id)) {
                this.form.detalle.push({
                    id: v.id,
                    placa: v.placa,
                    propietario: v.propietario ? v.prop.propietario : null,
                    correcciones:
                        this.form.tipo === "correccion_datos" ? [] : undefined
                });
            }
            this.vehiculoDetalleId = null;
        },
        handleUploadSuccess(res, file, fileList) {
            if (res.success && res.path) {
                this.form.documentos_adjuntos.push({
                    nombre: file.name,
                    path: res.path
                });
            }
        },
        handleChangeFile(file, fileList) {
            this.fileList = fileList;
        },
        handleRemoveFile(file, fileList) {
            this.fileList = fileList;
            // Quitar también del array documentos_adjuntos
            if (file && file.name) {
                this.form.documentos_adjuntos = this.form.documentos_adjuntos.filter(
                    doc => doc.nombre !== file.name
                );
            }
        },
        async submit() {
            this.errors = {}; // Limpiar errores antes de enviar
            this.loading_submit = true;

            // Si el tipo es 'emision', copiar unidades_emision a detalle con el formato adecuado
            if (this.form.tipo === "emision") {
                this.form.detalle = this.vehiculos
                    .filter(v => this.form.unidades_emision.includes(v.id))
                    .map(v => ({
                        id: v.id,
                        placa: v.placa,
                        vehiculo: v,
                        propietario: v.propietario || null,
                        correcciones: []
                    }));
            } else if (
                this.form.tipo === "registro" ||
                this.form.tipo === "baja" ||
                this.form.tipo === "correccion_datos"
            ) {
                // Asegurar que detalle tenga vehiculo y propietario
                if (this.vehiculoSeleccionado) {
                    const v = this.vehiculos.find(
                        x => x.id === this.vehiculoSeleccionado
                    );
                    if (v) {
                        this.form.detalle = [
                            {
                                id: v.id,
                                placa: v.placa,
                                vehiculo: v,
                                propietario: v.propietario || null,
                                correcciones:
                                    this.form.tipo === "correccion_datos"
                                        ? this.form.correcciones
                                        : []
                            }
                        ];
                    }
                }
            }

            // Documentos originales antes de editar
            const documentosOriginales = (
                this.form.documentos_adjuntos_originales || []
            ).map(doc => doc.nombre);
            // Documentos actuales (después de edición)
            const documentosActuales = (
                this.form.documentos_adjuntos || []
            ).map(doc => doc.nombre);
            // Documentos eliminados
            this.form.documentos_eliminados = documentosOriginales.filter(
                nombre => !documentosActuales.includes(nombre)
            );

            let path = `/${this.resource}`;
            if (this.recordId) {
                path = `/${this.resource}/${this.form.id}/update`;
            }

            await this.$http
                .post(path, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                        this.close();
                        this.getTables();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        onSuccess(response, file, fileList) {
            if (response.success && response.data) {
                // Agregar el documento subido a la lista de adjuntos
                this.form.documentos_adjuntos.push({
                    nombre: response.data.filename,
                    temp_file: response.data.temp_image,
                    temp_path: response.data.temp_path,
                    tipo: file.type || ""
                });
            } else {
                this.$message.error(response.message);
            }
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
            this.propietarioActual = null;
            this.vehiculoSeleccionado = null;
            this.fileList = [];
            // Limpiar input de el-upload (si hay referencia)
            if (this.$refs.upload) {
                this.$refs.upload.clearFiles();
            }
        }
    }
};
</script>
