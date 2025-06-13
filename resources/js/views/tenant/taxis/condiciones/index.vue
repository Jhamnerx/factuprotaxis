<template>
    <div class="condiciones-admin">
        <div class="page-header pr-0">
            <h2>Condiciones de la Unidad</h2>
            <ol class="breadcrumbs">
                <li class="active"></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button class="btn btn-custom" @click.prevent="clickCreate()">
                    <i class="fa fa-plus"></i> Nueva Condición
                </button>
            </div>
        </div>
        <div class="card tab-content-default row-new mb-0">
            <div class="data-table-visible-columns">
                <div class="row">
                    <div class="col-md-12">
                        <el-dropdown :hide-on-click="false">
                            <el-button type="primary">
                                <i class="fa fa-columns"></i> Mostrar/Ocultar
                                columnas
                                <i
                                    class="el-icon-arrow-down el-icon--right"
                                ></i>
                            </el-button>
                            <el-dropdown-menu
                                slot="dropdown"
                                class="column-selection-dropdown"
                            >
                                <el-dropdown-item
                                    v-for="(column, index) in columns"
                                    :key="index"
                                >
                                    <el-checkbox
                                        @change="getColumnsToShow(1)"
                                        v-model="column.visible"
                                    >
                                        {{ column.label }}
                                    </el-checkbox>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <data-table
                    ref="dataTable"
                    :columns="columns"
                    :resource="resource"
                    @click-create="clickCreate"
                    @click-edit="clickEdit"
                    @click-delete="clickDelete"
                >
                    <tr slot="heading">
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th v-if="columns.created_at.visible">
                            Fecha Registro
                        </th>
                        <th v-if="columns.updated_at.visible">
                            Fecha Actualización
                        </th>
                        <th>Acciones</th>
                    </tr>
                    <tr slot-scope="{ row }">
                        <td>{{ row.id }}</td>
                        <td>{{ row.descripcion }}</td>
                        <td>
                            <span
                                :style="{
                                    background: row.color,
                                    display: 'inline-block',
                                    width: '24px',
                                    height: '24px',
                                    borderRadius: '4px',
                                    border: '1px solid #ccc'
                                }"
                            ></span>
                            <span class="ml-2">{{ row.color }}</span>
                        </td>

                        <td v-if="columns.created_at.visible">
                            {{ row.created_at }}
                        </td>
                        <td v-if="columns.updated_at.visible">
                            {{ row.updated_at }}
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
            <condiciones-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></condiciones-form>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import CondicionesForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "TenantTaxisCondicionesIndex",
    mixins: [deletable],
    components: { DataTable, CondicionesForm },
    created() {
        this.getColumnsToShow();
    },
    data() {
        return {
            showDialog: false,
            resource: "condiciones",
            recordId: null,
            columns: {
                color: { label: "Color", visible: true },
                descripcion: { label: "Descripción", visible: true },
                created_at: { label: "Fecha Registro", visible: false },
                updated_at: { label: "Fecha Actualización", visible: false }
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
            this.destroy(`/${this.resource}/${id}`).then(() => {
                this.$refs.dataTable.fetchData();
            });
        },
        closeDialog() {
            this.showDialog = false;
            this.recordId = null;
            this.$refs.dataTable.fetchData();
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "condiciones_index",
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

<style scoped>
.condiciones-admin .btn-custom i {
    margin-right: 5px;
}
.condiciones-admin .btn-custom {
    background-color: #409eff;
    color: #fff;
}
.condiciones-admin .btn-custom:hover {
    background-color: #2889e9;
}
</style>
