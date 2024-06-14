<script lang="ts" setup>
import { Client } from "@/types";
import { reactive } from "vue";
import { watch, ref } from "vue";
import { useClient } from "@/composables/useClient";

const { createClient, updateClient } = useClient();
const props = defineProps<{
  client?: Client | null;
  reset?: Boolean;
}>();

const emits = defineEmits<{
  created: [Client];
  updated: [Client];
  reset: [void];
}>();

watch(
  () => props.client,
  (value) => {
    clientForm = { ...value! };
  }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const initialClient: Client = {
  id: 0,
  full_name: "",
  document_number: 0,
  cell_phone: null,
};

let clientForm = reactive({ ...initialClient });
const modalRefClient = ref<HTMLInputElement | null>(null);

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRefClient.value!);
  modalInstance?.hide();
  Object.assign(clientForm, { ...initialClient });
  emits("reset");
};

const handleSaveClient = () => {
  clientForm.id ? handleUpdateClient() : handleCreateClient();
};

const handleCreateClient = async () => {
  const { success, data } = await createClient({ ...clientForm });
  if (success) {
    emits("created", data!);
    closeModal();
  }
};
const handleUpdateClient = async () => {
  const { success, data } = await updateClient({ ...clientForm });
  if (success) {
    emits("updated", data!);
    closeModal();
  }
};
</script>

<template>
  <div
    ref="modalRefClient"
    class="modal fade"
    id="formClientModal"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <form
        class="modal-content"
        @submit.prevent="handleSaveClient"
        enctype="multipart/form-data"
      >
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Clientes</h5>
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
              <label for="full_name" class="form-label">Nombre Completo*</label>
              <input
                type="text"
                id="full_name"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="clientForm.full_name"
                required
              />
            </div>
            <div class="col-12 mb-3">
              <label for="document_number" class="form-label">DNI*</label>
              <input
                type="number"
                id="document_number"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="clientForm.document_number"
                min="0"
                required
              />
            </div>
            <div class="col-12 mb-3">
              <label for="cell_phone" class="form-label">N° Celular</label>
              <input
                type="number"
                id="cell_phone"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="clientForm.cell_phone"
                min="0"
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
