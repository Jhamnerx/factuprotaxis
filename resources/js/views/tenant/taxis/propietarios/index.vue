<template>
    <div class="propietarios">
        <div class="page-header pr-0">
            <h2>Propietarios</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Listado de Propietarios</span>
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
                                @change="getColumnsToShow(1)"
                                v-model="column.visible"
                                >{{ column.label }}</el-checkbox
                            >
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
                        <th class="text-right">Tipo de documento</th>
                        <th class="text-right">Número</th>
                        <th
                            v-if="columns.email.visible === true"
                            class="text-center"
                        >
                            Correo
                        </th>
                        <th
                            v-if="columns.telephone.visible === true"
                            class="text-center"
                        >
                            Telefono
                        </th>
                        <th
                            v-if="columns.department.visible === true"
                            class="text-center"
                        >
                            Departamento
                        </th>
                        <th
                            v-if="columns.province.visible === true"
                            class="text-center"
                        >
                            Provincia
                        </th>
                        <th
                            v-if="columns.district.visible === true"
                            class="text-center"
                        >
                            Distrito
                        </th>
                        <th v-if="columns.address.visible">Dirección</th>
                        <th v-if="columns.enabled.visible">Habilitado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr
                        slot-scope="{ index, row }"
                        :class="{ disable_color: !row.enabled }"
                    >
                        <td>{{ row.name }}</td>

                        <td class="text-right">{{ row.document_type }}</td>
                        <td class="text-right">{{ row.number }}</td>

                        <td
                            v-if="columns.email.visible === true"
                            class="text-center"
                        >
                            {{ row.email }}
                        </td>
                        <td
                            v-if="columns.telephone.visible === true"
                            class="text-center"
                        >
                            {{ row.telephone ? row.telephone : "" }}
                        </td>
                        <td
                            v-if="columns.department.visible === true"
                            class="text-center"
                        >
                            {{
                                row.department ? row.department.description : ""
                            }}
                        </td>
                        <td
                            v-if="columns.province.visible === true"
                            class="text-center"
                        >
                            {{ row.province ? row.province.description : "" }}
                        </td>
                        <td
                            v-if="columns.district.visible === true"
                            class="text-center"
                        >
                            {{ row.district ? row.district.description : "" }}
                        </td>
                        <td
                            v-if="columns.address.visible === true"
                            class="text-center"
                        >
                            {{ row.address ? row.address : "" }}
                        </td>

                        <td v-if="columns.enabled.visible">
                            {{ row.enabled ? "Sí" : "No" }}
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button
                                    class="btn btn-default btn-sm"
                                    type="button"
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div
                                    class="dropdown-menu"
                                    aria-labelledby="dropdownMenuButton"
                                >
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="clickEdit(row.id)"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="clickDelete(row.id)"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>

            <propietarios-form
                :api_service_token="api_service_token"
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></propietarios-form>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import PropietariosForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisPropietariosIndex",
    mixins: [deletable],
    props: ["api_service_token", "configuration"],
    components: { DataTable, PropietariosForm },
    created() {
        this.getColumnsToShow();
    },
    data() {
        return {
            showDialog: false,
            recordId: null,
            resource: "propietarios",
            columns: {
                email: {
                    label: "Correo electrónico",
                    visible: false
                },
                telephone: {
                    label: "Teléfono",
                    visible: false
                },
                department: {
                    label: "Departamento",
                    visible: false
                },
                province: {
                    label: "Provincia",
                    visible: false
                },
                district: {
                    label: "Distrito",
                    visible: false
                },
                address: {
                    label: "Dirección",
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
            this.$refs.dataTable.fetchData();
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "propietarios_index",
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
