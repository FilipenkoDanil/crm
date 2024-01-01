<script>
import MySnackBar from "@/components/MySnackBar.vue";

export default {
    name: "PurchaseCreatePage",
    components: {MySnackBar},

    data() {
        return {
            products: [],
            suppliers: [],
            warehouses: [],

            snackbar: false,
            snackbarText: '',
            errors: [],

            selectedProducts: [],
            selectedSupplier: [],
            selectedWarehouse: [],

            headers: [
                {
                    key: 'title',
                    title: 'Title'
                },
                {
                    key: 'quantity',
                    title: 'Quantity'
                },
                {
                    key: 'purchase_price',
                    title: 'Unit price'
                },
                {
                    key: 'sum',
                    title: 'Sum'
                }
            ]
        }
    },

    methods: {
        getProducts() {
            axios.get('/api/v1/products')
                .then(r => {
                    this.products = r.data.data.map(product => ({
                        ...product,
                        quantity: 1,
                        sum: parseFloat(product.purchase_price)
                    }));
                });
        },

        getSuppliers() {
            axios.get('/api/v1/suppliers')
                .then(r => {
                    this.suppliers = r.data.data
                })
        },

        getWarehouses() {
            axios.get('/api/v1/warehouses')
                .then(r => {
                    this.warehouses = r.data
                })
        },

        createPurchase() {
            const requestData = {
                supplier_id: this.selectedSupplier.id,
                warehouse_id: this.selectedWarehouse.id,
                data: this.selectedProducts.map(item => ({
                    id: item.id,
                    quantity: parseFloat(item.quantity),
                    purchase_price: parseFloat(item.purchase_price)
                }))
            };

            axios.post('/api/v1/purchases', requestData)
                .then(() => {
                    this.errors = []
                    this.selectedProducts = []
                    this.selectedWarehouse = []
                    this.selectedSupplier = []
                    this.showSnackbar('Purchase created.')
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                    this.showSnackbar(error.response.data.message)
                })
        },

        updateSum(item) {
            item.sum = (parseFloat(item.quantity) * parseFloat(item.purchase_price)).toFixed(2)
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getProducts()
        this.getSuppliers()
        this.getWarehouses()
    }
}
</script>

<template>
    <v-row>
        <v-col cols="12" lg="3">
            <v-autocomplete v-model="selectedProducts" :items="products"
                            :error-messages="errors.data"
                            placeholder="Select products"
                            chips item-color="red" item-title="title"
                            item-value="id" multiple
                            return-object closable-chips>

                <template v-slot:item="{ props, item }">
                    <v-list-item
                        v-bind="props"
                        :title="item?.title"
                    ></v-list-item>
                </template>
            </v-autocomplete>

            <v-autocomplete v-model="selectedSupplier" :items="suppliers" placeholder="Select supplier"
                            :error-messages="errors.supplier_id"
                            item-title="name" item-value="id" return-object></v-autocomplete>

            <v-autocomplete v-model="selectedWarehouse" :items="warehouses" placeholder="Select warehouse"
                            :error-messages="errors.warehouse_id"
                            item-title="title" item-value="id" return-object></v-autocomplete>
        </v-col>

        <v-col cols="12" lg="9">
            <v-data-table :items="selectedProducts" :headers="headers" items-per-page="-1">

                <template v-slot:item.quantity="{ item }">
                    <v-text-field v-model="item.quantity" @input="updateSum(item)"
                                  type="number" :min="1" :max="1000"
                    ></v-text-field>
                </template>

                <template #bottom></template>
            </v-data-table>

            <div class="mt-3 d-flex justify-space-between">
                <p class="text-h6 text-medium-emphasis">
                    Total sum:
                    <span class="font-weight-black"> {{
                            selectedProducts.reduce((sum, item) => sum + parseFloat(item.sum || 0), 0)
                        }}</span>
                </p>

                <v-btn @click="createPurchase" color="success">Save</v-btn>
            </div>
        </v-col>
    </v-row>

    <my-snack-bar v-model="snackbar"
                  :snackbarText="snackbarText"
                  @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>
</template>

<style scoped>

</style>
