<script>
export default {
    name: "LoginPage",

    data() {
        return {
            email: '',
            password: '',
            errors: [],

            loading: false
        }
    },

    methods: {
        login() {
            this.loading = true
            axios.get('/sanctum/csrf-cookie')
                .then(() => {
                    axios.post('/login', {
                        email: this.email,
                        password: this.password,
                    })
                        .then(r => {
                            this.loading = false
                            localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN'])
                            this.$router.push({name: 'home'})
                        })
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
    <v-card class="pa-3" elevation="3">

        <v-card-title class="text-h5">
            Welcome to CRMshop!👋🏻
        </v-card-title>

        <v-card-subtitle>
            Please sign-in to your account.
        </v-card-subtitle>

        <v-card-text>
            <v-form @submit.prevent="">
                <v-text-field v-model="email" class="mb-3" color="red" label="Email" type="email"
                              variant="outlined"
                              :error-messages="errors.email"
                              @keydown.enter="login"
                              required></v-text-field>
                <v-text-field v-model="password" class="mb-3" color="red" label="Password" type="password"
                              :error-messages="errors.password"
                              @keydown.enter="login"
                              variant="outlined" required></v-text-field>
                <v-btn @click="login" :loading="loading" block type="submit" color="red-darken-1">Login
                </v-btn>
                <div class="mt-5 d-flex justify-center align-center text-body-1">
                    <span class="mr-5 text-medium-emphasis">New on our platform?</span>
                    <router-link :to="{name: 'register'}" class="text-decoration-none text-red">Create an
                        account
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
</template>

<style scoped>

</style>
