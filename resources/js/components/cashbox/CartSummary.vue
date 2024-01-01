<script>
export default {
    name: "CartSummary",

    props: {
        selectedProducts: Array,
        dialog: Boolean,
        total: String|Number,
    },

    methods: {
        removeFromCart(product) {
            this.$emit('removeFromCart', product)
        },

        calculateTotal() {
            this.$emit('calculateTotal')
        },

        clearCart() {
            this.$emit('clearCart');
        },

        checkout() {
            this.$emit('checkout')
        }
    }
}
</script>

<template>
    <v-container class="border border-opacity-25 rounded">
        <v-card>
            <v-card-title class="text-center text-h4 mt-3">Cart <v-icon class="text-h6" size="xsmall">mdi-cart</v-icon></v-card-title>
            <v-divider></v-divider>
            <v-card-text v-if="selectedProducts.length > 0">
                <v-list>
                    <v-list-item v-for="cartItem in selectedProducts" :key="cartItem.product.title">
                        <v-list-item-title>{{ cartItem.product.title }}</v-list-item-title>
                        <v-list-item-action>
                            <v-spacer></v-spacer>
                            <v-responsive max-width="110">
                                <v-text-field class="pt-5 mr-2" density="compact" disabled>
                                    {{ cartItem.product.selling_price }}
                                </v-text-field>
                            </v-responsive>
                            <v-responsive max-width="100">
                                <v-text-field class="pt-5 mr-2" v-model="cartItem.quantity" @input="calculateTotal" label="Quantity" type="number" min="1" density="compact"></v-text-field>
                            </v-responsive>
                            <v-btn @click="removeFromCart(cartItem.product)" color="red" icon="mdi-close" size="small"></v-btn>
                        </v-list-item-action>
                    </v-list-item>
                </v-list>
                <v-divider class="mb-5"></v-divider>
                <span class="text-subtitle-1">Total: {{ total }}</span>
            </v-card-text>

            <v-card-text v-else>
                <h2 class="text-center my-5">Empty</h2>
            </v-card-text>

            <v-card-text v-if="selectedProducts.length > 0">
                <v-btn @click="checkout" class="mb-2" variant="text" block color="green">Checkout</v-btn>
                <v-btn @click="clearCart" variant="text" block color="red">Cancel</v-btn>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<style scoped>

</style>
