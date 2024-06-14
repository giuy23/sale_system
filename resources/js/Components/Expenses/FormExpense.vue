<script lang="ts" setup>
import { watch, reactive, ref } from "vue";
import { Expense } from "@/types/index";
import { useExpense } from "@/composables/useExpense";

const { createExpense, updateExpense } = useExpense();
const props = defineProps<{
  idExpense?: number;
  expense: Expense;
}>();

const emits = defineEmits<{
  reset: [void];
  updated: [Expense];
}>();

watch(
  () => props.idExpense,
  (value) => {
    expenseForm.daily_cash_id = value!;
  }
);

watch(
  () => props.expense,
  (value) => {
    expenseForm = { ...value! };
  }
);

const initialExpense: Expense = {
  id: 0,
  amount: 0,
  description: "",
  daily_cash_id: 0,
};

let expenseForm = reactive({ ...initialExpense });
const modalRef = ref<HTMLDivElement | null>(null);

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(expenseForm, { ...initialExpense });
  emits("reset");
};

const handleSaveExpense = () => {
  expenseForm.id ? handleEditExpense() : handleCreateExpense();
};

const handleCreateExpense = async () => {
  const { success, data } = await createExpense({ ...expenseForm });
  if (success) {
    closeModal();
  }
};
const handleEditExpense = async () => {
  const { success, data } = await updateExpense({ ...expenseForm });
  if (success) {
    emits("updated", data!);
    closeModal();
  }
};
</script>

<template>
  <div
    ref="modalRef"
    class="modal fade"
    id="createExpense"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog modal-sm">
      <form class="modal-content" @submit.prevent="handleSaveExpense">
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Gasto</h5>
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
              <label for="amount" class="form-label">Monto*</label>
              <input
                type="number"
                id="amount"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="expenseForm.amount"
                min="0"
                step="0.01"
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
                v-model="expenseForm.description"
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
