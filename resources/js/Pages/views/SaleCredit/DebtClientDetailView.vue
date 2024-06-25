<script lang="ts" setup>
import { CreditSaleClient, GetDataWithParams, ClientTotalDebt } from "@/types";
import Layout from "../Partials/Layout.vue";
import { watch } from "vue";
import { ref } from "vue";
import Pagination from "@/Components/common/Pagination.vue";
import DebtClientList from "@/Components/SaleCredits/DebtClient/DebtClientList.vue";

const saleCredits = ref<CreditSaleClient[]>([]);
const links = ref();
const clientDebt = ref<ClientTotalDebt>();

const props = defineProps<{
  saleCredits: GetDataWithParams;
  totalDebt: number;
  client: { id: number; full_name: string };
}>();

// watch(props.debts, () =>)
watch(
  props,
  (value) => {
    saleCredits.value = value.saleCredits.data;
    links.value = value.saleCredits.meta.links;

    clientDebt.value = {
      id: value.client.id,
      full_name: value.client.full_name,
      total_debt: value.totalDebt,
    };
  },
  { immediate: true }
);


console.log(props.totalDebt);
</script>

<template>
  <Layout>
    <DebtClientList
      :sale-credits="saleCredits!"
      :client="clientDebt!"
    />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
