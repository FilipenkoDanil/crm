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

            formData: {
                merchantAccount: "",
                merchantDomainName: "",
                orderReference: null,
                orderDate: null,
                amount: null,
                currency: "",
                productName: [],
                productPrice: [],
                productCount: [],
                merchantSignature: "",
            },

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
                    if (response.data.payment === 'card') {
                        const responseData = response.data[0];

                        this.formData.productName = responseData.productName;
                        this.formData.productPrice = responseData.productPrice;
                        this.formData.productCount = responseData.productCount;

                        this.formData.merchantAccount = response.data.merchantAccount;
                        this.formData.merchantDomainName = response.data.merchantDomainName;
                        this.formData.orderReference = response.data.orderReference;
                        this.formData.orderDate = response.data.orderDate;
                        this.formData.amount = response.data.amount;
                        this.formData.currency = response.data.currency;
                        this.formData.merchantSignature = response.data.merchantSignature;
                        this.formData.serviceUrl = response.data.serviceUrl;

                        setTimeout(() => {
                            document.getElementById('paymentForm').submit()
                        }, 1000)
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


    <form id="paymentForm" method="post" action="https://secure.wayforpay.com/pay" accept-charset="utf-8"  target="_blank">
        <input type="hidden" name="merchantAccount" :value="formData.merchantAccount">
        <input type="hidden" name="merchantAuthType" value="SimpleSignature">
        <input type="hidden" name="merchantDomainName" :value="formData.merchantDomainName">
        <input type="hidden" name="orderReference" :value="formData.orderReference">
        <input type="hidden" name="orderDate" :value="formData.orderDate">
        <input type="hidden" name="amount" :value="formData.amount">
        <input type="hidden" name="currency" :value="formData.currency">
        <input type="hidden" name="orderTimeout" value="600">
        <input type="hidden" name="serviceUrl" :value="formData.serviceUrl">

        <input v-for="(productName, index) in formData.productName" :key="index" type="hidden" :name="'productName[]'" :value="productName">
        <input v-for="(productPrice, index) in formData.productPrice" :key="index" type="hidden" :name="'productPrice[]'" :value="productPrice">
        <input v-for="(productCount, index) in formData.productCount" :key="index" type="hidden" :name="'productCount[]'" :value="productCount">

        <input type="hidden" name="merchantSignature" :value="formData.merchantSignature">
    </form>
</template>


<style scoped>

</style>
