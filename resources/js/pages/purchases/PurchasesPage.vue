<script>
import MySnackBar from "@/components/MySnackBar.vue";

export default {
    name: "PurchasesPage",
    components: {MySnackBar},

    data() {
        return {
            purchases: [],

            loading: false,

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
            this.loading = true
            axios.get('/api/v1/purchases')
                .then(r => {
                    this.purchases = r.data.data
                    this.loading = false
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

        onRowClick(item, row) {
            this.$router.push({name: 'purchases.show', params: {id: row.item.id}})
        },

        showSnackbar(text) {
            this.snackbar = true
            this.snackbarText = text
        }
    },

    mounted() {
        this.getPurchases()

        window.Echo.channel('purchase')
            .listen('.purchase', () => {
                this.getPurchases()
            })
    },

    beforeUnmount() {
        window.Echo.leave('purchase')
    },
}
</script>

<template>
    <v-data-table :items="purchases" :headers="headers" :loading="loading" @click:row="onRowClick" hover>
        <template v-slot:item.isApproved="{ item }">
            <v-btn :disabled="!can('edit purchases')" @click.stop.prevent="toggleApprove(item)" :color="item.isApproved ? 'green' : 'orange-darken-1'">
                {{ item.isApproved ? 'Approved' : 'Approve' }}
            </v-btn>
        </template>
    </v-data-table>

    <my-snack-bar v-model="snackbar" :snackbarText="snackbarText" @closeSnack="snackbar = !snackbar"></my-snack-bar>
</template>

<style scoped>

</style>
