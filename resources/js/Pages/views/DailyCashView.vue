<script lang="ts" setup>
import { GetDataWithParams, DailyCash } from "@/types";
import Layout from "./Partials/Layout.vue";
import Pagination from "@/Components/common/Pagination.vue";
import DailyCashList from "@/Components/DailyCashes/DailyCashList.vue";
import FormDailyCash from "@/Components/DailyCashes/FormDailyCash.vue";
import { ref, watch } from "vue";
import { useDailyCash } from "@/composables/useDailyCash";
import FormExpense from "@/Components/Expenses/FormExpense.vue";

const dailyCashes = ref<DailyCash[]>();
const links = ref<Object>();
const ModalIsOpen = ref(false);
const disabledBtnCreate = ref(false);
const idExpense = ref<number>();

const { changeStateDailyCash } = useDailyCash();
const props = defineProps<{
  dailyCashes: GetDataWithParams;
}>();

watch(
  props.dailyCashes,
  (value) => {
    dailyCashes.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const openModal = () => {};

const handleCreated = (data: DailyCash) => {
  dailyCashes.value!.unshift(data);
  disabledBtnCreate.value = true;
};

const changeStateCashRegister = async (id: number, state: boolean) => {
  console.log(id, state);
  console.log(!state);

  const { success, data } = await changeStateDailyCash(id, !state);
  if (success) {
    const findIndex = dailyCashes.value!.findIndex((el) => el.id === id);
    if (findIndex !== -1) {
      dailyCashes.value![findIndex] = data!;
    }
  }
};

const verifyCashIsOpenToday = () => {
  const today = new Date().toISOString().split("T")[0];
  const cashDate = dailyCashes.value![0].created_at.split(" ")[0];
  today === cashDate ? (disabledBtnCreate.value = true) : "";
};

verifyCashIsOpenToday();

const createExpense = (id: number) => {
  idExpense.value = id;
};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Caja Diaria
        </h4>
      </div>
      <div class="col-12 col-md-2">
        <button
          type="button"
          class="btn btn-primary"
          @click="openModal"
          data-bs-toggle="modal"
          data-bs-target="#backDropModal"
          :disabled="disabledBtnCreate"
        >
          Abrir Caja
        </button>
      </div>
    </div>

    <DailyCashList
      :daily-cashes="dailyCashes!"
      @close-cash="changeStateCashRegister"
      @create-expense="createExpense"
    />

    <FormDailyCash :reset="ModalIsOpen" @created="handleCreated" />

    <FormExpense :id-expense="idExpense!" />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
