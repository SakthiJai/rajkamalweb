<template>
	<BarChart ref="chartRef" :chartData="testData" :options="options" />
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
            const rawDates = newVal.data.purchaseSales.dates || [];

            const formattedDates = rawDates.map(dateStr => {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    });

			testData.value = {
                labels: formattedDates,
				// labels: newVal.data.purchaseSales.dates
				// 	? newVal.data.purchaseSales.dates
				// 	: [],
				datasets: [
					{

						label: t("menu.purchases"),
						data: newVal.data.purchaseSales.sales
							? newVal.data.purchaseSales.sales
							: [],
						backgroundColor: "#20C997",
					},

					{
						label: t("menu.sales"),
						data: newVal.data.purchaseSales.sales
							? newVal.data.purchaseSales.sales
							: [],
						backgroundColor: "#FFCD56",
					},

				],

			};
            console.log("Purchases:", newVal.data.purchaseSales.purchases);
            console.log("Sales:", newVal.data.purchaseSales.sales);

		});
//         watch(props, (newVal, oldVal) => {
//     const rawDates = newVal.data.purchaseSales.dates || [];

//     // Format the dates to DD/MM/YYYY
//     const formattedDates = rawDates.map(dateStr => {
//         const date = new Date(dateStr);
//         const day = String(date.getDate()).padStart(2, '0');
//         const month = String(date.getMonth() + 1).padStart(2, '0');
//         const year = date.getFullYear();
//         return `${day}/${month}/${year}`;
//     });

//     testData.value = {
//         labels: formattedDates,
//         datasets: [
//             {
//                 label: t("menu.purchases"),
//                 data: newVal.data.purchaseSales.purchases || [],
//                 backgroundColor: "#20C997",
//             },
//             {
//                 label: t("menu.sales"),
//                 data: newVal.data.purchaseSales.sales || [],
//                 backgroundColor: "#FFCD56",
//             },
//         ],
//     };

//     console.log("Purchases:", newVal.data.purchaseSales.purchases);
//     console.log("Sales:", newVal.data.purchaseSales.sales);
// });


		return {
			chartRef,
			testData,
			options,
		};
	},
};
</script>

<style></style>
