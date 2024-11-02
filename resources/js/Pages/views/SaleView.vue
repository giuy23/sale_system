<script lang="ts" setup>
import { GetDataWithParams, Sale } from "@/types";
import { ref, watch } from "vue";
import Layout from "./Partials/Layout.vue";
import SaleList from "@/Components/Sales/SaleList.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { confirmAction } from "@/Components/utils/toast";
import { useSale } from "@/composables/useSale";
import SearchByDate from "@/Components/common/SearchByDate.vue";

const { cancelSale, exportData } = useSale();
const sales = ref<Sale[]>([]);
const links = ref();

const props = defineProps<{
  sales: GetDataWithParams;
}>();

const cleanupLocalStorage = () => {
  const url = window.location.href;
  const saleIndexUrl = route("sale.index");

  if (url === saleIndexUrl) {
    localStorage.removeItem("searchDataSale");
  }
};

watch(
  props.sales,
  (value) => {
    sales.value = value.data;
    links.value = value.meta.links;
    cleanupLocalStorage();
  },
  { immediate: true }
);

const handleCancelSale = async (id: number) => {
  const isConfirmed = await confirmAction();

  if (isConfirmed) {
    const { success, data } = await cancelSale(id);
    if (success) {
      window.location.href = route("sale.detail", id);
    }
  }
};

const showSearchedData = (data: GetDataWithParams) => {
  sales.value = data.data;
  links.value = data.meta.links;
};

const handleExportData = async (type: string) => {
  await exportData(type, "searchDataSale");
};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Historial de Ventas
        </h4>
      </div>
    </div>

    <SearchByDate model="sale" storage="searchDataSale" @data-searched="showSearchedData" />

    <SaleList
      :sales="sales!"
      @cancel="handleCancelSale"
      @export="handleExportData"
    />

    <Pagination :links="links" storage="searchDataSale" />
  </Layout>
</template>

<style scoped></style>
