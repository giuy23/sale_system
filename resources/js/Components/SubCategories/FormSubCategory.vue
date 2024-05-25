<script lang="ts" setup>
import { Category, SubCategory } from '@/types';
import { ref, reactive, watch } from 'vue';
import { useSearch } from "@/composables/useSearch";
const { getSearchData } = useSearch();


const props = defineProps<{
  subCategory: SubCategory | null;
  reset: boolean;
}>()

const emits = defineEmits<{
  reset: [void];
}>()

watch(
  () => props.subCategory,
  (value) => {
    subCategoryForm = { ...value! };
  }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const initialSubCategory: SubCategory = {
  id: 0,
  name: "",
  category_id: 0,
}

let subCategoryForm = reactive({...initialSubCategory});
const modalRef = ref<HTMLDivElement | null>(null);

const handleSaveCategory = () => {
    subCategoryForm.id ? handleEditSubCategory() : handleCreateSubCategory();
};

const handleCreateSubCategory = () => {

}

const handleEditSubCategory = () => {}

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(subCategoryForm, { ...initialSubCategory });
  emits("reset");
};

const categories = ref<Category[]>();

const getCategories = async(value?: string) => {
  const {success, data} = await getSearchData("category", value);
  if (success) {
    categories.value = data.data;
    console.log(categories.value);

  }
}
getCategories()

const getCategoriesVueSelect = (data:string) => {
  const search = data.trim()

  if (!search) return
  getCategories(search)

}
</script>

<template>
  {{ categories?.map((categories) => categories.name) }}

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
          <h5 class="modal-title" id="backDropModalTitle">Categorías</h5>
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

              <v-select
              :options="categories"
              :getOptionLabel="(data: Category) => data.name"
              v-model="subCategoryForm.category_id"
              :reduce="(data: Category) => data.id"
              @search="getCategoriesVueSelect"
              >
              <!-- <template v-slot:option="category">
                <div>
                  No hay resultados
                </div>
              </template> -->
              <template #no-options="{ search }">
                <!-- <small class="block dark:text-black"> {{ search.length ? 'No hay resultado para' :'Porfavor busca y selecciona la ruta' }}
                  <span class="text-primary fw-bolder fs-5"> {{ search }}</span>
                </small> -->
                <span>No hay Resultados</span>
              </template>

              </v-select>

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

<style scoped>

</style>
