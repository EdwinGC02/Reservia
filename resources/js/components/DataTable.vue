<script>
import { nextTick } from 'vue'

export default {
    name: 'DataTableComponent',

    props: {
        id: {
            type: String,
            default: 'datatable'
        },
        headers: {
            type: Array,
            required: true
        },
        items: {
            type: Array,
            required: true
        },
        pageLength: {
            type: Number,
            default: 10
        },
        orderBy: {
            type: String,
            default: null
        }
    },

    mounted() {
        let orderIndex = 0

        if (this.orderBy) {
            const index = this.headers.findIndex(h => h.key === this.orderBy)
            orderIndex = index >= 0 ? index : 0
        }

        this.initDataTable(this.id, this.pageLength, orderIndex)
    },

    watch: {
        items: {
            deep: true,
            handler() {
                this.reInitDataTable()
            }
        },
        orderBy() {
            this.reInitDataTable()
        }
    },

    methods: {
        initDataTable(id, pageLength = 10, orderIndex = 0) {
            if ($.fn.dataTable.isDataTable(`#${id}`)) {
                let table = $(`#${id}`).DataTable()
                table.destroy()
            }

            nextTick(() => {
                $(`#${id}`).DataTable({
                    searching: false,
                    destroy: true,
                    pageLength: pageLength,
                    bLengthChange: false,
                    bInfo: true,
                    bFilter: true,
                    order: [[orderIndex, 'asc']],
                    language: {
                        decimal: "",
                        emptyTable: "No hay información",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                        infoFiltered: "(Filtrado de _MAX_ total de entradas)",
                        thousands: ",",
                        lengthMenu: "Mostrar _MENU_ entradas",
                        loadingRecords: "Cargando...",
                        processing: "Procesando...",
                        search: "Buscar:",
                        zeroRecords: "Sin resultados encontrados",
                        paginate: {
                            first: '<i class="ri-arrow-left-s-line"></i>',
                            last: '<i class="ri-arrow-right-s-left"></i>',
                            next: '<i class="ri-arrow-right-s-line"></i>',
                            previous: '<i class="ri-arrow-left-s-line"></i>',
                        },
                    },
                })
            })
        },

        reInitDataTable() {
            let orderIndex = 0

            if (this.orderBy) {
                const index = this.headers.findIndex(h => h.key === this.orderBy)
                orderIndex = index >= 0 ? index : 0
            }

            this.initDataTable(this.id, this.pageLength, orderIndex)
        }
    }
}
</script>

<template>
    <div>
        <div class="table-responsive table-card mb-1">
            <table class="table align-middle" :id="id">
                <thead class="table-light text-muted">
                    <tr>
                        <th
                            v-for="(header, index) in headers"
                            :key="index"
                            class="sort"
                            :data-sort="header.key"
                        >
                            {{ header.label }}
                        </th>
                    </tr>
                </thead>

                <tbody class="list form-check-all">
                    <tr v-for="(item, i) in items" :key="i">
                        <td v-for="(header, index) in headers" :key="index">
                            <slot :name="`cell-${header.key}`" :item="item">
                                {{ item[header.key] }}
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style scoped>
    table.dataTable {
        margin-top: 0px !important;
    }
</style>
