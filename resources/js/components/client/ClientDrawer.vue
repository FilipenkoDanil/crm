<script>
export default {
    name: "ClientDrawer",

    props: {
        editDrawer: Boolean,
        editableClient: Object,
        errors: Object,
        disableInputs: Boolean
    },

    methods: {
        editDrawerClose() {
            this.$emit('editDrawerClose');
        },
        updateClient() {
            this.$emit('updateClient');
        },
        deleteClient() {
            this.$emit('deleteClient');
        }
    }
}
</script>

<template>
    <v-navigation-drawer v-bind="editDrawer" temporary rail rail-width="450" location="right">
        <v-card flat>
            <v-card-title class="text-center mb-3">Editing a client #{{ editableClient.id }}</v-card-title>
            <v-card-text>
                <v-text-field variant="outlined" label="Name" placeholder="Huano Pedro"
                              :disabled="disableInputs"
                              :error-messages="errors.name"
                              v-model="editableClient.name" density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Phone" placeholder="380981234567"
                              :disabled="disableInputs"
                              v-model="editableClient.phone"
                              density="compact"></v-text-field>
            </v-card-text>

            <v-card-actions class="px-4">
                <v-btn @click="editDrawerClose" color="warning" variant="outlined">Cancel</v-btn>
                <v-btn v-if="can('edit clients')" @click="updateClient" color="success" variant="outlined" :disabled="disableInputs">Save</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-if="can('delete clients')" @click="deleteClient" color="error" variant="outlined" :disabled="disableInputs">Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
