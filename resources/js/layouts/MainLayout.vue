<script>
import MyNavDrawer from "@/components/MyNavDrawer.vue";
import MyAppBar from "@/components/MyAppBar.vue";

export default {
    name: "MainLayout",

    components: {MyAppBar, MyNavDrawer},

    data() {
        return {
            drawerValue: true,
        };
    },

    methods: {
        handleButtonClick() {
            this.drawerValue = !this.drawerValue;
        },
    },

    mounted() {
        this.drawerValue = !this.$vuetify.display.mobile
    }
}
</script>

<template>
    <my-nav-drawer v-model="drawerValue"></my-nav-drawer>
    <my-app-bar @buttonClick="handleButtonClick"></my-app-bar>

    <v-container>
        <router-view></router-view>
    </v-container>
</template>

<style scoped>

</style>
