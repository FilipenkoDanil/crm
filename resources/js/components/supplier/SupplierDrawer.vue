<script>
export default {
    name: "SupplierDrawer",

    props: {
        editDrawer: Boolean,
        editableSupplier: Object,
        errors: Object,
    },

    methods: {
        editDrawerClose() {
            this.$emit('editDrawerClose');
        },
        updateSupplier() {
            this.$emit('updateSupplier');
        },
        deleteSupplier() {
            this.$emit('deleteSupplier');
        }
    }
}
</script>

<template>
    <v-navigation-drawer v-bind="editDrawer" temporary rail rail-width="450" location="right">
        <v-card flat>
            <v-card-title class="text-center mb-3">Editing a supplier #{{ editableSupplier.id }}</v-card-title>
            <v-card-text>
                <v-text-field variant="outlined" label="Name" placeholder="Huano Pedro"
                              :error-messages="errors.name"
                              v-model="editableSupplier.name" density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Phone" placeholder="380981234567"
                              v-model="editableSupplier.phone"
                              density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Company" placeholder="O'Reilly-Kutch"
                              v-model="editableSupplier.company"
                              density="compact"></v-text-field>
            </v-card-text>

            <v-card-actions class="px-4">
                <v-btn @click="editDrawerClose" color="warning" variant="outlined">Cancel</v-btn>
                <v-btn @click="updateSupplier" color="success" variant="outlined">Save</v-btn>
                <v-spacer></v-spacer>
                <v-btn @click="deleteSupplier" color="error" variant="outlined">Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
