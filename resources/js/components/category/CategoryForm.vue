<script>
export default {
    name: "CategoryForm",

    props: {
        creatableCategory: Object,
        errors: Object,
        categories: Object
    },

    methods: {
        createCategory() {
            this.$emit('createCategory')
        }
    }
}
</script>

<template>
    <v-card>
        <v-card-title>
            <span class="text-h5">Create category</span>
        </v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableCategory.title"
                            :error-messages="errors.title"
                            label="Title*"
                            placeholder="voluptas"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-autocomplete
                            :items="categories"
                            v-model="creatableCategory.parent_id"
                            :error-messages="errors.parent_id"
                            item-title="title"
                            item-value="id"
                            label="Parent category"
                            placeholder="Parent category"
                        ></v-autocomplete>
                    </v-col>
                </v-row>
            </v-container>
            <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="blue-darken-1"
                variant="text"
                block
                @click="createCategory"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
