<script>
export default {
    name: "PurchaseInfoPage",

    data() {
        return {
            purchase: {}
        }
    },

    methods: {
        getPurchase() {
            axios.get(`/api/v1/purchases/${this.$route.params.id}`)
                .then(r => {
                    this.purchase = r.data
                })
        }
    },

    mounted() {
        this.getPurchase()
    }
}
</script>

<template>
    <v-row>
        <v-col cols="12">
            <v-btn @click="this.$router.back" icon>
                <v-icon>
                    mdi-arrow-left
                </v-icon>
            </v-btn>
        </v-col>
        <v-col cols="12" lg="10" class="mx-auto">
            <v-card class="rounded-lg" flat>
                <v-card-title class="text-end">Purchase #{{ purchase.id }}</v-card-title>
                <v-card-subtitle class="text-end text-subtitle-1">Date: {{ purchase.created_at }}</v-card-subtitle>

                <v-divider class="my-10"></v-divider>

                <v-card-text>
                    <v-row class="d-flex justify-space-between">
                        <v-col cols="6">
                            <div class="text-high-emphasis text-subtitle-1">Supplier:</div>
                            <div class="text-medium-emphasis text-subtitle-2">{{ purchase?.supplier?.name }}</div>
                            <div class="text-medium-emphasis text-subtitle-2">{{ purchase?.supplier?.phone }}</div>
                            <div class="text-medium-emphasis text-subtitle-2">{{ purchase?.supplier?.company }}</div>
                        </v-col>
                        <v-col cols="6" class="text-end">
                            <div class="text-high-emphasis text-subtitle-1">Warehouse:</div>
                            <div class="text-medium-emphasis text-subtitle-2">{{ purchase.movements && purchase.movements[0] && purchase.movements[0].warehouse ? purchase.movements[0].warehouse.title : 'N/A' }}</div>
                            <div class="text-medium-emphasis text-subtitle-2">{{ purchase.movements && purchase.movements[0] && purchase.movements[0].warehouse ? purchase.movements[0].warehouse.address : 'N/A' }}</div>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-divider class="my-10"></v-divider>

                <v-table density="compact" class="text-subtitle-1">
                    <thead>
                    <tr>
                        <th class="text-left">
                            Title
                        </th>
                        <th class="text-left">
                            Quantity
                        </th>
                        <th class="text-left">
                            Price per one
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-subtitle-2">
                    <tr
                        v-for="item in purchase.movements"
                        :key="item.item"
                    >
                        <td class="py-3">{{ item.product.title }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.unit_price }}</td>
                    </tr>
                    </tbody>
                </v-table>

                <v-divider></v-divider>

                <v-card-text class="d-flex justify-end">
                    <p class="text-subtitle-1"><span class="text-medium-emphasis">Total</span>: {{ purchase.total_amount }}</p>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
