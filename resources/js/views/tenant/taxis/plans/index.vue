<template>
    <div class="plans-admin">
        <div class="page-header pr-0">
            <h2>Planes</h2>
            <ol class="breadcrumbs">
                <li class="active"></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button class="btn btn-custom" @click.prevent="clickCreate()">
                    <i class="fa fa-plus"></i> Nuevo Plan
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
                                        {{ column.title }}
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
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Activo</th>
                        <th v-if="columns.currency.visible">Moneda</th>
                        <th>Periodo</th>
                        <th v-if="columns.invoice_interval.visible">
                            Intervalo
                        </th>
                        <th v-if="columns.sort_order.visible">Orden</th>
                        <th v-if="columns.is_socio.visible">Socio</th>
                        <th class="text-right">Acciones</th>
                    </tr>

                    <tr slot-scope="{ row }">
                        <td>{{ row.name }}</td>
                        <td>
                            {{ row.description }}
                        </td>
                        <td>{{ row.price }}</td>
                        <td>
                            <el-tag
                                :type="row.is_active ? 'success' : 'danger'"
                            >
                                {{ row.is_active ? "Sí" : "No" }}
                            </el-tag>
                        </td>
                        <td v-if="columns.currency.visible">
                            {{ row.currency }}
                        </td>
                        <td>
                            {{
                                row.invoice_interval == "indeterminate"
                                    ? "-"
                                    : row.invoice_interval
                            }}
                        </td>
                        <td v-if="columns.invoice_interval.visible">
                            <span
                                v-if="row.invoice_interval === 'indeterminate'"
                                >Indeterminado</span
                            >
                            <span v-if="row.invoice_interval == 'year'"
                                >Anual</span
                            >
                            <span v-if="row.invoice_interval == 'month'"
                                >Mensual</span
                            >
                        </td>
                        <td v-if="columns.sort_order.visible">
                            {{ row.sort_order }}
                        </td>
                        <td v-if="columns.is_socio.visible">
                            <el-tag :type="row.is_socio ? 'info' : 'default'">
                                {{ row.is_socio ? "Sí" : "No" }}
                            </el-tag>
                        </td>
                        <td class="text-right">
                            <button
                                type="button"
                                class="btn btn-xs btn-info"
                                @click.prevent="clickEdit(row.id)"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-xs btn-danger"
                                @click.prevent="clickDelete(row.id)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>
            <plan-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                @close="closeDialog"
            ></plan-form>
        </div>
    </div>
</template>

<script>
import DataTable from "../../../../components/DataTable.vue";
import PlanForm from "./form.vue";
import { deletable } from "../../../../mixins/deletable";
import { round } from "lodash";

export default {
    name: "PlansIndex",
    mixins: [deletable],
    components: { DataTable, PlanForm },
    data() {
        return {
            showDialog: false,
            recordId: null,
            resource: "planes",

            columns: {
                sort_order: { title: "Orden", visible: true },
                is_socio: { title: "Socio", visible: true },
                currency: { title: "Moneda", visible: true },
                invoice_interval: { title: "Intervalo", visible: true }
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
            this.destroy(`/${this.resource}/${id}`).then(() => {
                this.$refs.dataTable.getRecords();
            });
        },
        closeDialog() {
            this.showDialog = false;
            this.recordId = null;
            this.$refs.dataTable.getRecords();
        },
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "planes_index",
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
.plans-admin .btn-custom i {
    margin-right: 5px;
}
.plans-admin .btn-custom {
    background-color: #409eff;
    color: #fff;
}
.plans-admin .btn-custom:hover {
    background-color: #2889e9;
}
</style>
