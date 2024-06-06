<script lang="ts" setup>
import { DailyCash, DailyCashCreate } from "@/types";
import { ref } from "vue";
import { reactive } from "vue";
import { useDailyCash } from "@/composables/useDailyCash";

const { createDailyCash } = useDailyCash();
const props = defineProps<{
  reset: boolean;
}>();

const emits = defineEmits<{
  created: [DailyCash];
  reset: [void];
}>();

const initialDailyCash: DailyCashCreate = {
  id: 0,
  start_money: 0,
};

let dailyCashForm = reactive({ ...initialDailyCash });
const modalRef = ref<HTMLDivElement | null>(null);
const currentDate = ref();

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(dailyCashForm, { ...initialDailyCash });
  emits("reset");
};

const handleSaveDailyCash = () => {
  !dailyCashForm.id ? handleCreateDailyCash() : handleUpdateDailyCash();
};

const handleCreateDailyCash = async () => {
  const { success, data } = await createDailyCash({ ...dailyCashForm });
  if (success) {
    emits("created", data!);
    closeModal();
  }
};
const handleUpdateDailyCash = () => {};

const getCurrentDate = (date: Date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
};

currentDate.value = getCurrentDate(new Date());
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
      <form class="modal-content" @submit.prevent="handleSaveDailyCash">
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Caja Diaria</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeModal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-md-6 mb-3">
              <label for="name" class="form-label">Monto Inicial*</label>
              <input
                type="number"
                id="name"
                class="form-control"
                placeholder="Escriba el monto"
                v-model="dailyCashForm.start_money"
                step="0.01"
                min="0"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="name" class="form-label">Fecha de Apertura*</label>
              <input
                type="date"
                id="name"
                class="form-control"
                :value="currentDate"
                disabled
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
