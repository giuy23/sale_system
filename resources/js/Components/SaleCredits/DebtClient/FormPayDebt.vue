<script lang="ts" setup>
import { confirmAction, toastInfo } from "@/Components/utils/toast";
import { watch, ref, reactive } from "vue";
import { useSale } from "@/composables/useSale";

const { payDebtForTheSale } = useSale();
const props = defineProps<{
  debt: { id: number; amount: number };
}>();

watch(props.debt, (value) => {
  console.log(value);

  Object.assign(debtForm, value);
});

const emits = defineEmits<{
  reset: [void];
  update: [{ id: number; amount: number }];
}>();

const initialDebt = {
  id: 0,
  amount: 0,
};
const modalRef = ref();
let debtForm = reactive({ ...initialDebt });

const closeModal = (reset: boolean) => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  if (reset) {
    Object.assign(debtForm, { ...initialDebt });
    emits("reset");
  }
};

const handleSavePayment = async () => {
  if (debtForm.amount > props.debt.amount)
    return toastInfo("El pago debe ser menor a la deuda");
  if (debtForm.amount < 0)
    return toastInfo("El pago debe ser un número válido");

  debtForm.amount = Number(debtForm.amount.toFixed(2));
  closeModal(false);

  const isConfirmed = await confirmAction(
    `El monto ingresado es de: S/ ${debtForm.amount}`
  );

  if (isConfirmed) {
    const { success } = await payDebtForTheSale(debtForm);
    if (success) {
      emits("update", { ...debtForm });
    }
  } else {
    closeModal(true);
  }
};
</script>

<template>
  <div
    ref="modalRef"
    class="modal fade"
    id="DebtCientForm"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <form
        class="modal-content"
        @submit.prevent="handleSavePayment"
        enctype="multipart/form-data"
      >
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Monto A Pagar</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeModal(true)"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 mb-3">
              <label for="total" class="form-label">Monto*</label>
              <input
                type="number"
                id="total"
                min="0"
                step="0.01"
                class="form-control"
                placeholder="Escriba el monto"
                v-model="debtForm.amount"
                required
              />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            @click="closeModal(true)"
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
