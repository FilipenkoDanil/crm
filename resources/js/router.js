import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'login',
            path: '/login',
            component: () =>  import('./pages/authorization/LoginPage.vue'),
            meta: {
                layout: 'AuthLayout'
            }
        },
        {
            name: 'register',
            path: '/register',
            component: () => import('./pages/authorization/RegisterPage.vue'),
            meta: {
                layout: 'AuthLayout'
            }
        }
    ]
})

export default router
