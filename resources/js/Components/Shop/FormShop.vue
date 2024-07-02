<script lang="ts" setup>
import { ref, computed } from "vue";
import { ClientToSell, Client } from "@/types";
import { shop } from "@/composables/shop";
import FormClient from "../Clients/FormClient.vue";
import db from "just-debounce";
import { toastInfo, toastSuccess } from "../utils/toast";
import { useSearchToSell } from "@/composables/useSearchToSell";

const modalRefShop = ref<HTMLDivElement | null>(null);

const {
  client,
  backMoney,
  debtCustomer,
  saleType,
  customerPayment,
  descriptionDebt,
  loading,
  saveSale,
  resetFormValues,
} = shop();

const { searchClient, clients } = useSearchToSell();
const props = defineProps<{
  total: number;
}>();

const getClients = db(async (value: string | number) => {
  await searchClient(value);
}, 450);

const getInitialClient = db(async () => {
  const { success } = await searchClient("00000000");
  if (success) {
    client.value = clients!.value![0].id;
  }
}, 450);

getInitialClient();

const handleSelected = ({ id }: ClientToSell) => {
  client.value = id;
};

const getClientsVueSelect = async (data: string | number) => {
  let search;
  typeof data === "number" ? (search = data) : (search = data.trim());
  if (!search) return;

  await getClients(search);
};

const handleCreatedClient = (data: Client) => {
  clients.value!.push(data);
  client.value = data.id;
};

const handleCreateShop = async () => {
  const { success, msg } = await saveSale();
  if (success === false && msg !== "") return toastInfo(msg);
  if (success === true) {
    closeModal();
    resetFormValues();
    toastSuccess(msg);
  }
};

const handleOpenModalShop = () => {
  const clientModal = new bootstrap.Modal(modalRefShop.value);
  clientModal.show();
};
const closeModal = () => {
  const clientModal = bootstrap.Modal.getInstance(modalRefShop.value);
  clientModal.hide();
};

const textSaleType = computed(() => {
  if (saleType.value === 1) {
    return {
      color: "text-success",
      text: "Normal",
    };
  } else if (saleType.value === 3) {
    return {
      color: "text-info",
      text: "Crédito",
    };
  }
});
</script>

<template>
  <div
    ref="modalRefShop"
    class="modal fade"
    id="formShop"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <form class="modal-content" @submit.prevent="handleCreateShop">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title w-75" id="backDropModalTitle">
            Generar Venta
          </h5>
          <button class="btn btn-success btn-xs">
            <a
              class="dropdown-item p-2"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#formClientModal"
              ><i class="bx bx-edit-alt me-1"></i>Crear Cliente</a
            >
          </button>
          <button
            type="button"
            class="btn-close"
            @click="closeModal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="row mb-3 text-center">
              <label class="form-label fw-bolder"
                >Tipo de venta
                <strong :class="textSaleType?.color">{{
                  textSaleType?.text
                }}</strong>
              </label>
              <div class="col-6 d-flex justify-content-center">
                <div class="btn btn-primary" @click="saleType = 1">Venta</div>
              </div>
              <div class="col-6 d-flex justify-content-center">
                <div class="btn btn-info" @click="saleType = 3">Crédito</div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <label for="start_money" class="form-label">Cliente*</label>
              <div class="input-group input-group-merge">
                <v-select
                  class="form-control p-0"
                  :options="clients"
                  :getOptionLabel="(data: ClientToSell) => data.full_name + ' - ' + data.document_number"
                  :reduce="(data: ClientToSell) => data.id"
                  :clearable="false"
                  v-model="client"
                  @option:selected="handleSelected"
                  @search="getClientsVueSelect"
                >
                  <template #search="{ attributes, events }">
                    <input
                      class="vs__search"
                      :required="!client"
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
            <div v-if="saleType === 3" class="col-12 mb-3">
              <label for="start_money" class="form-label">Descripción</label>
              <input
                type="text"
                class="form-control"
                placeholder="Indique una descripción"
                v-model="descriptionDebt"
              />
            </div>
            <div class="row">
              <div class="col-12">
                <div class="row px-1 justify-content-end">
                  <div class="col-4">
                    <label
                      v-if="saleType === 1"
                      for="customer_payment"
                      class="form-label"
                      >Pago del cliente</label
                    >
                    <label v-else for="customer_payment" class="form-label"
                      >Pago Anticipio del cliente</label
                    >
                    <input
                      type="number"
                      class="form-control mt-1"
                      id="customer_payment"
                      min="0"
                      v-model="customerPayment"
                    />
                  </div>
                </div>
              </div>

              <div class="col-12">
                <h4 class="card-header text-end py-1 px-1">
                  Total: S/ {{ total }}
                </h4>
              </div>
              <div class="col-12">
                <h4
                  v-if="saleType === 1"
                  class="card-header text-end py-1 px-1"
                >
                  Vuelto: S/ {{ backMoney(total, customerPayment) }}
                </h4>
                <h4 v-else class="card-header text-end py-1 px-1">
                  Deuda: S/ {{ debtCustomer(total, customerPayment) }}
                </h4>
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
          <button type="submit" :disabled="loading" class="btn btn-primary">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>

  <FormClient @created="handleCreatedClient" @reset="handleOpenModalShop" />
</template>

<style scoped></style>
