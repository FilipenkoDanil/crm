<script>
import MySnackBar from "@/components/MySnackBar.vue";
import SupplierDrawer from "@/components/supplier/SupplierDrawer.vue";
import SupplierForm from "@/components/supplier/SupplierForm.vue";

export default {
    name: "SuppliersPage",
    components: {SupplierForm, SupplierDrawer, MySnackBar},

    data() {
        return {
            suppliers: [],

            search: '',
            loading: false,

            editDrawer: false,
            editableSupplier: {},
            errors: [],

            snackbar: false,
            snackbarText: '',

            creatableSupplier: {}
        }
    },

    methods: {
        getSuppliers() {
            this.loading = true
            axios.get('/api/v1/suppliers')
                .then(r => {
                    this.suppliers = r.data.data
                    this.loading = false
                })
        },

        onRowClick(item, row) {
            this.editDrawer = true
            this.editableSupplier = {...row.item}
            this.errors = []
        },

        createSupplier() {
            axios.post('/api/v1/suppliers', {
                name: this.creatableSupplier.name,
                phone: this.creatableSupplier.phone,
                company: this.creatableSupplier.company
            })
                .then(() => {
                    this.getSuppliers()
                    this.showSnackbar('Supplier created.')
                    this.creatableSupplier = {}
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                })
        },

        updateSupplier() {
            axios.post(`/api/v1/suppliers/${this.editableSupplier.id}`, {
                _method: 'PATCH',
                name: this.editableSupplier.name,
                phone: this.editableSupplier.phone,
                company: this.editableSupplier.company
            })
                .then(() => {
                    this.getSuppliers()
                    this.showSnackbar('Supplier updated.')
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                })
        },

        deleteSupplier() {
            axios.delete(`/api/v1/suppliers/${this.editableSupplier.id}`)
                .then(() => {
                    this.getSuppliers()
                    this.showSnackbar('Supplier deleted.')
                    this.editDrawer = false
                    this.editableSupplier = {}
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
        this.getSuppliers()

        window.Echo.channel('supplier')
            .listen('.supplier-created', () => {
                this.getSuppliers()
            })
            .listen('.supplier-deleted', () => {
                this.getSuppliers()
            })
    },

    beforeUnmount() {
        window.Echo.leave('supplier')
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
            <v-dialog v-if="can('create suppliers')" width="800" @update:modelValue="this.creatableSupplier = {}, this.errors = []">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" text="Create supplier"></v-btn>
                </template>

                <template v-slot:default>
                    <supplier-form :creatableSupplier="creatableSupplier"
                                   :errors="errors"
                                   @createSupplier="createSupplier"
                    ></supplier-form>
                </template>
            </v-dialog>
        </v-col>
    </v-row>

    <v-data-table :items="suppliers" @click:row="onRowClick" items-per-page="25"
                  :search="search"
                  :loading="loading"
                  hover></v-data-table>

    <supplier-drawer v-model="editDrawer"
                     :editableSupplier="editableSupplier"
                     :errors="errors"
                     @editDrawerClose="editDrawer = !editDrawer"
                     @updateSupplier="updateSupplier"
                     @deleteSupplier="deleteSupplier"
    ></supplier-drawer>

    <my-snack-bar v-model="snackbar"
                  :snackbarText="snackbarText"
                  @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>
</template>

<style scoped>

</style>
