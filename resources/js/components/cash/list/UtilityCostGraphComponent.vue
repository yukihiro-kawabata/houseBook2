<template>
  <BarChart class="chart"
    v-if="loaded"
    :chartData="chartData"
    />
</template>

<script>
  import BarChart from '../../graph/bar-chart';

  const KAMOKU_NAME = '水道光熱費';

  export default {
    data: function() {
      return {
        loaded: false,
        chartData: {
            labels: {},
            datasets: [
                {
                    label: KAMOKU_NAME,
                    data: {},
                    borderWidth: 1
                }
            ],
        },
        options: {
            plugins: {
                colorschemes: {
                    scheme: 'brewer.Accent6'
                }
            }
        }
      }
    },
    components: {
      BarChart,
    },
    methods: {
        xdata() {
            axios.get('/api/cash/sum_kamoku_graph?axis=x')
                .then((res) => {
                    this.chartData.labels = res.data;
                });
        },
        ydata() {
            axios.get('/api/cash/sum_kamoku_graph?axis=' + KAMOKU_NAME)
                .then((res) => {
                    this.chartData.datasets[0].data = res.data;
                    this.loaded = true;
                });
        }
    },
    mounted() {
        this.xdata();
        this.ydata();
    }
  }
</script>