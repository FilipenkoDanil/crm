<script>
import MySnackBar from "@/components/MySnackBar.vue";
import WarehouseProductsDrawer from "@/components/warehouse/WarehouseProductsDrawer.vue";

export default {
    name: "WarehouseProductsPage",
    components: {WarehouseProductsDrawer, MySnackBar},

    data() {
        return {
            products: [],

            warehouse: '',

            loading: true,
            headers: [
                {
                    key: 'id',
                    title: '#',
                },
                {
                    key: 'title',
                    title: 'Title'
                },
                {
                    key: 'barcode',
                    title: 'Barcode'
                },
                {
                    key: 'code',
                    title: 'Code'
                },
                {
                    key: 'purchase_price',
                    title: 'Purchase price'
                },
                {
                    key: 'selling_price',
                    title: 'Selling price'
                },
                {
                    key: 'pivot.stock',
                    title: 'In stock'
                },
                {
                    key: 'pivot.min_stock_notify',
                    title: 'Min. stock notify'
                }
            ],
            search: '',

            snackbar: false,
            snackbarText: '',


            editDrawer: false,
            selectedProduct: {
                pivot: {
                    stock: 0,
                    min_stock_notify: 0
                }
            },
            errors: []
        }
    },

    methods: {
        getWarehouse() {
            this.loading = true
            axios.get(`/api/v1/warehouses/${this.$route.params.id}`)
                .then(r => {
                    this.products = r.data.products
                    this.warehouse = r.data.title
                    this.loading = false
                })
        },

        onRowClick(item, row) {
            this.editDrawer = true
            this.selectedProduct = {
                ...row.item.pivot
            };
            this.errors = []
        },

        updateProductStock() {
            axios.post('/api/v1/products-warehouses', {
                _method: 'PATCH',
                warehouse_id: this.selectedProduct.warehouse_id,
                product_id: this.selectedProduct.product_id,
                stock: this.selectedProduct.stock,
                min_stock_notify: this.selectedProduct.min_stock_notify
            })
                .then(() => {
                    this.getWarehouse()
                    this.showSnackbar('Stock updated.')
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        },

        getColor(value, item) {
            return value <= item.pivot.min_stock_notify ? 'red' : 'green'
        }
    },

    mounted() {
        this.getWarehouse()
    }
}
</script>

<template>
    <h2 class="text-h5 mb-3">{{ warehouse }}</h2>

    <v-row class="d-flex justify-space-between">
        <v-col cols="6" sm="6" md="4" lg="3">
            <v-text-field v-model="search" label="Search" placeholder="Product name"
                          density="compact"
                          prepend-inner-icon="mdi-magnify"></v-text-field>
        </v-col>
    </v-row>

    <v-data-table :items="products" @click:row="onRowClick" items-per-page="25"
                  :search="search"
                  :headers="headers"
                  :loading="loading"
                  hover>
        <template v-slot:item.pivot.stock="{ value, item }">
            <v-chip :color="getColor(value, item)">
                {{ value }}
            </v-chip>
        </template>

        <template v-slot:loading>
            <v-skeleton-loader type="table-row@25"></v-skeleton-loader>
        </template>
    </v-data-table>

    <warehouse-products-drawer v-model="editDrawer"
                              :selectedProduct="selectedProduct"
                              :errors="errors"
                              @updateProduct="updateProductStock"
                              @editDrawerClose="editDrawer = !editDrawer"
    ></warehouse-products-drawer>

    <my-snack-bar v-model="snackbar"
                  :snackbar-text="snackbarText"
                  @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>

</template>

<style scoped>

</style>
