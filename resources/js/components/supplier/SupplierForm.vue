<script>
export default {
    name: "SupplierForm",

    props: {
        creatableSupplier: Object,
        errors: Object,
    },

    methods: {
        createSupplier() {
            this.$emit('createSupplier')
        }
    }
}
</script>

<template>
    <v-card>
        <v-card-title>
            <span class="text-h5">Create supplier</span>
        </v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableSupplier.name"
                            :error-messages="errors.name"
                            label="Name*"
                            placeholder="Huano Pedro"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableSupplier.phone"
                            label="Phone"
                            placeholder="380981234567"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableSupplier.company"
                            label="Company"
                            placeholder="Lesch Inc"
                        ></v-text-field>
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
                @click="createSupplier"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
