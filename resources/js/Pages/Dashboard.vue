<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Layout from "./views/Partials/Layout.vue";
import ChartBestSellingProduct from "@/Components/Products/ChartBestSellingProduct.vue";
import ChartRestockProduct from "@/Components/Products/ChartRestockProduct.vue";
import ChartFinalMoneyDay from "@/Components/DailyCashes/ChartFinalMoneyDay.vue";

import { useDailyCash } from "@/composables/useDailyCash";
import { useExpense } from "@/composables/useExpense";
import { useSale } from "@/composables/useSale";

const { profit, getProfit } = useDailyCash();
const { balance, getBalanceToday } = useExpense();
const { totalSalesToday, getTotalAmountToday } = useSale();

const getIntialData = async () => {
  await getProfit();
  await getBalanceToday();
  await getTotalAmountToday();
  console.log(profit.value);
};

getIntialData();

</script>

<template>
  <Head title="Dashboard" />
  <Layout>
    <!-- <h4 class="py-3 mb-4">
      <span class="text-muted fw-light">Charts /</span> Chart.js
    </h4> -->

    <div class="row">
      <div class="col-lg-8 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">
                  Hola {{ $page.props.auth.user.name }}! 🎉
                </h5>
                <div v-if="profit === -1" class="">
                  <p class="mb-4">
                    <span class="fw-bold text-danger"
                      >No hay una caja abierta hoy</span
                    >
                  </p>
                  <a
                    :href="route('dailyCash.index')"
                    class="btn btn-sm btn-outline-primary"
                    >Abrir Caja</a
                  >
                </div>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img
                  :src="`storage/images/users/${$page.props.auth.image}`"
                  height="140"
                  alt="User Authenticated"
                  data-app-dark-img="illustrations/man-with-laptop-dark.png"
                  data-app-light-img="illustrations/man-with-laptop-light.png"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
          <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div
                  class="card-title d-flex align-items-start justify-content-between"
                >
                  <div class="avatar flex-shrink-0">
                    <img src="/assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded" />
                  </div>
                  <!-- <div class="dropdown">
                    <button
                      class="btn p-0"
                      type="button"
                      id="cardOpt3"
                      data-bs-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div
                      class="dropdown-menu dropdown-menu-end"
                      aria-labelledby="cardOpt3"
                    >
                      <a class="dropdown-item" href="javascript:void(0);"
                        >View More</a
                      >
                      <a class="dropdown-item" href="javascript:void(0);"
                        >Delete</a
                      >
                    </div>
                  </div> -->
                </div>
                <span class="fw-semibold d-block mb-1">Ganancia de Hoy</span>
                <h3 class="card-title mb-2 text-success fw-semibold">
                  S/ {{ profit === -1 ? 0 : profit }}
                </h3>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div
                  class="card-title d-flex align-items-start justify-content-between"
                >
                  <div class="avatar flex-shrink-0">
                    <img src="/assets/img/icons/unicons/wallet-info.png" alt="Credit Card" class="rounded" />
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1"
                  >Monto de Ventas de Hoy</span
                >
                <h3 class="card-title mb-2 text-success fw-semibold">
                  S/ {{ totalSalesToday }}
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Revenue -->
      <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <ChartRestockProduct />
      </div>
      <!--/ Total Revenue -->
      <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
        <div class="row">
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div
                  class="card-title d-flex align-items-start justify-content-between"
                >
                  <div class="avatar flex-shrink-0">
                    <img src="/assets/img/icons/unicons/cc-success.png" alt="Credit Card" class="rounded" />
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Ingresos de Hoy</span>
                <h3 class="card-title mb-2 text-success fw-semibold">
                  S/ {{ balance?.entries }}
                </h3>
              </div>
            </div>
          </div>
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div
                  class="card-title d-flex align-items-start justify-content-between"
                >
                  <div class="avatar flex-shrink-0">
                    <img src="/assets/img/icons/unicons/cc-warning.png" alt="Credit Card" class="rounded" />
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Egresos de Hoy</span>
                <h3 class="card-title mb-2 text-danger fw-semibold">
                  S/ {{ balance?.exits }}
                </h3>
              </div>
            </div>
          </div>
          <!-- </div>
    <div class="row"> -->
          <!-- <div class="col-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div
                  class="d-flex justify-content-between flex-sm-row flex-column gap-3"
                >
                  <div
                    class="d-flex flex-sm-column flex-row align-items-start justify-content-between"
                  >
                    <div class="card-title">
                      <h5 class="text-nowrap mb-2">Profile Report</h5>
                      <span class="badge bg-label-warning rounded-pill"
                        >Year 2021</span
                      >
                    </div>
                    <div class="mt-sm-auto">
                      <small class="text-success text-nowrap fw-semibold"
                        ><i class="bx bx-chevron-up"></i> 68.2%</small
                      >
                      <h3 class="mb-0">$84,686k</h3>
                    </div>
                  </div>
                  <div id="profileReportChart"></div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-12">
        <ChartBestSellingProduct />
      </div>
      <div class="col-md-6 col-12">
        <ChartFinalMoneyDay />
        <!-- <div class="card">
          <div class="card-header header-elements">
            <h5 class="card-title mb-0">Latest Statistics</h5>
            <div class="card-action-element ms-auto py-0">
              <div class="dropdown">
                <button
                  type="button"
                  class="btn dropdown-toggle px-0"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="bx bx-calendar"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex align-items-center"
                      >Today</a
                    >
                  </li>
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex align-items-center"
                      >Yesterday</a
                    >
                  </li>
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex align-items-center"
                      >Last 7 Days</a
                    >
                  </li>
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex align-items-center"
                      >Last 30 Days</a
                    >
                  </li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex align-items-center"
                      >Current Month</a
                    >
                  </li>
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex align-items-center"
                      >Last Month</a
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <canvas
              id="barChart"
              class="chartjs"
              data-height="400"
              height="800"
              style="
                display: block;
                box-sizing: border-box;
                height: 400px;
                width: 440px;
              "
              width="880"
            ></canvas>
          </div>
        </div> -->
      </div>
    </div>
  </Layout>
</template>
