<script>
import AuthLayout from "@/layouts/AuthLayout.vue";
import MainLayout from "@/layouts/MainLayout.vue";
export default {
    name: "App",

    components: {
        AuthLayout,
        MainLayout
    }
}
</script>

<template>
    <v-app>
        <v-main>
            <component :is="$route.meta.layout">
                <router-view></router-view>
            </component>
        </v-main>
    </v-app>
</template>

<style scoped>

</style>
