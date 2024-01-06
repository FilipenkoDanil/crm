<script>
import MySnackBar from "@/components/MySnackBar.vue";
import CategoryDrawer from "@/components/category/CategoryDrawer.vue";
import CategoryForm from "@/components/category/CategoryForm.vue";

export default {
    name: "CategoriesPage",
    components: {CategoryForm, CategoryDrawer, MySnackBar},

    data() {
        return {
            categories: [],

            search: '',
            loading: false,

            editDrawer: false,
            editableCategory: {},
            errors: {},
            disableInputs: false,

            snackbar: false,
            snackbarText: '',

            creatableCategory: {},



            headers: [
                {
                    key: 'id',
                    title: '#'
                },
                {
                    key: 'title',
                    title: 'Title'
                },
                {
                    key: 'slug',
                    title: 'Slug'
                },
                {
                    key: 'parent.title',
                    title: 'Parent'
                }
            ]
        }
    },

    methods: {
        getCategories() {
            this.loading = true
            axios.get('/api/v1/categories')
                .then(r => {
                    this.categories = r.data
                    this.loading = false
                })
        },

        onRowClick(item, row) {
            this.editDrawer = true
            this.editableCategory = {...row.item}
            this.errors = []
            this.disableInputs = this.editableCategory.id === 1;

        },

        updateCategory() {
            axios.patch(`/api/v1/categories/${this.editableCategory.id}`, {
                title: this.editableCategory.title,
                slug: this.editableCategory.slug,
                parent_id: this.editableCategory.parent_id
            })
                .then(r => {
                    this.getCategories()
                    this.showSnackbar('Category updated.')
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                    this.showSnackbar(error.response.data.message)
                })
        },

        deleteCategory() {
            axios.delete(`/api/v1/categories/${this.editableCategory.id}`)
                .then(r => {
                    this.getCategories()
                    this.showSnackbar(r.data.message)
                    this.editDrawer = false
                    this.editableCategory = {}
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                    this.showSnackbar(error.response.data.message)
                })
        },

        createCategory() {
            axios.post('/api/v1/categories', {
                title: this.creatableCategory.title,
                parent_id: this.creatableCategory.parent_id
            })
                .then(() => {
                    this.getCategories()
                    this.showSnackbar('Category created')
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getCategories()

        window.Echo.channel('category')
            .listen('.category-created', () => {
                this.getCategories()
            })
            .listen('.category-deleted', () => {
                this.getCategories()
            })
    },

    beforeUnmount() {
        window.Echo.leave('category')
    }
}
</script>

<template>
    <v-row class="d-flex justify-space-between">
        <v-col cols="6" sm="6" md="4" lg="3">
            <v-text-field v-model="search" label="Search" placeholder="Category title"
                          density="compact"
                          prepend-inner-icon="mdi-magnify"></v-text-field>
        </v-col>
        <v-col cols="6" sm="6" md="4" lg="3" class="text-end">
            <v-dialog v-if="can('create categories')" width="800" @update:modelValue="this.creatableCategory = {}, this.errors = []">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" text="Create category"></v-btn>
                </template>

                <template v-slot:default>
                    <category-form :creatableCategory="creatableCategory" :errors="errors" :categories="categories" @createCategory="createCategory"></category-form>
                </template>
            </v-dialog>
        </v-col>
    </v-row>



    <v-data-table :items="categories" :headers="headers" :search="search" :loading="loading" @click:row="onRowClick"></v-data-table>

    <category-drawer v-model="editDrawer"
                     :editableCategory="editableCategory"
                     :categories="categories"
                     :errors="errors"
                     :disableInputs="disableInputs"
                     @editDrawerClose="editDrawer = !editDrawer"
                     @updateCategory="updateCategory"
                     @deleteCategory="deleteCategory"
    ></category-drawer>

    <my-snack-bar v-model="snackbar" :snackbarText="snackbarText" @closeSnack="snackbar = !snackbar"></my-snack-bar>
</template>

<style scoped>

</style>
