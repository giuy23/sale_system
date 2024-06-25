<script lang="ts" setup>
import { GetDataWithParams, SaleDetails } from "@/types/index";
import Layout from "./Partials/Layout.vue";
import { watch } from "vue";
import { ref } from "vue";
import CancelSaleList from "@/Components/Sales/CancelSale/CancelSaleList.vue";

const saleDetails = ref<SaleDetails[]>();

const props = defineProps<{
  saleDetails: GetDataWithParams;
}>();

watch(
  props.saleDetails,
  (value) => {
    saleDetails.value = value.data;

  },
  { immediate: true }
);

// console.log(saleDetails.value);
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">Devolución de Productos</h4>
      </div>
    </div>

    <CancelSaleList :sale-details="saleDetails!" />
  </Layout>
</template>

<style scoped></style>
