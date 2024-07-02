<script lang="ts" setup>
import { Category } from "@/types";
import { useCategory } from "@/composables/useCategory";
import { ref, watch, reactive } from "vue";

const { createCategory, updateCategory, loading } = useCategory();
const props = defineProps<{
  category: Category | null;
  reset: boolean;
}>();

const emits = defineEmits<{
  created: [Category];
  updated: [Category];
  reset: [void];
}>();

watch(
  () => props.category,
  (value) => {
    categoryForm = { ...value! };
  }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const initialCategory: Category = {
  id: 0,
  name: "",
  description: "",
};

let categoryForm = reactive({ ...initialCategory });
const modalRef = ref<HTMLDivElement | null>(null);

const handleSaveCategory = () => {
  categoryForm.id ? handleEditCategory() : handleCreateCategory();
};

const handleCreateCategory = async () => {
  const { success, data } = await createCategory({ ...categoryForm });
  if (success) {
    closeModal();
    emits("created", data!);
    return;
  }
};

const handleEditCategory = async () => {
  const { success, data } = await updateCategory({ ...categoryForm });
  if (success) {
    closeModal();
    emits("updated", data!);
    return;
  }
};

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(categoryForm, { ...initialCategory });
  emits("reset");
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
                v-model="categoryForm.name"
                required
              />
            </div>
          </div>
          <div class="row g-2">
            <div class="col mb-0">
              <label for="description" class="form-label">Descripción</label>
              <input
                type="text"
                id="description"
                class="form-control"
                placeholder="Escriba la descripción"
                v-model="categoryForm.description"
              />
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
          <button type="submit" :disabled="loading" class="btn btn-primary">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
