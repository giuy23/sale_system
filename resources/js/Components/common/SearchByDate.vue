<script lang="ts" setup>
import { SearchByDate, GetDataWithParams } from "@/types/index";
import { onMounted, reactive } from "vue";
import { useSearch } from "@/composables/useSearch";

const props = defineProps<{
  model: string;
  storage: string;
}>();

const emits = defineEmits<{
  dataSearched: [GetDataWithParams];
}>();

const {
  loading,
  getSearchByDate,
  initialSearch,
  searchData,
  getDatoToStorage,
} = useSearch();

const clearSearchData = () => {
  Object.assign(searchData, initialSearch);
  localStorage.removeItem(props.storage);
  const baseUrl = window.location.origin + window.location.pathname;
  window.history.replaceState(null, "", baseUrl);
  route("sale.index");
};

const handleSeacrhData = async () => {
  const { success, data } = await getSearchByDate(props.model, {
    ...searchData,
  });
  if (success) {
    emits("dataSearched", data!);
    localStorage.setItem(props.storage, JSON.stringify(searchData));
  }
};

onMounted(async () => {
  await getDatoToStorage(props.storage);
});
</script>

<template>
  <div class="card mb-3">
    <h5 class="card-header">Buscar</h5>
    <div class="card-body">
      <form @submit.prevent="handleSeacrhData">
        <div class="row">
          <div class="col-md-6 col-12 mb-4">
            <label for="dateRangePicker" class="form-label"
              >Rango de Fecha</label
            >
            <div
              class="input-group input-daterange"
              id="bs-datepicker-daterange"
            >
              <input
                type="date"
                id="dateRangePicker"
                placeholder="MM/DD/YYYY"
                class="form-control"
                v-model="searchData.start_date"
              />
              <span class="input-group-text">Hasta</span>
              <input
                type="date"
                placeholder="MM/DD/YYYY"
                class="form-control"
                v-model="searchData.end_date"
              />
            </div>
          </div>
          <div class="col-md-6 col-12 mb-4">
            <div class="row">
              <div class="col-md-6 col-6">
                <label class="form-label">Monto Mínimo</label
                ><input
                  type="number"
                  min="0"
                  class="form-control"
                  v-model="searchData.minimum_amount"
                />
              </div>
              <div class="col-md-6 col-6">
                <label class="form-label">Monto Máximo</label
                ><input
                  type="number"
                  min="0"
                  class="form-control"
                  v-model="searchData.maximum_amount"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 d-flex gap-4 justify-content-end">
            <button @click="clearSearchData" class="btn btn-danger">
              LIMPIAR FILTROS</button
            ><button type="submit" class="btn btn-primary">BUSCAR</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
