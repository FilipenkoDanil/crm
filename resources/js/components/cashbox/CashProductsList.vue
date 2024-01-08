<script>
export default {
    name: "CashProductsList",

    props: {
        filteredProducts: Array,
        isInCart: Function,
    },

    methods: {
        addToCart(product) {
            this.$emit('addToCart', product);
        }
    },
}
</script>

<template>
    <v-container class="border border-opacity-25 rounded">
        <v-row>
            <v-col cols="12" sm="4" md="4" lg="3" v-for="product in filteredProducts" :key="product.name">
                <v-card
                    class="mx-auto my-2"
                    max-width="374"
                >

                    <v-img
                        min-height="300"
                        cover
                        :src="product.image ? product.image : `https://picsum.photos/200?random?${product.id}`"
                    ></v-img>

                    <v-card-title>{{ product.title }}</v-card-title>

                    <v-card-text class="text-h6 text-medium-emphasis">{{ product.selling_price }}</v-card-text>

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
</template>

<style scoped>

</style>
