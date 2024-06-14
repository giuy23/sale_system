<script lang="ts" setup>
import { ref } from "vue";
import Layout from "./Partials/Layout.vue";
import ExpenseList from "@/Components/Expenses/ExpenseList.vue";
import { Expense, GetDataWithParams } from "@/types";
import { watch } from "vue";
import Pagination from "@/Components/common/Pagination.vue";
import FormExpense from "@/Components/Expenses/FormExpense.vue";

const expenses = ref<Expense[]>();
const expense = ref<Expense | null>(null);
const links = ref();
const modalIsOpen = ref(false);

const props = defineProps<{
  expenses: GetDataWithParams;
}>();

watch(
  props.expenses,
  (value) => {
    expenses.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const editExpense = (data: Expense) => {
  expense.value = data;
};

const handleDeleteExpense = (id: number) => {};

const handleUpdated = (data: Expense) => {
  const findIndex = expenses.value!.findIndex(({ id }) => id === data.id);
  if (findIndex !== -1) {
    expenses.value![findIndex] = data;
  }
};

const handleResetModal = () => {
  modalIsOpen.value = false;
};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Gastos
        </h4>
      </div>
    </div>

    <FormExpense
      :expense="expense!"
      :reset="modalIsOpen"
      @updated="handleUpdated"
      @reset="handleResetModal"
    />

    <ExpenseList
      :expenses="expenses!"
      @edit="editExpense"
      @delete="handleDeleteExpense"
    />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
