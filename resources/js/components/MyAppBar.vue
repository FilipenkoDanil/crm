<script>
export default {
    name: "MyAppBar",

    methods: {
        logout() {
            axios.post('/logout')
                .then(() => {
                    localStorage.removeItem('x_xsrf_token')
                    this.$router.push({name: 'login'})
                })
        },
        emitButtonClick() {
            this.$emit('buttonClick');
        },
    }
}
</script>

<template>
    <v-app-bar elevation="1" color="background">
        <v-app-bar-nav-icon @click="emitButtonClick" class="hidden-lg-and-up"></v-app-bar-nav-icon>
        <v-app-bar-title>{{ $route.meta.pageTitleBar }}</v-app-bar-title>
        <v-spacer></v-spacer>
        <v-menu
            min-width="200px"
            rounded
        >
            <template v-slot:activator="{ props }">
                <v-btn
                    icon
                    v-bind="props"
                >
                    <v-avatar
                        color="brown"
                        size="large"
                    >
                        <v-img src="https://i.pravatar.cc/300"></v-img>
                    </v-avatar>
                </v-btn>
            </template>
            <v-card>
                <v-card-text>
                    <div class="mx-auto text-center">
                        <v-avatar
                            color="brown"
                        >
                            <span class="text-h5">as</span>
                        </v-avatar>
                        <h3></h3>
                        <p class="text-caption mt-1">

                        </p>
                        <v-divider v-if="can('create sales')" class="my-3"></v-divider>
                        <v-btn
                            v-if="can('create sales')"
                            rounded
                            variant="text"
                            :to="{name: 'cashbox'}"
                        >
                            Cashbox mode
                        </v-btn>
                        <v-divider class="my-3"></v-divider>
                        <v-btn
                            rounded
                            variant="text"
                            @click="logout"
                        >
                            Disconnect
                        </v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </v-menu>
    </v-app-bar>
</template>

<style scoped>

</style>
