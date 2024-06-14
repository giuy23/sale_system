<script lang="ts" setup>
import { Category, SubCategory } from "@/types";
import { ref, reactive, watch } from "vue";
import { useSearch } from "@/composables/useSearch";
import { useSubCategory } from "@/composables/useSubCategory";
import db from "just-debounce";
const { getSearchData } = useSearch();
const { createSubCategorie, updateteSubCategorie } = useSubCategory();

const props = defineProps<{
  subCategory: SubCategory | null;
  reset: boolean;
}>();

const emits = defineEmits<{
  created: [SubCategory];
  updated: [SubCategory];
  reset: [void];
}>();

const initialSubCategory: SubCategory = {
  id: 0,
  name: "",
  category_id: 0,
};

const modalRef = ref<HTMLDivElement | null>(null);
const categories = ref<Category[]>([]);
const categorieSelected = ref<number | null>();

let subCategoryForm = reactive({ ...initialSubCategory });

watch(
  () => props.subCategory,
  async (value) => {
    if (value) {
      Object.assign(subCategoryForm, value);
      categorieSelected.value = value!.category_id;
      await getCategoriesVueSelect(value!.category!.id);
    }
  }
  // { immediate: true }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const handleSaveCategory = () => {
  subCategoryForm.id ? handleEditSubCategory() : handleCreateSubCategory();
};

const handleCreateSubCategory = async () => {
  const { success, data } = await createSubCategorie({
    ...subCategoryForm,
    category_id: categorieSelected.value!,
  });
  if (success) {
    closeModal();
    emits("created", data);
    return;
  }
};

const handleEditSubCategory = async () => {
  const { success, data } = await updateteSubCategorie({
    ...subCategoryForm,
    category_id: categorieSelected.value!,
  });
  if (success) {
    closeModal();
    emits("updated", data);
    return;
  }
};

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(subCategoryForm, { ...initialSubCategory });
  categorieSelected.value = null;
  emits("reset");
};

const getCategories = db(async (value: string | number) => {
  categories.value = [];
  const { success, data } = await getSearchData("category", value, [
    "name",
    "id",
  ]);
  if (success) {
    categories.value = data!.data;
  }
}, 450);

const getCategoriesVueSelect = async (data: string | number) => {
  let search;
  typeof data === "number" ? (search = data) : (search = data.trim());
  if (!search) return;

  await getCategories(search);
};

const handleSelected = ({ id }: Category) => {
  categorieSelected.value = id;
};
</script>

<template>
  <div
    ref="modalRef"
    class="modal fade"
    id="backDropModal"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <form class="modal-content" @submit.prevent="handleSaveCategory">
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Sub Categorías</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeModal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 mb-3">
              <label for="name" class="form-label">Nombre*</label>
              <input
                type="text"
                id="name"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="subCategoryForm.name"
                required
              />
            </div>
          </div>
          <div class="row g-2">
            <div class="col mb-0">
              <label for="description" class="form-label">Categoría*</label>
              <div class="input-group input-group-merge">
                <v-select
                  class="form-control p-0"
                  :options="categories"
                  :getOptionLabel="(data: Category) => data.name"
                  :reduce="(data: Category) => data.id"
                  :clearable="false"
                  v-model="subCategoryForm.category_id"
                  @option:selected="handleSelected"
                  @search="getCategoriesVueSelect"
                >
                  <template #search="{ attributes, events }">
                    <input
                      class="vs__search"
                      :required="!categorieSelected"
                      v-bind="attributes"
                      v-on="events"
                    />
                  </template>
                  <template #no-options="{ search }">
                    <span>No hay Resultados para {{ search }}</span>
                  </template>
                </v-select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            @click="closeModal"
          >
            Cerrar
          </button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
