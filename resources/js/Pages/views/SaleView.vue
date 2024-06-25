<script lang="ts" setup>
import { GetDataWithParams, Sale } from "@/types";
import { ref, watch } from "vue";
import Layout from "./Partials/Layout.vue";
import SaleList from "@/Components/Sales/SaleList.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { confirmAction } from "@/Components/utils/toast";
import { useSale } from "@/composables/useSale"

const { cancelSale } = useSale()
const sales = ref<Sale[]>([]);
const links = ref();

const props = defineProps<{
  sales: GetDataWithParams;
}>();

watch(
  props.sales,
  (value) => {
    sales.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const handleCancelSale = async(id: number) => {
  const isConfirmed = await confirmAction();

  if (isConfirmed) {
    const { success, data } = await cancelSale(id)
    if (success) {
      window.location.href = route('sale.detail', id);
    }
  }

};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Proveedores
        </h4>
      </div>
      <!-- <div class="col-12 col-md-2">
        <button
          type="button"
          class="btn btn-primary"
          @click="openModal"
          data-bs-toggle="modal"
          data-bs-target="#backDropModal"
        >
          Crear Proveedor
        </button>
      </div> -->
    </div>

    <SaleList :sales="sales!" @cancel="handleCancelSale" />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
