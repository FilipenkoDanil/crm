<script>
import MySnackBar from "@/components/MySnackBar.vue";
import CashProductsList from "@/components/cashbox/CashProductsList.vue";
import CartSummary from "@/components/cashbox/CartSummary.vue";

export default {
    name: "CashboxPage",
    components: {CartSummary, CashProductsList, MySnackBar},
    data() {
        return {
            products: [],
            selectedProducts: [],
            clients: [],
            warehouses: [],

            searchText: '',

            dialog: false,
            received: null,
            change: 0,
            total: 0,

            errors: {},

            selectedClient: 1,
            selectedWarehouse: null,

            snackbar: false,
            snackbarText: '',

            payment_id: 1,
            payments: {}
        }
    },

    methods: {
        getProducts() {
            axios.get('/api/v1/products')
                .then(r => {
                    this.products = r.data.data
                })
        },

        getClients() {
            axios.get('/api/v1/clients')
                .then(r => {
                    this.clients = r.data
                })
        },

        getWarehouses() {
            axios.get('/api/v1/warehouses')
                .then(r => {
                    this.warehouses = r.data
                })
        },

        getPayments() {
            axios.get('/api/v1/payments')
                .then(r => {
                    this.payments = r.data
                })
        },

        createSale() {
            const requestData = {
                client_id: this.selectedClient,
                warehouse_id: this.selectedWarehouse,
                payment_id: this.payment_id,
                data: this.selectedProducts.map(item => ({
                    id: item.product.id,
                    quantity: item.quantity,
                    selling_price: parseFloat(item.product.selling_price)
                })),
            }

            axios.post('/api/v1/sales', requestData)
                .then(response => {
                    if (response.data.payment === 'card' && response.data.system === 'LiqPay') {
                        const form = document.createElement('form');
                        form.setAttribute('method', 'post');
                        form.setAttribute('action', 'https://www.liqpay.ua/api/3/checkout');
                        form.setAttribute('accept-charset', 'utf-8');
                        form.setAttribute('target', '_blank');

                        Object.entries(response.data).forEach(([key, value]) => {
                            if (key !== 'productName' && key !== 'productPrice' && key !== 'productCount' && key !== 'system' && key !== 'payment') {
                                form.appendChild(this.createHiddenInput(key, value));
                            }
                        });

                        document.body.appendChild(form);
                        form.submit();
                        document.body.removeChild(form);

                    } else if (response.data.payment === 'card' && response.data.system === 'WayForPay') {
                        const responseData = response.data[0];

                        const form = document.createElement('form');
                        form.setAttribute('method', 'post');
                        form.setAttribute('action', 'https://secure.wayforpay.com/pay');
                        form.setAttribute('accept-charset', 'utf-8');
                        form.setAttribute('target', '_blank');

                        responseData.productName.forEach((productName, index) => {
                            form.appendChild(this.createHiddenInput('productName[]', productName));
                            form.appendChild(this.createHiddenInput('productPrice[]', responseData.productPrice[index]));
                            form.appendChild(this.createHiddenInput('productCount[]', responseData.productCount[index]));
                        });

                        Object.entries(response.data).forEach(([key, value]) => {
                            if (key !== 'productName' && key !== 'productPrice' && key !== 'productCount' && key !== 'system' && key !== 'payment') {
                                form.appendChild(this.createHiddenInput(key, value));
                            }
                        });

                        document.body.appendChild(form);
                        form.submit();
                        document.body.removeChild(form);
                    }

                    this.selectedProducts = []
                    this.errors = []
                    this.dialog = false
                    this.received = 0
                    this.showSnackbar('Sale created!')
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        createHiddenInput(name, value) {
            const input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', name);
            input.setAttribute('value', value);
            return input;
        },

        addToCart(product) {
            this.selectedProducts.push({product, quantity: 1});
            this.searchText = ''
            this.calculateTotal()
        },

        removeFromCart(product) {
            const existingProduct = this.selectedProducts.find(p => p.product === product);
            const index = this.selectedProducts.indexOf(existingProduct);
            this.selectedProducts.splice(index, 1);
            this.calculateTotal()
        },

        isInCart(product) {
            return this.selectedProducts.some((p) => p.product === product);
        },

        calculateTotal() {
            this.total = this.selectedProducts.reduce((total, {
                product,
                quantity
            }) => total + product.selling_price * quantity, 0).toFixed(2);
        },

        calculateChange() {
            this.change = this.received - this.total;
            this.change = Math.max(0, this.change);
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    computed: {
        filteredProducts() {
            const searchTextLower = this.searchText.toLowerCase().trim();
            return this.products.filter((product) => {
                const titleMatch = (product.title || '').toLowerCase().includes(searchTextLower);
                const barcodeMatch = (product.barcode || '').toLowerCase().includes(searchTextLower);
                const codeMatch = (product.code || '').toLowerCase().includes(searchTextLower);
                return titleMatch || barcodeMatch || codeMatch;
            });
        }
    },

    mounted() {
        this.getProducts()
        this.getClients()
        this.getWarehouses()
        this.getPayments()
    },

    watch: {
        received: 'calculateChange'
    },

};
</script>

<template>
    <v-row>
        <v-col cols="12" lg="8">
            <v-text-field v-model="searchText" placeholder="Product name, barcode, code"
                          prepend-inner-icon="mdi-magnify"></v-text-field>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" lg="8">
            <cash-products-list :filteredProducts="filteredProducts" @addToCart="addToCart"
                                :isInCart="isInCart"></cash-products-list>
        </v-col>

        <v-col cols="12" lg="4">
            <cart-summary
                :selectedProducts="selectedProducts"
                :dialog="dialog"
                :total="total"
                @removeFromCart="removeFromCart"
                @checkout="dialog = !dialog"
                @calculateTotal="calculateTotal"
                @clearCart="selectedProducts = []"
            ></cart-summary>
        </v-col>
    </v-row>

    <v-dialog v-model="dialog" max-width="600">
        <v-card>
            <v-card-title>Checkout</v-card-title>
            <v-card-text>
                <v-select v-model="selectedWarehouse" :items="warehouses" :error-messages="errors.warehouse_id"
                          item-title="title" item-value="id" placeholder="Warehouse"></v-select>
                <v-select v-model="selectedClient" :items="clients" :error-messages="errors.client_id" item-title="name"
                          item-value="id" placeholder="Client"></v-select>
                <v-select v-model="payment_id" :items="payments" item-title="type" item-value="id"></v-select>
            </v-card-text>

            <v-card-text>
                Total amount: {{ total }}
                <v-text-field v-model="received" placeholder="Received" type="number"></v-text-field>
                <v-text-field v-model="change" label="Change" type="number" disabled=""></v-text-field>
            </v-card-text>

            <v-card-actions>
                <v-btn :disabled="!can('create sales')" @click="createSale" block color="teal-darken-2" variant="flat">Complete</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <my-snack-bar v-model="snackbar" :snackbarText="snackbarText" @closeSnack="snackbar = !snackbar"></my-snack-bar>

</template>


<style scoped>

</style>
