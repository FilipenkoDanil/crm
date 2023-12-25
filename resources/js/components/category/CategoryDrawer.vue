<script>
export default {
    name: "CategoryDrawer",

    props: {
        editDrawer: Boolean,
        categories: {},
        disableInputs: Boolean,
        errors: Object,
        editableCategory: Object
    },

    methods: {
        editDrawerClose() {
            this.$emit('editDrawerClose')
        },
        updateCategory() {
            this.$emit('updateCategory')
        },
        deleteCategory() {
            this.$emit('deleteCategory')
        }
    }
}
</script>

<template>
    <v-navigation-drawer v-bind="editDrawer" temporary rail rail-width="450" location="right">
        <v-card flat>
            <v-card-title class="text-center mb-3">Editing a client #{{ editableCategory.id }}</v-card-title>
            <v-card-text>
                <v-text-field variant="outlined" label="Title" placeholder="voluptas"
                              :disabled="disableInputs"
                              :error-messages="errors.title"
                              v-model="editableCategory.title" density="compact"></v-text-field>
                <v-text-field variant="outlined" label="Slug" placeholder="slug-slug"
                              :disabled="disableInputs"
                              :error-messages="errors.slug"
                              v-model="editableCategory.slug"
                              density="compact"></v-text-field>

                <v-select v-model="editableCategory.parent_id"
                          :items="categories"
                          :disabled="disableInputs"
                          :error-messages="errors.parent_id"
                          clearable
                          item-title="title" item-value="id"
                          placeholder="Parent category"
                          label="Parent category"
                ></v-select>
            </v-card-text>

            <v-card-actions class="px-4">
                <v-btn @click="editDrawerClose" color="warning" variant="outlined">Cancel</v-btn>
                <v-btn @click="updateCategory" color="success" variant="outlined" :disabled="disableInputs">Save</v-btn>
                <v-spacer></v-spacer>
                <v-btn @click="deleteCategory" color="error" variant="outlined" :disabled="disableInputs">Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
