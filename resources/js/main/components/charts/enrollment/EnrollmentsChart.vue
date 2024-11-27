<template>
    <BarChart :chartData="chartData" :options="options" />
</template>

<script>
import { ref, watch } from "vue";
import { BarChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";
import { useI18n } from "vue-i18n";

Chart.register(...registerables);

export default {
    props: ["data"],
    components: {
        BarChart,
    },
    setup(props) {
      const { t } = useI18n();

      const options = ref({
        responsive: true,
        plugins: {
          legend: {
            position: "bottom",
          },
          title: {
            display: props.title != "" ? true : false,
            text: props.title,
          },
        },
        scales: {
          x: {
            stacked: true,
            ticks: {
              display: false,
            }
          },
          y: {
            stacked: true,
            position: 'left',
            title: {
              display: true,
              text: 'Payments and Interest',
            },
          },
          'y-axis-2': {
            type: 'linear',
            position: 'right',
            title: {
              display: true,
              text: 'Total Balance',
            },
            grid: {
              drawOnChartArea: false, // only want the grid lines for one axis to show up
            },
          },
        },
      });

      const chartData = ref({
      labels: props.data.dates,
      datasets: [
          {
          type: 'bar',
          label: t('enrollment.payments'),
          data: props.data.payments,
          backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color for payments
          },
          {
          type: 'bar',
          label: t('enrollment.interest'),
          data: props.data.interests,
          backgroundColor: 'rgba(255, 159, 64, 0.2)', // Color for interest
          },
          {
          type: 'line',
          label: t('enrollment.balance'),
          data: props.data.balances,
          borderColor: 'rgba(54, 162, 235, 1)', // Color for line
          borderWidth: 2,
          fill: false,
          yAxisID: 'y-axis-2',
          },
      ],
      });

      watch(() => props.data, (newData) => {
          chartData.value.labels = newData.dates;
          chartData.value.datasets[0].data = newData.principals;
          chartData.value.datasets[1].data = newData.interests;
          chartData.value.datasets[2].data = newData.balances;
      });

      return {
          chartData,
          options,
      }
  }
}
</script>