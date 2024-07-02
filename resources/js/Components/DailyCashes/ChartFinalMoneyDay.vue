<script setup lang="ts">
import * as Chart from "chart.js/auto";
import { onMounted, ref } from "vue";
import { useDailyCash } from "@/composables/useDailyCash";

const { getLastDailyCashes } = useDailyCash();
const dataToView = ref<Chart.ChartDataset<"bar">[]>([]);
const labelToView = ref<string[]>([]);
const chartProduct = ref<HTMLCanvasElement | null>(null);

const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  const options: Intl.DateTimeFormatOptions = {
    year: "numeric",
    month: "short", // Puedes usar "long" para el nombre completo del mes
    day: "numeric",
  };
  return date.toLocaleString("es-ES", options);
};

const getInfoData = async () => {
  const { success, data } = await getLastDailyCashes();
  if (success && data) {
    const dataset = {
      label: "Monto",
      data: data.map((el) => Number(el.final_money)),
      borderWidth: 1,
    };
    dataToView.value = [dataset];
    labelToView.value = data.map((el) => formatDate(el.created_at));
  }
};

const updateChart = () => {
  new Chart.default(chartProduct.value!, {
    type: "polarArea",
    data: {
      labels: labelToView.value,
      datasets: dataToView.value,
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
      },
    },
  });
};

onMounted(async () => {
  await getInfoData();
  updateChart(); // Crear el gráfico inicialmente
});
</script>

<template>
  <div class="card">
    <div class="card-header header-elements row justify-content-around">
      <div class="col-12">
        <h5 class="card-title mb-0">Monto Final de las Úlitmas 5 Cajas</h5>
      </div>
    </div>
    <div class="card-body d-flex flex-wrap justify-content-center">
      <canvas ref="chartProduct"></canvas>
    </div>
  </div>
</template>

<style scoped>
canvas {
  max-height: 450px;
}
</style>
