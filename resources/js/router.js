import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'login',
            path: '/login',
            component: () =>  import('./pages/authorization/LoginPage.vue')
        },
        {
            name: 'register',
            path: '/register',
            component: () => import('./pages/authorization/RegisterPage.vue')
        }
    ]
})

export default router
