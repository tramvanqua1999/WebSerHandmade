
<script>
 import {
        Bar,
        mixins
    } from 'vue-chartjs'
    export default {
        extends: Bar,
        mixins: [mixins.reactiveProp],
        props: ['chartData', 'options'],
        data() {
            return {
                options: {
                    title: {
                        display: true,
                        text: 'Top 5 điểm thi cao nhất',
                    }, //Chart.js options
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        },
        mounted() {
            this.renderChart(this.chartdata, this.options)
        }
    }
</script>

