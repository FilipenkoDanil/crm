<script>
export default {
    name: "AuthLayout"
}
</script>

<template>
    <v-container class="fill-height">
        <v-row justify="center">
            <v-col cols="12" sm="7" lg="4" xl="3">
                <router-view></router-view>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>

</style>
