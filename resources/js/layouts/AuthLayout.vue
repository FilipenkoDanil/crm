<script>
export default {
    name: "AuthLayout"
}
</script>

<template>
    <v-container class="fill-height">
        <v-row justify="center">
            <v-col cols="12" sm="7" lg="4" xl="3">
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.35s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
