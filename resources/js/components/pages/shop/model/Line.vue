
<script>
 import {
        Line,
        mixins
    } from 'vue-chartjs'
    export default {
        extends: Line,
        mixins: [mixins.reactiveProp],
        props: ['chartData', 'options'],	
        data() {
            return {
                options: {
                    title: {
                        display: true,
                        text: 'Biểu đồ trung bình điểm từng khóa',
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