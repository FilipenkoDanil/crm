<script>
import OAuthTooltip from "@/components/oauth/OAuthTooltip.vue";

export default {
    name: "RegisterPage",
    components: {OAuthTooltip},

    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errors: [],

            loading: false,

            oauthWindow: null,
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
                    <o-auth-tooltip @auth="auth"></o-auth-tooltip>
                </v-col>
            </v-row>

        </v-card-text>
    </v-card>
</template>

<style scoped>

</style>
