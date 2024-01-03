<script>
export default {
    name: "WarehouseDrawer",

    props: {
        editDrawer: Boolean,
        editableWarehouse: Object,
        errors: Object,
    },

    methods: {
        editDrawerClose() {
            this.$emit('editDrawerClose')
        },
        updateWarehouse() {
            this.$emit('updateWarehouse')
        },
        deleteWarehouse() {
            this.$emit('deleteWarehouse')
        }
    }
}
</script>

<template>
    <v-navigation-drawer v-bind="editDrawer" temporary rail rail-width="450" location="right">
        <v-card flat>
            <v-card-title class="text-center mb-3">Editing a warehouse #{{ editableWarehouse.id }}</v-card-title>
            <v-card-text>
                <v-text-field variant="outlined" label="Title" placeholder="Aut saepe sed."
                              :error-messages="errors.title"
                              v-model="editableWarehouse.title" density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Address" placeholder="9679 Kurt Landing Apt. 821East Minaville, WA 49706"
                              v-model="editableWarehouse.address"
                              density="compact"></v-text-field>
            </v-card-text>

            <v-card-actions class="px-4">
                <v-btn @click="editDrawerClose" color="warning" variant="outlined">Cancel</v-btn>
                <v-btn v-if="can('edit warehouses')" @click="updateWarehouse" color="success" variant="outlined">Save</v-btn>
                <v-btn v-if="can('show warehouses')" color="success" variant="outlined" :to="{name: 'warehouses.show', params: {id: this.editableWarehouse.id || 1}}">Go to</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-if="can('delete warehouses')" @click="deleteWarehouse" color="error" variant="outlined">Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
