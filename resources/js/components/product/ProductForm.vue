<script>
export default {
    name: "ProductForm",

    props: {
        creatableProduct: Object,
        categories: Array,
        errors: Object,
    },

    methods: {
        createProduct() {
            this.$emit("createProduct");
        },
    },
}
</script>

<template>
    <v-card>
        <v-card-title>
            <span class="text-h5">Create product</span>
        </v-card-title>
        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field
                            v-model="creatableProduct.title"
                            :error-messages="errors.title"
                            label="Title*"
                            placeholder="Cougar Minos X3"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field
                            v-model="creatableProduct.barcode"
                            label="Barcode"
                            placeholder="4826291486446242"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field
                            v-model="creatableProduct.code"
                            label="Code"
                            placeholder="MX-140"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-autocomplete
                            item-title="title"
                            item-value="id"
                            v-model="creatableProduct.category_id"
                            :error-messages="errors.category_id"
                            :items="categories"
                            label="Category*"
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-file-input v-model="creatableProduct.image" label="Upload file"></v-file-input>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-text-field
                            v-model="creatableProduct.purchase_price"
                            variant="outlined"
                            label="Purchase price"
                            placeholder="999.99"
                            prepend-inner-icon="mdi-cash"
                            step="0.01"
                            density="compact"
                            type="number"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-text-field
                            v-model="creatableProduct.selling_price"
                            variant="outlined"
                            label="Selling price"
                            placeholder="1199.99"
                            prepend-inner-icon="mdi-cash"
                            step="0.01"
                            density="compact"
                            type="number"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-container>
            <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="blue-darken-1"
                variant="text"
                block
                @click="createProduct"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
