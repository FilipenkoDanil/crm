<script>
export default {
    name: "CashboxPage",
    data() {
        return {
            products: [],
            selectedProducts: [],
            searchText: '',
        }
    },

    methods: {
        getProducts() {
            axios.get('/api/v1/products')
                .then(r => {
                    this.products = r.data.data
                })
        },

        addToCart(product) {
            this.selectedProducts.push({product, quantity: 1});
            this.searchText = ''
        },

        removeFromCart(product) {
            const existingProduct = this.selectedProducts.find(p => p.product === product);
            const index = this.selectedProducts.indexOf(existingProduct);
            this.selectedProducts.splice(index, 1);
        },

        isInCart(product) {
            return this.selectedProducts.some((p) => p.product === product);
        },

        calculateTotal() {
            return this.selectedProducts.reduce((total, {
                product,
                quantity
            }) => total + product.selling_price * quantity, 0).toFixed(2);
        },
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
    }

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
            <v-container class="border border-opacity-25">
                <v-row>
                    <v-col cols="12" sm="4" md="4" lg="3" v-for="product in filteredProducts" :key="product.name">
                        <v-card
                            class="mx-auto my-2"
                            max-width="374"
                        >

                            <v-img
                                aspect-ratio="1/1"
                                height="200"
                                cover
                                :src="product.image ? product.image : 'https://picsum.photos/200'"
                            ></v-img>

                            <v-card-title>{{ product.title }}</v-card-title>

                            <v-card-text class="text-h6 text-medium-emphasis">${{ product.selling_price }}</v-card-text>

                            <v-card-actions>
                                <v-btn @click="addToCart(product)"
                                       :disabled="isInCart(product)" block append-icon="mdi-cart">Add to cart
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
                <h2 v-if="filteredProducts.length === 0" class="pt-6 text-center">Not found</h2>
            </v-container>
        </v-col>

        <v-col cols="12" lg="4">
            <v-container class="border border-opacity-25">
                <v-card>
                    <v-card-title class="text-center text-h4 mt-3">Cart</v-card-title>
                    <v-divider></v-divider>
                    <v-card-text v-if="selectedProducts.length > 0">
                        <v-list>
                            <v-list-item v-for="cartItem in selectedProducts" :key="cartItem.product.title">
                                <v-list-item-title>{{ cartItem.product.title }}</v-list-item-title>
                                <v-list-item-action>
                                    <v-spacer></v-spacer>
                                    <v-responsive max-width="110">
                                        <v-text-field class="pt-5 mr-2" density="compact" disabled>
                                            ${{ cartItem.product.selling_price }}
                                        </v-text-field>
                                    </v-responsive>
                                    <v-responsive max-width="100">
                                        <v-text-field class="pt-5 mr-2" v-model="cartItem.quantity" label="Quantity"
                                                      type="number" min="1" density="compact"></v-text-field>
                                    </v-responsive>
                                    <v-btn @click="removeFromCart(cartItem.product)" color="red" icon="mdi-close"
                                           size="small"></v-btn>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                        <v-divider class="mb-5"></v-divider>
                        <span class="text-subtitle-1">Total: ${{ calculateTotal() }}</span>
                    </v-card-text>

                    <v-card-text v-else>
                        <h2 class="text-center my-5">Empty</h2>
                    </v-card-text>

                    <v-card-text v-if="selectedProducts.length > 0">
                        <v-btn class="mb-2" variant="text" block color="green">Checkout</v-btn>
                        <v-btn variant="text" block color="red">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>
    </v-row>
</template>


<style scoped>

</style>
