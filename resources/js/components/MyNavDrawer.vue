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

            <v-list-item link rounded="e-xl" prepend-icon="mdi-note-outline" :to="{name: 'products.index'}">
                <v-list-item-title>Products</v-list-item-title>
            </v-list-item>

            <v-list-item link rounded="e-xl" prepend-icon="mdi-file-move-outline">
                <v-list-item-title>Sales</v-list-item-title>
            </v-list-item>

            <div class="d-flex text-subtitle-2 text-grey-darken-1 align-center">
                <v-divider></v-divider>
                <span class="ml-1 mr-3 text-uppercase">Title</span>
                <v-divider></v-divider>
            </div>

        </v-list>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
