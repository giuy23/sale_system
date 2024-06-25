<script lang="ts" setup>
import { SaleDetails } from "@/types";
import { ref, watch } from "vue";
import { useCancelSale } from "@/composables/useCancelSale";
import { confirmAction } from "@/Components/utils/toast";

const { saleDetails, subTotal, amountToReturn, confirmCancelSale } =
  useCancelSale();

const props = defineProps<{
  saleDetails: SaleDetails[];
}>();

watch(
  props.saleDetails,
  (value) => {
    saleDetails.value = value.map((el) => ({ ...el, quantitySell: 0 }));
  },
  { immediate: true }
);

console.log(saleDetails.value);

const increaseQuantity = (sale: SaleDetails) => {
  if (sale.quantitySell === 0) return;
  return --sale.quantitySell!;
};

const decreaseQuantity = (sale: SaleDetails) => {
  if (sale.quantitySell === sale.quantity) return;
  return ++sale.quantitySell!;
};

const handleConfirmCancelSale = async () => {
  const isConfirmed = await confirmAction();
  if (isConfirmed) {
    const { data, success } = await confirmCancelSale();
  }
};
</script>

<template>
  <div class="card">
    <h5 class="card-header">Striped rows</h5>
    <h5 class="card-header">Monto a Devolver: {{ amountToReturn }}</h5>
    <button class="btn btn-primary" @click="handleConfirmCancelSale">
      Confirmar Anulación
    </button>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Descuento</th>
            <th>Cantidad Devuelta</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="sale in saleDetails">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ sale.product_name }}</strong>
            </td>
            <td>S/ {{ sale.price }}</td>
            <td>S/ {{ sale.discount }}</td>
            <td class="d-flex">
              <button
                type="button"
                class="btn btn-sm btn-icon btn-danger p-1"
                @click="decreaseQuantity(sale)"
              >
                <i class="bx bx-minus"></i>
              </button>
              <h4 class="card-header p-1 ps-2">
                {{ sale.quantity - sale.quantitySell! }}
              </h4>
              <button
                type="button"
                class="btn btn-sm btn-icon btn-info p-1"
                @click="increaseQuantity(sale)"
              >
                <i class="bx bx-plus"></i>
              </button>
            </td>
            <td>S/ {{ subTotal(sale) }}</td>
            <!-- <td>
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
                    v-if="sale.state === 1"
                    class="dropdown-item"
                    type="button"
                    :href="route('sale.detail', sale.id)"
                    ><i class="bx bx-edit-alt me-1"></i> Anular Venta</a
                  >
                  <a
                    class="dropdown-item"
                    type="button"
                    @click="route('sale.detail', sale.id)"
                    ><i class="bx bx-edit-alt me-1"></i> Ver Detalles</a
                  >
                </div>
              </div>
            </td> -->
          </tr>
          <tr v-if="!saleDetails.length">
            <td colspan="6" class="text-center">
              <h5 class="my-2 lead fw-700">No hay Datos</h5>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped></style>
