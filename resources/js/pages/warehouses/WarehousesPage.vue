<script>
import MySnackBar from "@/components/MySnackBar.vue";
import WarehouseDrawer from "@/components/warehouse/WarehouseDrawer.vue";
import WarehouseForm from "@/components/warehouse/WarehouseForm.vue";

export default {
    name: "WarehousesPage",
    components: {WarehouseForm, WarehouseDrawer, MySnackBar},

    data() {
        return {
            warehouses: [],

            search: '',
            loading: false,

            editDrawer: false,
            editableWarehouse: {},
            errors: [],

            snackbar: false,
            snackbarText: '',

            creatableWarehouse: {}
        }
    },

    methods: {
        getWarehouses() {
            this.loading = true
            axios.get('/api/v1/warehouses')
                .then(r => {
                    this.warehouses = r.data
                    this.loading = false
                })
        },

        onRowClick(item, row) {
            this.editDrawer = true
            this.editableWarehouse = {...row.item}
            this.errors = []
        },

        createWarehouse() {
            axios.post('/api/v1/warehouses', {
                title: this.creatableWarehouse.title,
                address: this.creatableWarehouse.address
            })
                .then(() => {
                    this.getWarehouses()
                    this.showSnackbar('Warehouse created.')
                    this.creatableWarehouse = {}
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                })
        },

        updateWarehouse() {
            axios.patch(`/api/v1/warehouses/${this.editableWarehouse.id}`, {
                title: this.editableWarehouse.title,
                address: this.editableWarehouse.address
            })
                .then(() => {
                    this.getWarehouses()
                    this.showSnackbar('Warehouse updated.')
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                    this.showSnackbar(error.response.data.message)
                })
        },

        deleteWarehouse() {
            axios.delete(`/api/v1/warehouses/${this.editableWarehouse.id}`)
                .then(r => {
                    this.getWarehouses()
                    this.showSnackbar(r.data.message)
                    this.editDrawer = false
                    this.editableWarehouse = {}
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                    this.showSnackbar(error.response.data.errors)
                })
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getWarehouses()

        window.Echo.channel('warehouse')
            .listen('.warehouse-created', () => {
                this.getWarehouses()
            })
    },

    beforeUnmount() {
        window.Echo.leave('warehouse')
    }
}
</script>

<template>
    <v-row class="d-flex justify-space-between">
        <v-col cols="6" sm="6" md="4" lg="3">
            <v-text-field v-model="search" label="Search" placeholder="Warehouse title"
                          density="compact"
                          prepend-inner-icon="mdi-magnify"></v-text-field>
        </v-col>
        <v-col cols="6" sm="6" md="4" lg="3" class="text-end">
            <v-dialog v-if="can('create warehouses')" width="800" @update:modelValue="this.creatableWarehouse = {}, this.errors = []">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" text="Create warehouse"></v-btn>
                </template>

                <template v-slot:default>
                    <warehouse-form :creatableWarehouse="creatableWarehouse"
                                    :errors="errors"
                                    @createWarehouse="createWarehouse"></warehouse-form>
                </template>
            </v-dialog>
        </v-col>
    </v-row>

    <v-data-table :items="warehouses" @click:row="onRowClick" items-per-page="25"
                  :search="search"
                  :loading="loading"
                  hover></v-data-table>

    <warehouse-drawer v-model="editDrawer"
                      :editableWarehouse="editableWarehouse"
                      :errors="errors"
                      @editDrawerClose="editDrawer = !editDrawer"
                      @updateWarehouse="updateWarehouse"
                      @deleteWarehouse="deleteWarehouse"
    ></warehouse-drawer>

    <my-snack-bar v-model="snackbar"
                  :snackbar-text="snackbarText"
                  @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>
</template>

<style scoped>

</style>
