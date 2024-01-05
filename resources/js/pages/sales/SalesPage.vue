<script>
export default {
    name: "SalesPage",

    data() {
        return {
            sales: [],

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
            axios.get('/api/v1/sales')
                .then(r => {
                    this.sales = r.data.data
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
    }
}
</script>

<template>
    <v-data-table :items="sales" :headers="headers" @click:row="onRowClick" hover>
        <template v-slot:item.isPaid="{ value, item }">
            <v-chip :color="getColor(value)">
                {{ !!value ? 'Paid' : 'No' }}
            </v-chip>
        </template>
    </v-data-table>
</template>

<style scoped>

</style>
