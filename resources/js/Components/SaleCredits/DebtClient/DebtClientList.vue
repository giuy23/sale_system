<script lang="ts" setup>
import { confirmAction, redirectToast } from "@/Components/utils/toast";
import { CreditSaleClient, ClientTotalDebt } from "@/types/index";
import FormPayDebt from "./FormPayDebt.vue";
import { ref } from "vue";
import { useSale } from "@/composables/useSale";
import { toastSuccess } from "@/Components/utils/toast";

const { payAllDebts } = useSale();
const saleCredits = ref<CreditSaleClient[]>([]);
const client = ref<ClientTotalDebt>();
const debtOneCredit = ref({
  id: 0,
  amount: 0,
});

const props = defineProps<{
  saleCredits: CreditSaleClient[];
  client: ClientTotalDebt;
}>();

saleCredits.value = props.saleCredits;
client.value = props.client;

const handlePayAllDebts = async () => {
  const isConfirmed = await confirmAction(
    `El monto total de todas las deudas es de: S/ ${client.value!.total_debt}`
  );
  let creditSaleIds = {};

  creditSaleIds = saleCredits.value.map((el) => ({
    ...creditSaleIds,
    id: el.id,
  }));

  console.log(saleCredits.value);

  if (isConfirmed) {
    const { success } = await payAllDebts(creditSaleIds);
    if (success) {
      redirectToast("Pago Total de Deudas Exitosa", "saleCredit");
    }
  }
};

const createPayment = (credit: CreditSaleClient) => {
  debtOneCredit.value.id = credit.id;
  debtOneCredit.value.amount = credit.remaining_amount;
};

const resetValuesDebt = () => {
  debtOneCredit.value.amount = 0;
  debtOneCredit.value.id = 0;
};

const updateAmountToSaleCredits = (data: { id: number; amount: number }) => {
  const findIndex = saleCredits.value.findIndex((el) => el.id === data.id);
  if (findIndex !== -1) {
    const sale = saleCredits.value[findIndex];
    const newAmount = Number((sale.remaining_amount - data.amount).toFixed(2));

    newAmount === 0
      ? saleCredits.value.splice(findIndex, 1)
      : (saleCredits.value[findIndex].remaining_amount = newAmount);

    updateAmountTotalDebt(data.amount);
    toastSuccess("Monto ingresado con éxito");
  }
};

const updateAmountTotalDebt = (amount: number) => {
  client.value!.total_debt! = Number(
    (client.value!.total_debt! - amount).toFixed(2)
  );
  console.log(client.value!.total_debt!);
};

console.log(saleCredits.value);
</script>

<template>
  <div class="card">
    <div class="row">
      <div class="col-12 col-md-7">
        <h5 class="card-header">Cliente: {{ client!.full_name }}</h5>
      </div>
      <div class="col-12 col-md-5">
        <div class="row">
          <div class="col-6">
            <h5 class="card-header">
              Monto total de Deuda: {{ client!.total_debt }}
            </h5>
          </div>
          <div class="col-6 align-content-center">
            <button class="btn btn-info" @click="handlePayAllDebts">
              Pagar Todo el Monto
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Fecha de la Venta Realizada</th>
            <th>Monto Total</th>
            <th>Monto Restante</th>
            <th>Descripcion</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="credit in saleCredits">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ credit.created_at }}</strong>
            </td>
            <td>{{ credit.amount_payable }}</td>
            <td>{{ credit.remaining_amount }}</td>
            <td>{{ credit.description }}</td>
            <td>
              <div class="dropdown">
                <button
                  type="button"
                  class="btn p-0 dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                >
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a
                    class="dropdown-item"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#DebtCientForm"
                    @click="createPayment(credit)"
                    ><i class="bx bx-edit-alt me-1"></i> Pagar Deuda</a
                  >
                  <a
                    class="dropdown-item"
                    type="button"
                    target="_blank"
                    :href="route('sale.detailSale', credit.sale_id)"
                    ><i class="bx bx-edit-alt me-1"></i> Ver Productos
                    Comprados</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!saleCredits.length">
            <td colspan="6" class="text-center">
              <h5 class="my-2 lead fw-700">No hay Datos</h5>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <FormPayDebt
    :debt="debtOneCredit!"
    @reset="resetValuesDebt"
    @update="updateAmountToSaleCredits"
  />
</template>

<style scoped></style>
