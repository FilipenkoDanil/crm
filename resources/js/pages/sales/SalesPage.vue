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

    },

    mounted() {
        this.getSales()
    }
}
</script>

<template>
    <v-data-table :items="sales" :headers="headers" @click:row="onRowClick" hover></v-data-table>
</template>

<style scoped>

</style>
