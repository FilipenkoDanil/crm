<script>
export default {
    name: "MyNavDrawer",
}
</script>

<template>
    <v-navigation-drawer color="background" floating>
        <template v-slot:prepend>
            <span class="d-flex justify-center mt-2 text-h5">CRMshop</span>
        </template>

        <v-list density="compact" color="red-lighten-1" base-color="red-lighten-5" class="pr-2">
            <v-list-item link rounded="e-xl" prepend-icon="mdi-home-outline" :to="{name: 'home'}">
                <v-list-item-title>
                    Home
                </v-list-item-title>
            </v-list-item>

            <v-list-item v-if="can('show products')" link rounded="e-xl" prepend-icon="mdi-package-variant" :to="{name: 'products.index'}">
                <v-list-item-title>Products</v-list-item-title>
            </v-list-item>

            <v-list-item v-if="can('show categories')" link rounded="e-xl" prepend-icon="mdi-ballot-outline" :to="{name: 'categories.index'}">
                <v-list-item-title>Categories</v-list-item-title>
            </v-list-item>


            <v-list-item v-if="can('show warehouses')" link rounded="e-xl" prepend-icon="mdi-warehouse" :to="{name: 'warehouses.index'}">
                <v-list-item-title>Warehouses</v-list-item-title>
            </v-list-item>


            <div v-if="can('show clients | show suppliers')" class="d-flex text-subtitle-2 text-grey-darken-1 align-center">
                <v-divider></v-divider>
                <span class="ml-1 mr-3 text-uppercase">Users</span>
                <v-divider></v-divider>
            </div>

            <v-list-item v-if="can('show clients')" link rounded="e-xl" prepend-icon="mdi-account-group-outline" :to="{name: 'clients.index'}">
                <v-list-item-title>Clients</v-list-item-title>
            </v-list-item>

            <v-list-item v-if="can('show suppliers')" link rounded="e-xl" prepend-icon="mdi-account-multiple-outline" :to="{name: 'suppliers.index'}">
                <v-list-item-title>Suppliers</v-list-item-title>
            </v-list-item>

            <div v-if="can('create purchases | show purchases')" class="d-flex text-subtitle-2 text-grey-darken-1 align-center">
                <v-divider></v-divider>
                <span class="ml-1 mr-3 text-uppercase">Movements</span>
                <v-divider></v-divider>
            </div>

            <v-list-group v-if="can('create purchases | show purchases')">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" link rounded="e-xl" prepend-icon="mdi-file-outline">
                        <v-list-item-title>Purchases</v-list-item-title>
                    </v-list-item>
                </template>

                <v-list-item v-if="can('show purchases')" link rounded="e-xl" prepend-icon="mdi-file-outline" :to="{name: 'purchases.index'}">
                    <v-list-item-title>List</v-list-item-title>
                </v-list-item>

                <v-list-item v-if="can('create purchases')" link rounded="e-xl" prepend-icon="mdi-file-plus-outline" :to="{name: 'purchases.create'}">
                    <v-list-item-title>Create</v-list-item-title>
                </v-list-item>
            </v-list-group>

            <v-list-group v-if="can('show sales')">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" link rounded="e-xl" prepend-icon="mdi-file-move-outline">
                        <v-list-item-title>Sales</v-list-item-title>
                    </v-list-item>
                </template>

                <v-list-item v-if="can('show sales')" link rounded="e-xl" prepend-icon="mdi-file-move-outline" :to="{name: 'sales.index'}">
                    <v-list-item-title>List</v-list-item-title>
                </v-list-item>
            </v-list-group>

            <div v-if="can('show trash')" class="d-flex text-subtitle-2 text-grey-darken-1 align-center">
                <v-divider></v-divider>
                <span class="ml-1 mr-3 text-uppercase">Other</span>
                <v-divider></v-divider>
            </div>

            <v-list-item v-if="can('show trash')" link rounded="e-xl" prepend-icon="mdi-trash-can-outline" :to="{name: 'trash'}">
                <v-list-item-title>Trash</v-list-item-title>
            </v-list-item>


        </v-list>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
