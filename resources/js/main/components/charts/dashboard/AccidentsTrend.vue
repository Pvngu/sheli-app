<template>
    <LineChart ref="chartRef" :chartData="testData" :options="options" />
</template>

<script>
import { ref, watch } from "vue";
import { LineChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";
import { useI18n } from "vue-i18n";

Chart.register(...registerables);

export default {
    props: ["data"],
    components: {
        LineChart,
    },
    setup(props) {
        const chartRef = ref();
        const { t } = useI18n();

        const options = ref({
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom",
                },
                title: {
                    display: false,
                    text: "Chart.js Doughnut Chart",
                },
            },
        });

        const testData = ref({});

        watch(props, (newVal, oldVal) => {
            testData.value = {
                labels: newVal.data.accident_trends.labels ? newVal.data.accident_trends.labels : [],
                datasets: [
                    {
                        label: t("menu.accidents"),
                        data: newVal.data.accident_trends.values
                            ? newVal.data.accident_trends.values
                            : [],
                        backgroundColor: "#20C997",
                    },
                ],
            };
        });

        return {
            chartRef,
            testData,
            options,
        };
    },
};
</script>

<style></style>
