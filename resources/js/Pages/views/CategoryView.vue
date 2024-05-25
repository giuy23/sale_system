<script lang="ts" setup>
import FormCategory from "@/Components/Categories/FormCategory.vue";
import CategoryList from "@/Components/Categories/CategoryList.vue";
import Layout from "./Partials/Layout.vue";
import { Category } from "@/types";
import { ref } from "vue";
import { useCategory } from "@/composables/useCategory";
import { watch } from "vue";
import Pagination from "@/Components/common/Pagination.vue";
import SearchData from "@/Components/common/SearchData.vue";

const { deleteCategory } = useCategory();
const categories = ref<Category[]>();
const links = ref();
const category = ref<Category | null>(null);
const modalIsOpen = ref(false);
const props = defineProps<{
  categories: Category[] | any;
}>();

watch(
  props.categories,
  (value) => {
    categories.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const handleCreated = (data: Category) => {
  categories.value!.push(data);
};

const handleUpdated = (data: Category) => {
  const findIndex = categories.value!.findIndex(({ id }) => id === data.id);
  if (findIndex !== -1) {
    categories.value![findIndex] = data;
  }
};

const editCategory = (data: Category) => {
  category.value = data;
};

const handleDeleteCategory = async (id: number) => {
  const success = await deleteCategory(id);
  if (success) {
    const index = categories.value!.findIndex((el) => el.id === id);
    categories.value!.splice(index, 1);
  }
};

const openModal = () => (modalIsOpen.value = true);
const handleResetModal = () => (modalIsOpen.value = false);

const showSearchedData = (data: any) => {
  categories.value = data.data;
  links.value = data.links;
};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Categorías
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
          Crear Categoría
        </button>
      </div>
    </div>

    <SearchData table="category" @data-searched="showSearchedData" />

    <FormCategory
      :category="category"
      :reset="modalIsOpen"
      @created="handleCreated"
      @updated="handleUpdated"
      @reset="handleResetModal"
    />

    <CategoryList
      v-if="categories"
      :categories="categories"
      @edit="editCategory"
      @delete="handleDeleteCategory"
    />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
