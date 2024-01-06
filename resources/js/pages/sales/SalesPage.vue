<script>
export default {
    name: "SalesPage",

    data() {
        return {
            sales: [],

            loading: false,

            headers: [
                {
                    key: 'id',
                    title: 'Id'
                },
                {
                    key: 'order_reference',
                    title: 'Order id'
                },
                {
                    key: 'client.name',
                    title: 'Client'
                },
                {
                    key: 'total_amount',
                    title: 'Total amount'
                },
                {
                    key: 'profit',
                    title: 'Profit'
                },
                {
                    key: 'user.name',
                    title: 'Seller'
                },
                {
                    key: 'payment.type',
                    title: 'Payment'
                },
                {
                    key: 'isPaid',
                    title: 'Is paid'
                },
                {
                    key: 'created_at',
                    title: 'Created at'
                }
            ]
        }
    },

    methods: {
        getSales() {
            this.loading = true
            axios.get('/api/v1/sales')
                .then(r => {
                    this.sales = r.data.data
                    this.loading = false
                })
        },

        onRowClick(item, row) {
            this.$router.push({name: 'sales.show', params: {id: row.item.id}})
        },

        getColor(value) {
            return value ? 'green' : 'red'
        },

    },

    mounted() {
        this.getSales()

        window.Echo.channel('sale')
            .listen('.sale-created', () => {
                this.getSales()
            })
    },

    beforeUnmount() {
        window.Echo.leave('sale')
    }
}
</script>

<template>
    <v-data-table :items="sales" :headers="headers" :loading="loading" @click:row="onRowClick" hover>
        <template v-slot:item.isPaid="{ value, item }">
            <v-chip :color="getColor(value)">
                {{ !!value ? 'Paid' : 'No' }}
            </v-chip>
        </template>
    </v-data-table>
</template>

<style scoped>

</style>
