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
            component: () => import('./pages/warehouses/WarehousesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Warehouses'
            }
        },
        {
            path: '/warehouses/:id',
            name: 'warehouses.show',
            component: () => import('./pages/warehouses/WarehouseProductsPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Products in warehouse'
            }
        },
        {
            path: '/suppliers',
            name: 'suppliers.index',
            component: () => import('./pages/suppliers/SuppliersPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Suppliers'
            }
        },
        {
            path: '/purchases',
            name: 'purchases.index',
            component: () => import('./pages/purchases/PurchasesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Purchases'
            }
        },
        {
            path: '/purchases/create',
            name: 'purchases.create',
            component: () => import('./pages/purchases/PurchaseCreatePage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Create purchase'
            }
        },
        {
            path: '/categories',
            name: 'categories.index',
            component: () => import('./pages/categories/CategoriesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Categories'
            }
        },
        {
            path: '/trash',
            name: 'trash',
            component: () => import('./pages/trash/TrashPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Trash'
            }
        }
    ]
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('x_xsrf_token');

    if (!token) {
        if (to.name === 'login' || to.name === 'register') {
            return next();
        }

        return next({
            name: 'login',
        });
    }


    if (to.name === 'login' || to.name === 'register') {
        return next({
            name: 'home',
        });
    }

    next();
});

export default router
