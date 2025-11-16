<script>
import { usePage } from '@inertiajs/vue3'
// import Layout from "@/Layouts/main.vue"
// import Layout from "@/Layouts/maindashboard.vue"
// import Layout from "@/Layouts/layout-sidebar.vue"
import PageHeader from "@/Components/header/header.vue"
import DataTable from '@/Components/DataTable.vue'
import { useFetchPetition } from '@/Composables/useFetchPetition.js';
import { useAlert } from '@/Composables/useSweetAlert.js';

const { showAlert, showConfirm } = useAlert();
const { fetchPetition } = useFetchPetition();

export default {
    name: 'ProductsIndex',
    components: {
        // Layout,
        PageHeader,
        DataTable,
    },
    data() {
        return {
            searchQuery: '',
            products: [],
        }
    },
    // props: {
    //     products: {
    //         type: Object,
    //         required: true,
    //         default: () => ({ data: [], meta: { total: 0 } })
    //     }
    // },
    computed: {
        // filteredProducts() {
        //     const productsList = this.products.data || [];
        //     const query = this.searchQuery.trim().toLowerCase();
        //     if (!query) {
        //         return productsList; // si está vacío, devuelve todo
        //     }
        //     return productsList.filter(yard =>
        //         yard.code?.toLowerCase().includes(query) ||
        //         yard.name?.toLowerCase().includes(query) ||
        //         yard.description?.toLowerCase().includes(query)
        //     );
        // },
        tableHeaders() {
            return [
                { label: 'Código', key: 'code' },
                { label: 'Nombre', key: 'name' },
                { label: 'Estado', key: 'status' },
                { label: 'Acciones', key: 'acciones' },
            ]
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.ListarProductos();
        });
    },
    methods: {
        getStatusClass(status) {
            const classes = {
                1: 'badge bg-success-subtle text-success',
                0: 'badge bg-danger-subtle text-danger'
            };
            return classes[status] || 'bg-secondary-subtle';
        },

        getStatusText(status) {
            const texts = {
                1: 'Activo',
                0: 'Inactivo'
            };
            return texts[status] || 'Desconocido';
        },

        async ListarProductos() {
            const response = await fetchPetition(`/listarProductos`, {
                method: 'GET'
            });
            if (response.ok) {
                    console.log('Productos listados:', response);
                    this.products = response.data?.data;
                } else {
                    showAlert('error', 'Error', 'No se pudieron listar los productos', 2000);
                }
        },

        async deleteYard(id) {
            try {
                const confirmed = await showConfirm(
                    'warning',
                    '¡Alerta!',
                    '¿Está seguro que desea eliminar el patio?',
                    'Sí, eliminar'
                );
                if (!confirmed) return;
                const response = await fetchPetition(`/api/products/${id}`, {
                    method: 'DELETE'
                });
                if (response.ok) {
                    showAlert('success', '¡Éxito!', 'El patio ha sido eliminado correctamente', 1500);
                    window.location.reload(); // o router.visit('/patios') si usas Inertia
                } else {
                    console.warn('Error al eliminar patio:', response.status, response.data);
                    showAlert('error', 'Error', 'Error al eliminar el patio', 2000);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Error inesperado', 'Ocurrió un problema al eliminar el patio', 2000);
            }
        },
    }
}
</script>

<template>
    <Layout>
        <PageHeader title="Gestión de Productos" pageTitle="Maestras" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom-dashed">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">Productos</h5>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex flex-wrap align-items-start gap-2">
                                    <!-- <a :href="route('products.create')" class="btn btn-success add-btn"> -->
                                    <a href="products.create" class="btn btn-success add-btn">
                                        <i class="ri-add-line align-bottom me-1"></i> Nuevo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-bottom-dashed border-bottom">
                        <div class="row g-3">
                            <div class="col-xl-12">
                                <div class="">
                                    <input type="text" class="form-control" placeholder="Buscar..." v-model="searchQuery">
                                    <!-- <i class="ri-search-line search-icon"></i> -->
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <div class="card-body">
                        <div>
                            <!-- Tabla reutilizable -->
                            <DataTable
                                id="tabla-products"
                                :headers="tableHeaders"
                                :items="products"
                                :page-length="20"
                                order-by="code"
                            >
                                <!-- Custom cell: status -->
                                <!-- <template #cell-status="{ item }">
                                    <span class="badge" :class="getStatusClass(item.status)">
                                        {{ getStatusText(item.status) }}
                                    </span>
                                </template> -->

                                <!-- Custom cell: acciones -->
                                <!-- <template #cell-acciones="{ item }">
                                    <ul class="list-inline hstack gap-2 mb-0">
                                        <li class="list-inline-item">
                                            <a :href="`/products/${item.id}/show`" class="text-primary" title="Ver" @click="showYard(item.id)">
                                                <i class="ri-eye-fill fs-16"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item edit">
                                            <a :href="`/products/${item.id}/edit`" class="text-primary" title="Editar">
                                                <i class="ri-pencil-fill fs-16"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0);" class="text-danger" title="Eliminar" @click="deleteYard(item.id)">
                                                <i class="ri-delete-bin-5-fill fs-16"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </template> -->
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
    .search-box {
        position: relative;
    }

    .search-icon {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        color: #74788d;
    }

    .fs-15 {
        font-size: 0.9375rem;
    }
</style>

