<script lang="ts" setup>
import { GetDataWithParams, DailyCash, CreateExpense, Expense } from "@/types";
import Layout from "./Partials/Layout.vue";
import Pagination from "@/Components/common/Pagination.vue";
import DailyCashList from "@/Components/DailyCashes/DailyCashList.vue";
import FormDailyCash from "@/Components/DailyCashes/FormDailyCash.vue";
import FormExpense from "@/Components/Expenses/FormExpense.vue";
import { ref, watch } from "vue";
import { useDailyCash } from "@/composables/useDailyCash";
import { toastSuccess } from "@/Components/utils/toast";
import SearchByDate from "@/Components/common/SearchByDate.vue";
import { onMounted } from "vue";

const dailyCashes = ref<DailyCash[]>();
const links = ref();
const ModalIsOpen = ref(false);
const disabledBtnCreate = ref(false);
const expense = ref<CreateExpense>({
  id: 0,
  type: 1,
});

const cleanupLocalStorage = () => {
  const url = window.location.href;
  const saleIndexUrl = route("dailyCash.index");

  if (url === saleIndexUrl) {
    localStorage.removeItem("searchDataCash");
  }
};

const { changeStateDailyCash, exportData } = useDailyCash();
const props = defineProps<{
  dailyCashes: GetDataWithParams;
}>();

watch(
  props.dailyCashes,
  (value) => {
    dailyCashes.value = value.data;
    links.value = value.meta.links;
    cleanupLocalStorage();
  },
  { immediate: true }
);

onMounted(() => {
  verifyCashIsOpenToday();
});

const openModal = () => {};
const today = new Date().toISOString().split("T")[0];

const handleCreated = (data: DailyCash) => {
  dailyCashes.value!.unshift(data);
  disabledBtnCreate.value = true;
  localStorage.setItem("cash", JSON.stringify({ date: today, is_open: true }));
  toastSuccess("Caja creada con éxito");
};

const changeStateCashRegister = async (id: number, state: boolean) => {
  const { success, data } = await changeStateDailyCash(id, state);
  if (success) {
    const findIndex = dailyCashes.value!.findIndex((el) => el.id === id);
    if (findIndex !== -1) {
      dailyCashes.value![findIndex] = data!;
    }
    console.log(state);

    state
      ? toastSuccess("Caja Cerrada con éxito")
      : toastSuccess("Caja Abierta con éxito");
  }
};

const verifyCashIsOpenToday = () => {
  let savedData = JSON.parse(localStorage.getItem("cash") || "{}");

  if (!savedData.date || savedData.date !== today) {
    localStorage.setItem(
      "cash",
      JSON.stringify({ date: today, is_open: false })
    );
    savedData = { date: today, is_open: false };
  }
  disabledBtnCreate.value = savedData.is_open;
};

const createExpense = (id: number, type: number) => {
  expense.value = { id, type };
};

const handleCreateExpense = (data: Expense) => {
  const dailyCash = dailyCashes.value?.find(
    ({ id }) => id === data.daily_cash_id
  );
  if (data.type == 2) {
    dailyCash!.final_money -= data.amount;
    dailyCash!.profit = dailyCash!.final_money - dailyCash!.start_money;
  } else {
    dailyCash!.final_money += data.amount;
    dailyCash!.profit = dailyCash!.final_money - dailyCash!.start_money;
  }

  toastSuccess("Monto actualizado con éxito");
};

const showSearchedData = (data: GetDataWithParams) => {
  dailyCashes.value = data.data;
  links.value = data.meta.links;
};

const handleExportData = async (type: string) => {
  await exportData(type, "searchDataCash");
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
    <div v-if="!disabledBtnCreate" class="row mb-3">
      <div class="col-12">
        <div class="card bg-warning text-white mb-3 text-center">
          <div class="card-header">Caja no Abierta</div>
          <div class="card-body">
            <p class="card-text">
              No Haz Abierto una Caja Hoy. Para Realizar una Venta Debes Abrir
              Una.
            </p>
          </div>
        </div>
      </div>
    </div>

    <SearchByDate
      model="dailyCash"
      @data-searched="showSearchedData"
      storage="searchDataCash"
    />

    <DailyCashList
      :daily-cashes="dailyCashes!"
      @close-cash="changeStateCashRegister"
      @create-expense="createExpense"
      @export="handleExportData"
    />

    <FormDailyCash :reset="ModalIsOpen" @created="handleCreated" />

    <FormExpense :create-expense="expense!" @created="handleCreateExpense" />

    <Pagination :links="links" storage="searchDataCash" />
  </Layout>
</template>

<style scoped></style>
