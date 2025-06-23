<template>
    <div class="solicitudes">
        <div class="page-header pr-0">
            <h2>Solicitudes</h2>
            <div class="right-wrapper pull-right">
                <el-button type="primary" @click="clickCreate()">
                    <i class="fa fa-plus-circle"></i> Nueva Solicitud
                </el-button>
            </div>
        </div>
        <div class="card tab-content-default row-new mb-0">
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i
                            class="el-icon-arrow-down el-icon--right"
                        ></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item
                            v-for="(column, key) in columns"
                            :key="key"
                        >
                            <el-checkbox
                                @change="getColumnsToShow(1)"
                                v-model="column.visible"
                                >{{ column.title }}</el-checkbox
                            >
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Motivo</th>
                        <th>Placa</th>
                        <th v-if="columns.estado.visible">Estado</th>
                        <th>Fecha</th>
                        <th v-if="columns.documentos.visible">Documentos</th>
                        <th v-if="columns.acciones.visible">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ row.id }}</td>
                        <td>
                            {{ row.tipo_texto }}
                            {{ (row.tipo = "baja" ? row.tipo_baja : "") }}
                        </td>
                        <td>
                            {{ row.descripcion ? row.descripcion : "" }}
                        </td>
                        <td>{{ row.motivo ? row.motivo : "" }}</td>
                        <td>
                            <span v-if="row.detalle && row.detalle.length">
                                {{
                                    row.detalle
                                        .map(d =>
                                            d.vehiculo && d.vehiculo.placa
                                                ? d.vehiculo.placa
                                                : d.placa || ""
                                        )
                                        .filter(p => !!p)
                                        .join(", ")
                                }}
                            </span>
                            <span v-else>-</span>
                        </td>
                        <td v-if="columns.estado.visible">{{ row.estado }}</td>
                        <td>{{ row.fecha }}</td>
                        <td v-if="columns.documentos.visible">
                            <el-link
                                v-if="row.cantidad_documentos > 0"
                                @click="verDocumentos(row)"
                            >
                                {{ row.cantidad_documentos }} adjuntos
                            </el-link>
                        </td>
                        <td v-if="columns.acciones.visible">
                            <el-button size="mini" @click="descargarPDF(row)"
                                ><i class="fas fa-download"></i
                            ></el-button>
                            <el-button size="mini" @click="clickEdit(row.id)"
                                ><i class="fa fa-edit"></i
                            ></el-button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
        <solicitudes-form
            :showDialog.sync="showDialog"
            :recordId="recordId"
            @close="closeDialog"
        />
        <el-dialog :visible.sync="showDocs" title="Documentos Adjuntos">
            <ul class="list-group list-group-flush">
                <li
                    v-for="(doc, idx) in documentosAdjuntos"
                    :key="idx"
                    class="list-group-item d-flex align-items-center"
                >
                    <i
                        class="fa fa-file-pdf-o text-danger mr-2"
                        v-if="doc.nombre && doc.nombre.endsWith('.pdf')"
                    ></i>
                    <i class="fa fa-file-image-o text-primary mr-2" v-else></i>
                    <a
                        :href="doc.url"
                        target="_blank"
                        class="ml-1 text-primary font-weight-bold d-flex align-items-center"
                    >
                        {{ doc.nombre }}
                        <i class="fa fa-download ml-2"></i>
                    </a>
                </li>
            </ul>
        </el-dialog>
    </div>
</template>

<script>
import SolicitudesForm from "./form.vue";
import DataTable from "../../../../components/DataTable.vue";
import { deletable } from "../../../../mixins/deletable";
export default {
    name: "SolicitudesIndex",

    components: { SolicitudesForm, DataTable },
    mixins: [deletable],
    data() {
        return {
            showDialog: false,
            recordId: null,
            showDocs: false,
            documentosAdjuntos: [],
            resource: "solicitudes",
            columns: {
                estado: { title: "Estado", visible: true },
                documentos: { title: "Documentos", visible: true },
                acciones: { title: "Acciones", visible: true }
            }
        };
    },
    methods: {
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickEdit(recordId) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        closeDialog() {
            this.showDialog = false;
            this.recordId = null;
            this.$refs.dataTable.fetchData();
        },
        verDocumentos(row) {
            this.documentosAdjuntos = (row.documentos_adjuntos || []).map(
                doc => ({
                    nombre: doc.nombre || doc,
                    url:
                        doc.ruta && doc.ruta.startsWith("public")
                            ? `/storage/${doc.ruta.replace(/^public[\\/]/, "")}`
                            : `/storage/${doc.ruta || doc}`
                })
            );
            this.showDocs = true;
        },
        descargarPDF(row) {
            if (row.download_solicitud) {
                window.open(row.download_solicitud, "_blank");
            } else {
                this.$message.error("No se ha encontrado la URL del contrato");
            }
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "solicitudes_index",
                    updated: updated !== undefined
                })
                .then(response => {
                    if (updated === undefined) {
                        let currentCols = response.data.columns;
                        if (currentCols !== undefined) {
                            this.columns = currentCols;
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
};
</script>
