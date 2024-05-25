<script lang="ts" setup>
import { ref, watch } from "vue";
import Layout from "./Partials/Layout.vue";
import { SubCategory } from "@/types/index";
import SearchData from "@/Components/common/SearchData.vue";
import FormSubCategory from "@/Components/SubCategories/FormSubCategory.vue"

const props = defineProps<{
  subCategories: SubCategory[] | any;
}>();

const modalIsOpen = ref(false);
const subCategories = ref<SubCategory[]>();
const subCategory = ref<SubCategory | null>(null);
const links = ref();

watch(props.subCategories, (value) => {
  subCategories.value = value.data;
  links.value = value.meta.links
}, {immediate: true})

const openModal = () => (modalIsOpen.value = true);
const handleResetModal = () => (modalIsOpen.value = false);

const showSearchedData = (data: any) => {
  subCategories.value = data.data;
  links.value = data.links;
};

</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Sub Categorías
        </h4>
      </div>
      <div class="col-12 col-md-2">
        <button
          type="button"
          class="btn btn-primary"
          @click="openModal"
          data-bs-toggle="modal"
          data-bs-target="#backDropModal"
        >
          Crear Sub Categoría
        </button>
      </div>
    </div>

    <SearchData table="subCategory" @data-searched="showSearchedData" />

    <FormSubCategory
    :sub-category="subCategory"
    :reset="modalIsOpen"
    @reset="handleResetModal"
    />
  </Layout>
</template>

<style scoped></style>
