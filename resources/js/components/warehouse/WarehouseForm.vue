<script>
export default {
    name: "WarehouseForm",

    props: {
        creatableWarehouse: Object,
        errors: Object,
    },

    methods: {
        createWarehouse() {
            this.$emit('createWarehouse')
        }
    }
}
</script>

<template>
    <v-card>
        <v-card-title>
            <span class="text-h5">Create warehouse</span>
        </v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableWarehouse.title"
                            :error-messages="errors.title"
                            label="Title*"
                            placeholder="Quibusdam qui ipsam."
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableWarehouse.address"
                            label="Address"
                            placeholder="960 Keeling WaysRempelfurt, NM 05909-4345"
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
                @click="createWarehouse"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
