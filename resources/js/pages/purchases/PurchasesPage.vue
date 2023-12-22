<script>
import MySnackBar from "@/components/MySnackBar.vue";

export default {
    name: "PurchasesPage",
    components: {MySnackBar},

    data() {
        return {
            purchases: [],

            headers: [
                {
                    key: 'id',
                    title: 'Id'
                },
                {
                    key: 'total_amount',
                    title: 'Total amount'
                },
                {
                    key: 'supplier.name',
                    title: 'Supplier'
                },
                {
                    key: 'user.name',
                    title: 'User'
                },
                {
                    key: 'isApproved',
                    title: 'Approved'
                }
            ],

            snackbar: false,
            snackbarText: '',
        }
    },

    methods: {
        getPurchases() {
            axios.get('/api/v1/purchases')
                .then(r => {
                    this.purchases = r.data.data
                })
        },

        toggleApprove(item) {
            axios.post(`/api/v1/purchases/${item.id}`, {_method: 'PATCH'})
                .then(() => {
                    this.showSnackbar('Approve changed.')
                    this.getPurchases()
                })
                .catch(error => {
                    this.showSnackbar(error.response.data.message)
                })
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getPurchases()
    }
}
</script>

<template>
    <v-data-table :items="purchases" :headers="headers">
        <template v-slot:item.isApproved="{ item }">
            <v-btn @click="toggleApprove(item)" :color="item.isApproved ? 'green' : 'orange-darken-1'">
                {{ item.isApproved ? 'Approved' : 'Approve' }}
            </v-btn>
        </template>
    </v-data-table>

    <my-snack-bar v-model="snackbar" :snackbarText="snackbarText" @closeSnack="snackbar = !snackbar"></my-snack-bar>
</template>

<style scoped>

</style>
