<template>
  <div class="card">
    <div class="card-header header-elements row justify-content-around">
      <div class="col-md-7 col-12">
        <h5 class="card-title mb-0">Productos más vendidos</h5>
      </div>
      <div class="col-md-5 col-12">
        <input
          type="month"
          min="2024-01"
          class="form-control"
          @input="handleSearchProducts"
          v-model="date"
        />
      </div>
    </div>
    <div class="card-body d-flex flex-wrap justify-content-center">
      <canvas
        ref="chartProduct"
        class="chartjs"
        data-height="400"
        height="400"
        :style="{
          display: 'block',
          boxSizing: 'border-box',
          height: '400px',
          width: '100%',
        }"
      ></canvas>
    </div>
  </div>
</template>

<script setup lang="ts">
import * as Chart from "chart.js/auto";
import { onMounted, ref } from "vue";
import { useProduct } from "@/composables/useProduct";
import { backgroundColors } from "@/Components/utils/chartJS";

const { getBestSellingProducts } = useProduct();
let chartInstance: any | null = null; // Variable para almacenar la instancia del gráfico
const dataToView = ref<Chart.ChartDataset<"bar">[]>([]);
const labelToView = ref<string[]>([]);
const date = ref<string>();
const chartProduct = ref<HTMLCanvasElement | null>(null); // Definición de la referencia

const getInfoData = async () => {
  const { success, data } = await getBestSellingProducts(date.value!);
  if (success && data) {
    const dataset = {
      label: "Cantidad Vendida",
      data: data.map((el) => Number(el.sales_count)),
      borderWidth: 1,
      backgroundColor: backgroundColors,
    };
    dataToView.value = [dataset];
    labelToView.value = data.map((el) => el.name);
  } else {
    // En caso de que no haya datos válidos, podrías limpiar los datos actuales
    dataToView.value = [];
    labelToView.value = [];
  }
};

const getCurrentDate = () => {
  const currentTime = new Date();
  const month = String(currentTime.getMonth() + 1).padStart(2, "0");
  const year = currentTime.getFullYear();
  date.value = `${year}-${month}`;
};

const handleSearchProducts = async () => {
  await getInfoData();
  updateChart(); // Actualizar el gráfico después de obtener nuevos datos
};

const updateChart = () => {
  if (!chartProduct.value) {
    return; // Salir si chartProduct no está definido
  }

  if (chartInstance) {
    chartInstance.destroy(); // Destruir el gráfico anterior si existe
  }

  chartInstance = new Chart.default(chartProduct.value, {
    type: "bar",
    data: {
      labels: labelToView.value,
      datasets: dataToView.value,
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: "Productos más vendidos por mes",
        },
      },
      scales: {
        y: {
          stacked: true,
        },
      },
    },
  });

  // Restaurar estilos después de crear el gráfico
  chartProduct.value.style.display = "block";
  chartProduct.value.style.boxSizing = "border-box";
  chartProduct.value.style.height = "400px";
  chartProduct.value.style.width = "100%";
};

onMounted(async () => {
  getCurrentDate();
  await getInfoData();
  updateChart(); // Crear el gráfico inicialmente
});
</script>

<style scoped>
/* Estilos específicos para el componente */
</style>
