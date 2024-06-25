<script lang="ts" setup>
import { ClientToSell, GetDataWithParams } from "@/types";
import Layout from "../Partials/Layout.vue";
import { ref } from "vue";
import { watch } from "vue";
import SearchData from "@/Components/common/SearchData.vue";
import SaleCreditList from "@/Components/SaleCredits/SaleCreditList.vue";
import Pagination from "@/Components/common/Pagination.vue";

const saleCredits = ref<ClientToSell[]>();
const links = ref();

const props = defineProps<{
  saleCredits: GetDataWithParams;
}>();

watch(
  props.saleCredits,
  (value) => {
    console.log(value);
    saleCredits.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const showSearchedData = (data: GetDataWithParams) => {
  saleCredits.value = data.data;
  links.value = data.meta.links;
};

</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Deudas de Clientes
        </h4>
      </div>

    </div>

    <SearchData
      table="saleCredit"
      :search-by="['id']"
      @data-searched="showSearchedData"
    />

    <SaleCreditList :sale-credits="saleCredits!" />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
