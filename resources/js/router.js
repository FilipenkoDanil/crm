import {createRouter, createWebHistory} from "vue-router";

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
            },
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./pages/authorization/LoginPage.vue'),
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
                pageTitleBar: 'Products',
                permissions: ['show products']
            }
        },
        {
            path: '/clients',
            name: 'clients.index',
            component: () => import('./pages/clients/ClientsPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Clients',
                permissions: ['show clients']
            }
        },
        {
            path: '/warehouses',
            name: 'warehouses.index',
            component: () => import('./pages/warehouses/WarehousesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Warehouses',
                permissions: ['show warehouses']
            }
        },
        {
            path: '/warehouses/:id',
            name: 'warehouses.show',
            component: () => import('./pages/warehouses/WarehouseProductsPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Products in warehouse',
                permissions: ['show warehouses']
            }
        },
        {
            path: '/suppliers',
            name: 'suppliers.index',
            component: () => import('./pages/suppliers/SuppliersPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Suppliers',
                permissions: ['show suppliers']
            }
        },
        {
            path: '/purchases',
            name: 'purchases.index',
            component: () => import('./pages/purchases/PurchasesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Purchases',
                permissions: ['show purchases']
            }
        },
        {
            path: '/purchases/create',
            name: 'purchases.create',
            component: () => import('./pages/purchases/PurchaseCreatePage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Create purchase',
                permissions: ['create purchases']
            }
        },
        {
            path: '/purchases/:id',
            name: 'purchases.show',
            component: () => import('./pages/purchases/PurchaseInfoPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Purchase info',
                permissions: ['show purchases']
            }
        },
        {
            path: '/sales',
            name: 'sales.index',
            component: () => import('./pages/sales/SalesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Sales',
                permissions: ['show sales']
            },
        },
        {
            path: '/sales/:id',
            name: 'sales.show',
            component: () => import('./pages/sales/SaleInfoPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Sale info',
                permissions: ['show sales']
            }
        },
        {
            path: '/categories',
            name: 'categories.index',
            component: () => import('./pages/categories/CategoriesPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Categories',
                permissions: ['show categories']
            }
        },
        {
            path: '/trash',
            name: 'trash',
            component: () => import('./pages/trash/TrashPage.vue'),
            meta: {
                layout: 'MainLayout',
                pageTitleBar: 'Trash',
                permissions: ['show trash']
            }
        },
        {
            path: '/cashbox',
            name: 'cashbox',
            component: () => import('./pages/CashboxPage.vue'),
            meta: {
                layout: 'CashboxLayout',
                permissions: ['create sales']
            }
        }
    ]
})

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('x_xsrf_token');

    if (!token) {
        if (to.name === 'login' || to.name === 'register') {
            next();
        } else {
            next({
                name: 'login',
            });
        }
    } else {
        if (to.name === 'login' || to.name === 'register') {
            next({
                name: 'home',
            });
        } else {
            const response = await axios.get('/api/v1/get-permissions');
            window.Laravel.jsPermissions = response.data;

            const requiredPermissions = to.meta.permissions;

            if (!requiredPermissions || hasAllPermissions(requiredPermissions)) {
                next();
            } else {
                next({ name: 'home' });
            }
        }
    }
});

function hasAllPermissions(requiredPermissions) {
    const userPermissions = window.Laravel.jsPermissions?.permissions || [];

    return requiredPermissions.every(permission =>
        userPermissions.includes(permission)
    );
}

export default router
