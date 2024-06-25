<script lang="ts" setup>
import { ref, watch } from "vue";
import Layout from "./Partials/Layout.vue";
import { SubCategory, GetDataWithParams } from "@/types/index";
import SearchData from "@/Components/common/SearchData.vue";
import FormSubCategory from "@/Components/SubCategories/FormSubCategory.vue";
import Pagination from "@/Components/common/Pagination.vue";
import SubCategoryList from "@/Components/SubCategories/SubCategoryList.vue";
import { useSubCategory } from "@/composables/useSubCategory";
import { confirmDelete, toastSuccess } from "@/Components/utils/toast";

const { deleteSubCategorie } = useSubCategory();
const modalIsOpen = ref(false);
const subCategories = ref<SubCategory[]>();
const subCategory = ref<SubCategory | null>(null);
const links = ref<Object>();
const openModal = () => (modalIsOpen.value = true);
const closeModal = () => {
  modalIsOpen.value = false;
  subCategory.value = null;
};

const props = defineProps<{
  subCategories: GetDataWithParams;
}>();

watch(
  props.subCategories,
  (value) => {
    subCategories.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const showSearchedData = (data: GetDataWithParams) => {
  subCategories.value = data.data;
  links.value = data.meta.links;
};

const handleCreated = (data: SubCategory) => {
  toastSuccess("SubCategoría creada con éxito.");
  subCategories.value?.push(data);
};
const handleUpdated = (data: SubCategory) => {
  const findIndex = subCategories.value!.findIndex(({ id }) => id === data.id);

  if (findIndex !== -1) {
    toastSuccess("SubCategoría actualizada con éxito.");
    subCategories.value![findIndex] = data;
  }
};

const editCategory = (data: SubCategory) => {
  subCategory.value = data;
};

const handleDeleteSubCategory = async (id: number) => {
  const isConfirmed = await confirmDelete(
    "Al eliminar una subcategoría los productos que le pertenezcan a esta pasarán como inactivos"
  );
  if (isConfirmed) {
    const { success } = await deleteSubCategorie(id);
    if (success) {
      toastSuccess("SubCategoría eliminada con éxito.");
      const index = subCategories.value!.findIndex((el) => el.id === id);
      subCategories.value!.splice(index, 1);
    }
  }
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

    <SearchData
      table="subCategory"
      :search-by="['name']"
      @data-searched="showSearchedData"
    />

    <FormSubCategory
      :sub-category="subCategory"
      :reset="modalIsOpen"
      @created="handleCreated"
      @updated="handleUpdated"
      @reset="closeModal"
    />

    <SubCategoryList
      :sub-categories="subCategories!"
      @edit="editCategory"
      @delete="handleDeleteSubCategory"
    />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
