<script>
import OAuthTooltip from "@/components/oauth/OAuthTooltip.vue";
import axios from "axios";

export default {
    name: "LoginPage",
    components: {OAuthTooltip},

    data() {
        return {
            email: '',
            password: '',
            errors: [],

            loading: false,

            oauthWindow: null,
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
                        .then(() => {
                            axios.get('/api/v1/user')
                                .then(r => {
                                    localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN'])
                                    localStorage.setItem('user_name', r.data.name)
                                    this.$router.push({name: 'home'})
                                })
                        })
                        .catch(err => {
                            this.errors = err.response.data.errors
                        })
                        .finally(() => {
                            this.loading = false
                        })
                })
        },

        handleAuthenticatedEvent(event) {
            if (event.origin === window.location.origin && event.data.status === 'authenticated') {
                axios.get("/api/v1/user").then(r => {
                    localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN']);
                    localStorage.setItem('user_name', r.data.name)
                    this.$router.push({name: 'home'})
                })
                    .catch(() => {
                        this.errors.email = 'OAuth error. Please, try again.'
                    })
                    .finally(() => {
                        this.loading = false
                    })
            } else if (event.origin === window.location.origin && event.data.status === 'canceled') {
                this.loading = false;
                this.errors.email = 'OAuth error. Please, try again.'
            }
        },

        auth(provider) {
            this.loading = true;
            this.oauthWindow = window.open(`/auth/${provider}`, "_blank");

            window.addEventListener('message', this.handleAuthenticatedEvent);
        }
    },

    beforeUnmount() {
        window.removeEventListener('message', this.handleAuthenticatedEvent);
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
                    <o-auth-tooltip @auth="auth"></o-auth-tooltip>
                </v-col>
            </v-row>

        </v-card-text>
    </v-card>
</template>

<style scoped>

</style>
