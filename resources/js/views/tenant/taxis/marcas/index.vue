<template>
    <div class="marcas">
        <div class="page-header pr-0">
            <h2>Marcas</h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Listado de Marcas</span>
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
                            v-for="(column, key) in columns"
                            :key="key"
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
                        <th v-if="columns.make_country.visible === true">
                            País de Origen
                        </th>
                        <th v-if="columns.created_at.visible === true">
                            Fecha Registro
                        </th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr
                        slot-scope="{ index, row }"
                        :class="{ disable_color: !row.enabled }"
                    >
                        <td>{{ row.nombre }}</td>
                        <td v-if="columns.make_country.visible === true">
                            {{ row.make_country }}
                        </td>
                        <td v-if="columns.created_at.visible === true">
                            {{ row.created_at }}
                        </td>
                        <td class="text-right">
                            <button
                                type="button"
                                class="btn waves-effect waves-light btn-xs btn-info"
                                @click.prevent="clickEdit(row.id)"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                type="button"
                                class="btn waves-effect waves-light btn-xs btn-danger"
                                @click.prevent="clickDelete(row.id)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>
            <marca-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></marca-form>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import MarcaForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";

export default {
    name: "MarcasIndex",
    mixins: [deletable],
    components: { DataTable, MarcaForm },
    data() {
        return {
            showDialog: false,
            recordId: null,
            resource: "marcas",
            columns: {
                make_country: { label: "País de Origen", visible: true },
                created_at: { label: "Fecha Registro", visible: true }
            }
        };
    },
    created() {
        this.getColumnsToShow();
    },
    methods: {
        clickCreate() {
            this.recordId = null;
            this.showDialog = true;
        },
        clickEdit(id) {
            this.recordId = id;
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
                    report: "marcas_index",
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
