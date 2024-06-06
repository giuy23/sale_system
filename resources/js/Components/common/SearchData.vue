<script lang="ts" setup>
import { ref } from "vue";
import { useSearch } from "@/composables/useSearch";
import db from "just-debounce"
import { GetDataWithParams } from "@/types";

const props = defineProps<{
  table: string;
  searchBy: string[];
}>();

const emits = defineEmits<{
  dataSearched: [GetDataWithParams];
}>();

const { getSearchData } = useSearch();

const searchValue = ref("");

const handleSearchData = db(async() => {
  if (!searchValue.value) return;

  const { success, data } = await getSearchData(
    props.table,
    searchValue.value,
    props.searchBy
  );
  if (success) {
    emits("dataSearched", data!);
  }
}, 450)

</script>

<template>
  <div class="row mx-0 mb-3 justify-content-end">
    <div class="card col-12 col-md-3 d-flex flex-wrap flex-row p-2 gap-2">
      <span class="fw-bold block mr-12 align-content-center">Buscar</span>
      <div class="d-flex align-items-center">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          v-model.trim="searchValue"
          @input="handleSearchData"
          type="text"
          class="form-control border-0 shadow-none ms-2"
          placeholder="Buscar por Nombre..."
          aria-label="Buscar por Nombre..."
        />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
