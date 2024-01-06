<script>
export default {
    name: "MyTrashTable",

    props: {
        items: {
            type: Array,
            required: true,
        },

        restoreItem: {
            type: Function,
            required: true,
        },
    }
}
</script>

<template>
    <v-data-table :items="items" hover>
        <template v-slot:item.actions="{ item }">
            <v-tooltip text="Restore item">
                <template v-if="can('restore trash')" v-slot:activator="{ props }">
                    <v-btn v-bind="props" icon @click="restoreItem(item)" flat="">
                        <v-icon color="green">mdi-delete-restore</v-icon>
                    </v-btn>
                </template>
            </v-tooltip>
        </template>
    </v-data-table>
</template>

<style scoped>

</style>
