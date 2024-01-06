<script>
export default {
    name: "WarehouseProductDrawer",

    props: {
        editDrawer: Boolean,
        selectedProduct: Object,
        errors: Object,
    },

    methods: {
        editDrawerClose() {
            this.$emit('editDrawerClose')
        },
        updateProduct() {
            this.$emit('updateProduct')
        }
    }
}
</script>

<template>
    <v-navigation-drawer v-bind="editDrawer" temporary rail rail-width="450" location="right">
        <v-card flat>
            <v-card-title class="text-center mb-3">Editing</v-card-title>
            <v-card-text>
                <v-text-field variant="outlined" type="number" label="Stock" placeholder="150"
                              :error-messages="errors.stock"
                              v-model="selectedProduct.stock" density="compact"></v-text-field>
                <v-text-field variant="outlined" type="number" label="Min stock notify" placeholder="0"
                              :error-messages="errors.min_stock_notify"
                              v-model="selectedProduct.min_stock_notify"
                              density="compact"></v-text-field>
            </v-card-text>

            <v-card-actions class="px-4">
                <v-btn @click="editDrawerClose" color="warning" variant="outlined">Cancel</v-btn>
                <v-btn v-if="can('edit productWarehouses')" @click="updateProduct" color="success" variant="outlined">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>

</template>

<style scoped>

</style>
