import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('./pages/HomePage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Home'
            }
        },
        {
            path: '/login',
            name: 'login',
            component: () =>  import('./pages/authorization/LoginPage.vue'),
            meta: {
                layout: 'AuthLayout'
            }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('./pages/authorization/RegisterPage.vue'),
            meta: {
                layout: 'AuthLayout'
            }
        },
        {
            path: '/products',
            name: 'products.index',
            component: () => import('./pages/products/ProductsPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Products'
            }
        },
        {
            path: '/clients',
            name: 'clients.index',
            component: () => import('./pages/clients/ClientsPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Clients'
            }
        },
        {
            path: '/warehouses',
            name: 'warehouses.index',
            component: () => import('./pages/warehouse/WarehousesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Warehouses'
            }
        },
        {
            path: '/warehouses/:id',
            name: 'warehouses.show',
            component: () => import('./pages/warehouse/WarehouseProductsPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Products in warehouse'
            }
        }
    ]
})

export default router
