<template>
    <div class="conductores">
        <div class="page-header pr-0">
            <h2>Conductores</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Listado de Conductores</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <button
                    class="btn btn-custom btn-sm mt-2 mr-2"
                    type="button"
                    @click.prevent="clickCreate()"
                >
                    <i class="fa fa-plus-circle"></i> Nuevo
                </button>
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
                            v-for="(column, index) in columns"
                            :key="index"
                        >
                            <el-checkbox
                                v-model="column.visible"
                                @change="getColumnsToShow(true)"
                            >
                                {{ column.label }}
                            </el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table
                    :columns="columns"
                    :resource="resource"
                    @click-create="clickCreate"
                    @click-edit="clickEdit"
                    @click-delete="clickDelete"
                >
                    <tr slot="heading">
                        <th>Nombre</th>
                        <th class="text-right">Número de documento</th>
                        <th class="text-center">Licencia</th>
                        <th v-if="columns.fecha_nacimiento_formatted.visible">
                            Fecha de Nacimiento
                        </th>
                        <th v-if="columns.edad.visible">Edad</th>
                        <th v-if="columns.address.visible">Dirección</th>
                        <th v-if="columns.telephone_1.visible">Teléfono 1</th>
                        <th v-if="columns.telephone_2.visible">Teléfono 2</th>
                        <th v-if="columns.telephone_3.visible">Teléfono 3</th>
                        <th v-if="columns.enabled.visible">Habilitado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr
                        slot-scope="{ row }"
                        :class="{ disable_color: !row.enabled }"
                    >
                        <td>{{ row.name }}</td>
                        <td class="text-right">{{ row.number }}</td>
                        <td class="text-center">
                            <div v-if="row.licencia && row.licencia.numero">
                                <el-tag
                                    :type="
                                        row.has_valid_license
                                            ? 'success'
                                            : 'warning'
                                    "
                                    size="mini"
                                >
                                    {{ row.primary_license_status }}
                                </el-tag>
                                <br />
                                <small class="text-muted">
                                    {{ row.primary_license_number }} ({{
                                        row.primary_license_category
                                    }})
                                </small>
                                <br />
                                <small class="text-muted">
                                    Vence: {{ row.primary_license_expiration }}
                                </small>
                            </div>
                            <div v-else>
                                <el-tag type="danger" size="mini"
                                    >Sin licencia</el-tag
                                >
                            </div>
                        </td>
                        <td v-if="columns.fecha_nacimiento_formatted.visible">
                            {{ row.fecha_nacimiento_formatted }}
                        </td>
                        <td v-if="columns.edad.visible">{{ row.edad }} años</td>
                        <td v-if="columns.address.visible">
                            {{ row.address }}
                        </td>
                        <td v-if="columns.telephone_1.visible">
                            {{ row.telephone_1 }}
                        </td>
                        <td v-if="columns.telephone_2.visible">
                            {{ row.telephone_2 }}
                        </td>
                        <td v-if="columns.telephone_3.visible">
                            {{ row.telephone_3 }}
                        </td>
                        <td v-if="columns.enabled.visible">
                            <el-tag :type="row.enabled ? 'success' : 'danger'">
                                {{
                                    row.enabled ? "Habilitado" : "Deshabilitado"
                                }}
                            </el-tag>
                        </td>
                        <td class="text-right">
                            <el-button
                                circle
                                size="mini"
                                @click="clickEdit(row.id)"
                                type="primary"
                                icon="el-icon-edit"
                            ></el-button>
                            <el-button
                                circle
                                size="mini"
                                @click="clickDelete(row.id)"
                                type="danger"
                                icon="el-icon-delete"
                            ></el-button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <conductores-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></conductores-form>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import ConductoresForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisConductoresIndex",
    mixins: [deletable],
    props: ["configuration"],
    components: { DataTable, ConductoresForm },
    created() {
        this.getColumnsToShow();
    },
    data() {
        return {
            showDialog: false,
            recordId: null,
            resource: "conductores",
            columns: {
                address: {
                    label: "Dirección",
                    visible: false
                },
                fecha_nacimiento_formatted: {
                    label: "Fecha de Nacimiento",
                    visible: false
                },
                edad: {
                    label: "Edad",
                    visible: false
                },
                telephone_1: {
                    label: "Teléfono 1",
                    visible: false
                },
                telephone_2: {
                    label: "Teléfono 2",
                    visible: false
                },
                telephone_3: {
                    label: "Teléfono 3",
                    visible: false
                },
                enabled: {
                    label: "Habilitado",
                    visible: true
                }
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
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        closeDialog() {
            this.showDialog = false;
            this.$eventHub.$emit("reloadData");
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "conductores_index",
                    updated: updated !== undefined
                })
                .then(response => {
                    if (updated === undefined) {
                        let currentCols = response.data.columns;
                        if (currentCols !== undefined) {
                            Object.keys(currentCols).forEach(key => {
                                if (this.columns[key] !== undefined) {
                                    this.columns[key].visible =
                                        currentCols[key].visible;
                                }
                            });
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
