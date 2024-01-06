<script>
import MySnackBar from "@/components/MySnackBar.vue";
import ClientForm from "@/components/client/ClientForm.vue";
import ClientDrawer from "@/components/client/ClientDrawer.vue";

export default {
    name: "ClientsPage",
    components: {ClientDrawer, ClientForm, MySnackBar},

    data() {
        return {
            clients: [],

            search: '',
            loading: false,

            editDrawer: false,
            editableClient: {},
            errors: [],
            disableInputs: false,

            snackbar: false,
            snackbarText: '',

            creatableClient: {}
        }
    },

    methods: {
        getClients() {
            this.loading = true
            axios.get('/api/v1/clients')
                .then(r => {
                    this.clients = r.data
                    this.loading = false
                })
        },

        onRowClick(item, row) {
            this.editDrawer = true
            this.editableClient = {...row.item}
            this.errors = []
            this.disableInputs = this.editableClient.id === 1;

        },

        createClient() {
            axios.post('/api/v1/clients', {
                name: this.creatableClient.name,
                phone: this.creatableClient.phone
            })
                .then(() => {
                    this.getClients()
                    this.showSnackbar('Client created.')
                    this.creatableClient = {}
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                })
        },

        updateClient() {
            axios.patch(`/api/v1/clients/${this.editableClient.id}`, {
                name: this.editableClient.name,
                phone: this.editableClient.phone
            })
                .then(() => {
                    this.getClients()
                    this.showSnackbar('Client updated.')
                    this.errors = []
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                    this.showSnackbar(error.response.data.message)
                })
        },

        deleteClient() {
            axios.delete(`/api/v1/clients/${this.editableClient.id}`)
                .then(r => {
                    this.getClients()
                    this.showSnackbar(r.data.message)
                    this.editDrawer = false
                    this.editableClient = {}
                })
                .catch(error => {
                    this.errors = error.response.data.errors || {}
                    this.showSnackbar(error.response.data.message)
                })
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getClients()

        window.Echo.channel('client')
            .listen('.client-created', () => {
                this.getClients()
            })
    },

    beforeUnmount() {
        window.Echo.leave('client')
    }
}
</script>

<template>
    <v-row class="d-flex justify-space-between">
        <v-col cols="6" sm="6" md="4" lg="3">
            <v-text-field v-model="search" label="Search" placeholder="Client name"
                          density="compact"
                          prepend-inner-icon="mdi-magnify"></v-text-field>
        </v-col>
        <v-col cols="6" sm="6" md="4" lg="3" class="text-end">
            <v-dialog v-if="can('create clients')" width="800" @update:modelValue="this.creatableClient = {}, this.errors = []">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" text="Create client"></v-btn>
                </template>

                <template v-slot:default>
                    <client-form :creatableClient="creatableClient"
                                 :errors="errors"
                                 @createClient="createClient"
                    ></client-form>
                </template>
            </v-dialog>
        </v-col>
    </v-row>


    <v-data-table :items="clients" @click:row="onRowClick" items-per-page="25"
                  :search="search"
                  :loading="loading"
                  hover></v-data-table>


    <client-drawer v-model="editDrawer"
                   :editableClient="editableClient"
                   :errors="errors"
                   :disableInputs="disableInputs"
                   @editDrawerClose="editDrawer = false"
                   @updateClient="updateClient"
                   @deleteClient="deleteClient"></client-drawer>

    <my-snack-bar v-model="snackbar"
                  :snackbar-text="snackbarText"
                  @closeSnack="snackbar = !snackbar"
    ></my-snack-bar>

</template>

<style scoped>

</style>
