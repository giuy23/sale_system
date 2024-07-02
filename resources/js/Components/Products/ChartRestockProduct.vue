<script lang="ts" setup>
import * as Chart from "chart.js/auto";
import { onMounted, ref } from "vue";
import { useProduct } from "@/composables/useProduct";

const { productsRestock, getProductsToRestock } = useProduct();
const dataToView = ref<Chart.ChartDataset<"bar">[]>([]);
const labelToView = ref<string[]>([]);
const chartProduct = ref<HTMLCanvasElement | null>(null);

const getInfoData = async () => {
  await getProductsToRestock();

  const dataset = {
    label: "Cantidad Actual",
    data: productsRestock.value.map((el) => Number(el.quantity)),
    borderWidth: 1,
  };
  dataToView.value = [dataset];
  labelToView.value = productsRestock.value.map((el) => el.name);
};

const updateChart = () => {
  new Chart.default(chartProduct.value!, {
    type: "line",
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
    <div class="row row-bordered g-0">
      <div class="col-12">
        <h5 class="card-header m-0 me-2 pb-3">Productos para Reabastecer</h5>
        <canvas ref="chartProduct"></canvas>
      </div>
      <!-- <div class="col-md-4">
        <div class="card-body">
          <div class="text-center"></div>
        </div>
        <div id="growthChart"></div>
        <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

        <div
          class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between"
        >
          <div class="d-flex">
            <div class="me-2">
              <span class="badge bg-label-primary p-2"
                ><i class="bx bx-dollar text-primary"></i
              ></span>
            </div>
            <div class="d-flex flex-column">
              <small>2022</small>
              <h6 class="mb-0">$32.5k</h6>
            </div>
          </div>
          <div class="d-flex">
            <div class="me-2">
              <span class="badge bg-label-info p-2"
                ><i class="bx bx-wallet text-info"></i
              ></span>
            </div>
            <div class="d-flex flex-column">
              <small>2021</small>
              <h6 class="mb-0">$41.2k</h6>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</template>

<style scoped>
canvas {
  max-height: 150px;
}
</style>
