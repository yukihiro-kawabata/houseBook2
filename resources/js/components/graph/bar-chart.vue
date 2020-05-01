<script>
    import { Bar } from 'vue-chartjs'
    import 'chartjs-plugin-colorschemes'
    export default {
        extends: Bar,
        props: [
            'chartData',
            'options'
        ],
        mounted () {
            this.renderChart(this.chartData, this.options)
        }
    }
</script>