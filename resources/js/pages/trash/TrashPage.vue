<script>
import MySnackBar from "@/components/MySnackBar.vue";
import MyTrashTable from "@/components/trash/MyTrashTable.vue";

export default {
    name: "TrashPage",
    components: {MyTrashTable, MySnackBar},

    data() {
        return {
            tab: 'tab-1',

            deletedProducts: [],
            deletedSuppliers: [],
            deletedClients: [],
            deletedWarehouses: [],

            snackbar: false,
            snackbarText: '',
        }
    },

    methods: {
        getDeletedProducts() {
            axios.get('/api/v1/trash/products')
                .then(r => this.deletedProducts = r.data.data.map(item => ({
                    ...item,
                    actions: ''
                })))
        },

        getDeletedSuppliers() {
            axios.get('/api/v1/trash/suppliers')
                .then(r => this.deletedSuppliers = r.data.data.map(item => ({
                    ...item,
                    actions: ''
                })))
        },

        getDeletedClients() {
            axios.get('/api/v1/trash/clients')
                .then(r => this.deletedClients = r.data.data.map(item => ({
                    ...item,
                    actions: ''
                })))
        },

        getDeletedWarehouses() {
            axios.get('/api/v1/trash/warehouses')
                .then(r => this.deletedWarehouses = r.data.data.map(item => ({
                    ...item,
                    actions: ''
                })))
        },

        restoreProduct(item) {
            axios.post(`/api/v1/trash/products/${item.id}/restore`)
                .then(r => {
                    this.getDeletedProducts()
                    this.showSnackbar(r.data.message)
                })
        },

        restoreSupplier(item) {
            axios.post(`/api/v1/trash/suppliers/${item.id}/restore`)
                .then(r => {
                    this.getDeletedSuppliers()
                    this.showSnackbar(r.data.message)
                })
        },

        restoreClient(item) {
            axios.post(`/api/v1/trash/clients/${item.id}/restore`)
                .then(r => {
                    this.getDeletedClients()
                    this.showSnackbar(r.data.message)
                })
        },

        restoreWarehouse(item) {
            axios.post(`/api/v1/trash/warehouses/${item.id}/restore`)
                .then(r => {
                    this.getDeletedWarehouses()
                    this.showSnackbar(r.data.message)
                })
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getDeletedProducts()
        this.getDeletedSuppliers()
        this.getDeletedClients()
        this.getDeletedWarehouses()
    }
}
</script>

<template>
    <v-tabs
        v-model="tab"
        stacked
        align-tabs="center"
        bg-color="surface"
        mobile-breakpoint="lg"
    >
        <v-tab value="tab-1">
            <v-icon>mdi-package-variant</v-icon>
            Products
        </v-tab>

        <v-tab value="tab-2">
            <v-icon>mdi-account-multiple-outline</v-icon>
            Suppliers
        </v-tab>

        <v-tab value="tab-3">
            <v-icon>mdi-account-group-outline</v-icon>
            Clients
        </v-tab>

        <v-tab value="tab-4">
            <v-icon>mdi-warehouse</v-icon>
            Warehouses
        </v-tab>
    </v-tabs>

    <v-window v-model="tab" disabled>
        <v-window-item value="tab-1">
            <my-trash-table :items="deletedProducts" :restoreItem="restoreProduct"></my-trash-table>
        </v-window-item>

        <v-window-item value="tab-2">
            <my-trash-table :items="deletedSuppliers" :restoreItem="restoreSupplier"></my-trash-table>
        </v-window-item>

        <v-window-item value="tab-3">
            <my-trash-table :items="deletedClients" :restoreItem="restoreClient"></my-trash-table>
        </v-window-item>

        <v-window-item value="tab-4">
            <my-trash-table :items="deletedWarehouses" :restoreItem="restoreWarehouse"></my-trash-table>
        </v-window-item>
    </v-window>

    <my-snack-bar v-model="snackbar"
                  :snackbarText="snackbarText"
                  @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>

</template>

<style scoped>

</style>
