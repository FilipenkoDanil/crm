<script>
export default {
    name: "RegisterPage",

    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errors: [],

            loading: false
        }
    },

    methods: {
        register() {
            this.loading = true
            axios.get('/sanctum/csrf-cookie')
                .then(() => {
                    axios.post('/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                    })
                        .then(() => this.loading = false)
                        .catch(err => {
                            this.errors = err.response.data.errors
                            this.loading = false
                        })
                })
        }
    }
}
</script>

<template>
    <v-container class="fill-height">
        <v-row justify="center">
            <v-col cols="12" sm="7" lg="4" xl="3">
                <v-card class="pa-3" elevation="3">

                    <v-card-title class="text-h5">
                        Adventure starts here 🚀
                    </v-card-title>

                    <v-card-subtitle>
                        Make your app management easy and fun!
                    </v-card-subtitle>

                    <v-card-text>
                        <v-form @submit.prevent="">
                            <v-text-field v-model="name" class="mb-3" color="red" label="Username" type="text"
                                          variant="outlined"
                                          :error-messages="this.errors.name"
                                          required></v-text-field>
                            <v-text-field v-model="email" class="mb-3" color="red" label="Email" type="email"
                                          variant="outlined"
                                          :error-messages="this.errors.email"
                                          required></v-text-field>
                            <v-text-field v-model="password" class="mb-3" color="red" label="Password" type="password"
                                          :error-messages="this.errors.password"
                                          variant="outlined" required></v-text-field>
                            <v-text-field v-model="password_confirmation" class="mb-3" color="red"
                                          label="Password confirmation"
                                          :error-messages="this.errors.password_confirmation"
                                          type="password" variant="outlined" required></v-text-field>
                            <v-btn @click="register" :loading="loading" block type="submit" color="red-darken-1">Sign
                                up
                            </v-btn>
                            <div class="mt-5 d-flex justify-center align-center text-body-1">
                                <span class="mr-5 text-medium-emphasis">Already have an account?</span>
                                <router-link :to="{name: 'login'}" class="text-decoration-none text-red">Sign in
                                    instead
                                </router-link>
                            </div>
                        </v-form>

                        <v-row class="mt-2">
                            <v-col cols="12" class="d-flex align-center mb-0">
                                <v-divider></v-divider>
                                <span class="mx-4">or</span>
                                <v-divider></v-divider>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-center mt-n5">
                                <v-btn variant="plain" icon>
                                    <v-icon color="red">
                                        mdi-google
                                    </v-icon>
                                </v-btn>
                                <v-btn variant="plain" icon>
                                    <v-icon color="blue">
                                        mdi-facebook
                                    </v-icon>
                                </v-btn>
                                <v-btn variant="plain" icon>
                                    <v-icon>
                                        mdi-github
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>

</style>