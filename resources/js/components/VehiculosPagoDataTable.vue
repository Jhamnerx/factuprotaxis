<template>
    <div v-loading="loading_submit">
        <div class="row ">
            <div class="col-lg-12 filter-container">
                <div class="btn-filter-content">
                    <el-button
                        type="primary"
                        class="btn-show-filter mb-2"
                        :class="{ shift: isVisible }"
                        @click="toggleInformation"
                    >
                        {{ isVisible ? "Ocultar filtros" : "Mostrar filtros" }}
                    </el-button>
                </div>
                <div class="row filter-content" v-if="applyFilter && isVisible">
                    <div class="col-lg-6 col-md-6 col-sm-12 pb-2">
                        <div class="d-flex">
                            <div style="width:100px">
                                Filtrar por:
                            </div>
                            <el-select
                                v-model="search.column"
                                placeholder="Select"
                                @change="changeClearInput"
                            >
                                <el-option
                                    v-for="(label, key) in columns"
                                    :key="key"
                                    :value="key"
                                    :label="label"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 pb-2">
                        <template>
                            <el-input
                                placeholder="Buscar"
                                v-model="search.value"
                                style="width: 100%;"
                                prefix-icon="el-icon-search"
                                @input="getRecords"
                            >
                            </el-input>
                        </template>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive table-responsive-new">
                    <slot
                        v-for="(row, index) in records"
                        :row="row"
                        :index="customIndex(index)"
                    ></slot>

                    <div>
                        <el-pagination
                            @current-change="getRecords"
                            layout="total, prev, pager, next"
                            :total="pagination.total"
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                        >
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style></style>
<script>
import queryString from "query-string";

export default {
    props: {
        resource: String,
        applyFilter: {
            type: Boolean,
            default: true,
            required: false
        },
        sortField: {
            type: String,
            default: "id"
        },
        sortDirection: {
            type: String,
            default: "desc"
        }
    },
    data() {
        return {
            search: {
                column: null,
                value: null,
                list_value: "all"
            },
            columns: [],
            records: [],
            pagination: {},
            isVisible: false,
            loading_submit: false,
            list_columns: {
                all: "Todos",
                visible: "Visibles",
                hidden: "Ocultos"
            },
            currentSort: {
                field: this.sortField,
                direction: this.sortDirection
            }
        };
    },
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getRecords();
        });
        this.$root.$refs.DataTable = this;
    },
    async mounted() {
        let column_resource = _.split(this.resource, "/");
        await this.$http
            .get(`/${_.head(column_resource)}/vehiculos/columns`)
            .then(response => {
                this.columns = response.data;
                this.search.column = _.head(Object.keys(this.columns));
            });
        await this.getRecords();
    },
    methods: {
        toggleInformation() {
            this.isVisible = !this.isVisible;
        },
        customIndex(index) {
            return (
                this.pagination.per_page * (this.pagination.current_page - 1) +
                index +
                1
            );
        },
        getRecords() {
            this.loading_submit = true;
            return this.$http
                .get(
                    `/${
                        this.resource
                    }/vehiculos/records?${this.getQueryParameters()}`
                )
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch(error => {})
                .then(() => {
                    this.loading_submit = false;
                });
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                sort_field: this.currentSort.field,
                sort_direction: this.currentSort.direction,
                ...this.search
            });
        },
        changeClearInput() {
            this.search.value = "";
            this.getRecords();
        },
        getSearch() {
            return this.search;
        },
        handleSort(field) {
            if (this.currentSort.field === field) {
                if (this.currentSort.direction === "asc") {
                    this.currentSort.direction = "desc";
                } else if (
                    this.currentSort.direction === "desc" &&
                    field === "description"
                ) {
                    this.currentSort.field = "id";
                    this.currentSort.direction = "desc";
                } else {
                    this.currentSort.direction = "asc";
                }
            } else {
                this.currentSort.field = field;
                this.currentSort.direction = "asc";
            }

            this.$emit("sort-change", this.currentSort);
            this.getRecords();
        }
    },
    watch: {
        sortField(newVal) {
            this.currentSort.field = newVal;
        },
        sortDirection(newVal) {
            this.currentSort.direction = newVal;
        }
    }
};
</script>
