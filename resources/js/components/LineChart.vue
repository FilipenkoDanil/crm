<script>
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

import {Line} from 'vue-chartjs'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)
export default {
    name: "LineChart",

    components: {Line},

    data() {
        return {
            loaded: false,

            chartPeriod: 'today',
            toggle: 0,

            chartData: {
                labels: [],
                datasets: []
            },
            options:  {
                responsive: true,
                maintainAspectRatio: false,
                cubicInterpolationMode: 'monotone',
                tension: 0.4
            }
        }
    },

    methods: {
        getChart() {
            axios.get(`/api/v1/chart/${this.chartPeriod}`)
                .then(r => {
                    this.chartData = {
                        labels: r.data.labels,
                        datasets: [
                            { data: r.data.datasets[0].data, label: r.data.datasets[0].label, backgroundColor: '#fa3b3b', borderColor: '#c92121', },
                            { data: r.data.datasets[1].data, label: r.data.datasets[1].label, backgroundColor: '#5323e5', borderColor: '#4d1b9d' }
                        ]
                    };
                    this.loaded = true;
                })
        },

        changePeriod(period) {
            this.chartPeriod = period;
            this.getChart();
        },
    },

    created() {
        window.Echo.channel('sale-created')
            .listen('.sale-created', () => {
                this.getChart()
            })
    },

    beforeUnmount() {
        window.Echo.leave('sale-created')
    },

    mounted() {
        this.getChart()
    }
}
</script>

<template>
    <v-btn-toggle
        v-model="toggle"
        density="compact"
        variant="outlined"
    >
        <v-btn @click="changePeriod('today')">Today</v-btn>
        <v-btn @click="changePeriod('week')">Week</v-btn>
    </v-btn-toggle>

    <v-responsive height="85vh" class="border border-opacity-25 rounded-lg pa-1 bg-surface">
        <Line :data="chartData" :options="options"></Line>
    </v-responsive>
</template>

<style scoped>

</style>
