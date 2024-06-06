<script lang="ts" setup>
import { Provider } from "@/types/index";
import { reactive, ref } from "vue";
import { useProvider } from "@/composables/useProvider";
import { watch } from "vue";

const { createProvider, updateProvider } = useProvider();

const props = defineProps<{
  provider: Provider | null;
  reset: Boolean;
}>();

const emits = defineEmits<{
  created: [Provider];
  updated: [Provider];
  reset: [void];
}>();

watch(
  () => props.provider,
  (value) => {
    providerForm = { ...value! };
  }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const initialProvider: Provider = {
  id: 0,
  name: "",
  document_number: null,
  name_company: "",
  cellphone: 0,
  // image: "",
};

const modalRef = ref(false);
const image = ref();
let providerForm = reactive({ ...initialProvider });

const handleSaveProvider = () => {
  providerForm.id ? handleEditProvider() : handleCreateProvider();
};

const handleInputImage = (e: Event) => {
  const file = (e.target as HTMLInputElement).files![0];
  image.value = file;
};

const handleCreateProvider = async () => {
  const { success, data } = await createProvider({
    ...providerForm,
    image: image.value,
  });
  if (success) {
    emits("created", data);
    closeModal();
  }
};
const handleEditProvider = async () => {

  const { success, data } = await updateProvider({
    ...providerForm,
    image: image.value,
  });
  if (success) {
    emits("updated", data);
    closeModal();
  }
};

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(providerForm, { ...initialProvider });
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
      <form
        class="modal-content"
        @submit.prevent="handleSaveProvider"
        enctype="multipart/form-data"
      >
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Proveedores</h5>
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
                v-model="providerForm.name"
                required
              />
            </div>
            <div class="col-12 mb-3">
              <label for="name_company" class="form-label"
              >Nombre de la compañia</label
              >
              <input
              type="text"
              id="name_company"
              class="form-control"
              placeholder="Escriba el nombre"
              v-model="providerForm.name_company"
              required
              />
            </div>
            <div class="col-12 mb-3">
              <label for="cellphone" class="form-label">N° Celular</label>
              <input
              type="text"
                id="cellphone"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="providerForm.cellphone"
                required
                />
              </div>
            </div>
            <div class="col-12 mb-3">
              <label for="dni" class="form-label">DNI</label>
              <input
                type="text"
                id="dni"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="providerForm.document_number"
              />
            </div>
          <div class="row g-2">
            <div class="col-12 mb-3">
              <label for="image" class="form-label">Imagen</label>
              <input
                type="file"
                accept="image/*"
                id="image"
                class="form-control"
                placeholder="Escriba el nombre"
                @input="handleInputImage"
                required
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
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
