<script lang="ts" setup>
import { reactive } from "vue";
import { SearchByDate } from "@/types";
import { useSearch } from "@/composables/useSearch";
import { useSale } from "@/composables/useSale";
import { useExpense } from "@/composables/useExpense";

const { getDate } = useSearch();
const { exportDataReportDailySales } = useSale();
const { exportDataReportDailyExpenses } = useExpense();

const props = defineProps<{
  model: string;
}>();

const currentDate = getDate();

const formInitialSearch: SearchByDate = {
  start_date: currentDate,
  end_date: "",
};

const formSearch = reactive({ ...formInitialSearch });

const searchReport = async () => {
  if (formSearch.start_date === "") return;
  formSearch.end_date = formSearch.start_date;
  await exportDataReportDailySales("excel", { ...formSearch });
  await exportDataReportDailyExpenses("excel", { ...formSearch });
};
</script>

<template>
  <h5 class="card-title text-primary">Generar Reporte Diario</h5>
  <div class="card-body">
    <form action="" @submit.prevent="searchReport">
      <div class="row">
        <div class="col-6">
          <input
            type="date"
            placeholder="MM/DD/YYYY"
            class="form-control"
            v-model="formSearch.start_date"
          />
        </div>
        <div class="col-6">
          <button type="submit" class="btn btn-primary">Generar</button>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped></style>
