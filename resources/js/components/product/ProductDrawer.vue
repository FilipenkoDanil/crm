<script>
export default {
    name: "ProductDrawer",

    props: {
        editDrawer: Boolean,
        editableProduct: Object,
        categories: Array,
        errors: Object
    },

    methods: {
        editDrawerClose() {
            this.$emit('editDrawerClose');
        },
        deleteImage() {
            this.$emit('deleteImage');
        },
        updateProduct() {
            this.$emit('updateProduct');
        },
        deleteProduct() {
            this.$emit('deleteProduct');
        }
    }
}
</script>

<template>
    <v-navigation-drawer v-bind="editDrawer" temporary="" rail rail-width="450" location="right">
        <v-card flat>
            <v-card-title class="text-center mb-3">Editing a product #{{ editableProduct.id }}</v-card-title>
            <v-card-text>
                <v-text-field variant="outlined" label="Title" placeholder="Cougar Minos X3"
                              :error-messages="errors.title"
                              v-model="editableProduct.title" density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Barcode" placeholder="4826291486446242"
                              v-model="editableProduct.barcode"
                              density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Code" placeholder="MX-140"
                              v-model="editableProduct.code"
                              density="compact"></v-text-field>
                <div class="d-flex">
                    <v-text-field variant="outlined" label="Purchase price" placeholder="999.99"
                                  v-model="editableProduct.purchase_price"
                                  prepend-inner-icon="mdi-cash"
                                  step="0.01"
                                  density="compact" type="number" class="w-25"></v-text-field>
                    <v-spacer></v-spacer>
                    <v-text-field variant="outlined" label="Selling price" placeholder="1199.99"
                                  v-model="editableProduct.selling_price"
                                  prepend-inner-icon="mdi-cash"
                                  step="0.01"
                                  density="compact" type="number" class="w-25"></v-text-field>
                </div>

                <v-autocomplete v-model="editableProduct.category_id" item-title="title" item-value="id"
                                :items="categories" variant="outlined" label="Category"></v-autocomplete>

                <v-img :src="editableProduct.image" class="align-center text-center rounded-lg"
                       v-if="editableProduct.image">
                    <v-tooltip text="Delete">
                        <template v-slot:activator="{ props }">
                            <v-btn v-bind="props" color="red"
                                   @click="deleteImage"
                                   class="border border-secondary"
                                   icon size="large">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </template>
                    </v-tooltip>
                </v-img>

                <v-file-input v-model="editableProduct.uploadImage" accept="image/png, image/jpeg" show-size
                              :error-messages="errors.image"
                              label="Upload image" class="mt-4"></v-file-input>
            </v-card-text>

            <v-card-actions class="px-4">
                <v-btn @click="editDrawerClose" color="warning" variant="outlined">Cancel</v-btn>
                <v-btn @click="updateProduct" color="success" variant="outlined">Save</v-btn>
                <v-spacer></v-spacer>
                <v-btn @click="deleteProduct" color="error" variant="outlined">Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
