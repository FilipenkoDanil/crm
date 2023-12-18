<script>
import MySnackBar from "@/components/MySnackBar.vue";
import ProductDrawer from "@/components/product/ProductDrawer.vue";
import ProductForm from "@/components/product/ProductForm.vue";

export default {
    name: "ProductsPage",
    components: {ProductForm, ProductDrawer, MySnackBar},

    data() {
        return {
            search: '',
            products: [],
            headers: [
                {
                    key: 'id',
                    title: '#',
                },
                {
                    key: 'title',
                    title: 'Title'
                },
                {
                    key: 'barcode',
                    title: 'Barcode'
                },
                {
                    key: 'code',
                    title: 'Code'
                },
                {
                    key: 'category.title',
                    title: 'Category'
                },
                {
                    key: 'purchase_price',
                    title: 'Purchase price'
                },
                {
                    key: 'selling_price',
                    title: 'Selling price'
                },
            ],

            categories: [],

            editDrawer: false,
            editableProduct: {},
            errors: [],

            snackbar: false,
            snackbarText: '',

            creatableProduct: {}
        }
    },

    methods: {
        getProducts() {
            axios.get('/api/v1/products')
                .then(r => {
                    this.products = r.data.data
                })
        },

        getCategories() {
            axios.get('/api/v1/categories')
                .then(r => {
                    this.categories = r.data
                })
        },

        onRowClick(item, row) {
            this.editDrawer = !this.editDrawer
            this.editableProduct = {...row.item}
        },

        createProduct() {
            axios.post('/api/v1/products', {
                title: this.creatableProduct.title,
                barcode: this.creatableProduct.barcode,
                code: this.creatableProduct.code,
                category_id: this.creatableProduct.category_id,
                purchase_price: this.creatableProduct.purchase_price,
                selling_price: this.creatableProduct.selling_price,
                image: this.creatableProduct.image?.[0]
            }, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .then(() => {
                    this.getProducts()
                    this.creatableProduct = []
                    this.errors = []
                    this.showSnackbar('Product created.')
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        updateProduct() {
            axios.post(`/api/v1/products/${this.editableProduct.id}`, {
                _method: 'PATCH',
                title: this.editableProduct.title,
                barcode: this.editableProduct.barcode,
                code: this.editableProduct.code,
                category_id: this.editableProduct.category_id,
                purchase_price: this.editableProduct.purchase_price,
                selling_price: this.editableProduct.selling_price,
                image: this.editableProduct.uploadImage?.[0]
            }, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .then(() => {
                    this.errors = [];
                    this.getProducts()
                    this.showSnackbar('Product updated.')
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        deleteProduct() {
            axios.delete(`/api/v1/products/${this.editableProduct.id}`)
                .then(() => {
                    this.getProducts()
                    this.showSnackbar('Product deleted')
                    this.editableProduct = []
                    this.editDrawer = false
                })
        },

        deleteImage() {
            axios.post(`/api/v1/products/${this.editableProduct.id}`, {
                _method: 'PATCH',
                deleteImage: true
            })
                .then(() => {
                    this.editableProduct.image = null
                    this.products = this.products.map(item => (item.id === this.editableProduct.id ? {...this.editableProduct} : item))
                    this.showSnackbar('Image deleted.')
                })
        },

        showSnackbar(text) {
            this.snackbarText = text
            this.snackbar = true
        }
    },

    watch: {
        editDrawer() {
            this.errors = []
        }
    },

    mounted() {
        this.getProducts()
        this.getCategories()
    }
}
</script>

<template>
    <v-row class="d-flex justify-space-between">
        <v-col cols="6" sm="6" md="4" lg="3">
            <v-text-field v-model="search" label="Search" placeholder="Product name"
                          density="compact"
                          prepend-inner-icon="mdi-magnify"></v-text-field>
        </v-col>
        <v-col cols="6" sm="6" md="4" lg="3" class="text-end">
            <v-dialog width="800" @update:modelValue="this.creatableProduct = {}, this.errors = []">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" text="Create product"></v-btn>
                </template>

                <template v-slot:default>
                    <product-form :creatableProduct="creatableProduct"
                                  :categories="categories"
                                  :errors="errors"
                                  @createProduct="createProduct"
                    ></product-form>
                </template>
            </v-dialog>
        </v-col>
    </v-row>

    <v-data-table :items="products" @click:row="onRowClick" items-per-page="25"
                  :headers="headers"
                  :search="search"
                  hover></v-data-table>

        <product-drawer v-model="editDrawer"
                        :editableProduct="editableProduct"
                        :categories="categories"
                        :errors="errors"
                        @editDrawerClose="editDrawer = false"
                        @deleteImage="deleteImage"
                        @updateProduct="updateProduct"
                        @deleteProduct="deleteProduct"
        ></product-drawer>

    <my-snack-bar
        v-model="snackbar"
        :snackbar-text="snackbarText"
        @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>
</template>

<style scoped>

</style>
