<script>
export default {
    name: "ClientForm",

    props: {
        creatableClient: Object,
        errors: Object,
    },

    methods: {
        createClient() {
            this.$emit("createClient");
        },
    },
}
</script>

<template>
    <v-card>
        <v-card-title>
            <span class="text-h5">Create client</span>
        </v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableClient.name"
                            :error-messages="errors.name"
                            label="Name*"
                            placeholder="Huano Pedro"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field
                            v-model="creatableClient.phone"
                            label="Phone"
                            placeholder="380981234567"
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
                @click="createClient"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
