<script lang="ts" setup>
import { watch, ref } from "vue";
import { ProductToSell } from "@/types";
import { shop } from "@/composables/shop";
import FormShop from "./FormShop.vue";
import { toastInfo } from "../utils/toast";

const { subTotal, products, total, deleteProductToForm } = shop();

const props = defineProps<{
  product: ProductToSell | null;
}>();

const emits = defineEmits<{
  reset: [void];
}>();

watch(
  () => props.product,
  (value) => {
    if (value) {
      const productExists = products.value.findIndex(
        (el) => el.id === value.id
      );
      if (productExists === -1) {
        products.value.push({ ...value, quantitySell: 1 });
      } else {
        products.value[productExists].quantitySell! += 1;
      }
    }
    emits("reset");
  }
);

const increaseQuantity = (product: ProductToSell) => {
  if (product.quantitySell === product.quantity) return;
  return (product.quantitySell! += 1);
};

const decreaseQuantity = (product: ProductToSell) => {
  if (product.quantitySell === 1) return;
  return (product.quantitySell! -= 1);
};

const enterDiscount = (product: ProductToSell) => {
  if (product.discount! > product.sale_price) {
    toastInfo("No puedes ingresar un descuento mayor al precio del producto");
    return (product.discount = 0);
  }
};
</script>

<template>
  <div class="col">
    <div class="card heigth-list">
      <h5 class="card-header py-2">Ventas</h5>
      <div class="card-body py-2 flex-grow-0">
        <span class="notificationRequest"
          ><strong>Cantidad de Productos : {{ products.length }}</strong></span
        >
        <div class="error"></div>
      </div>
      <div class="table-responsive heigth-content-products">
        <table class="table table-striped table-borderless border-bottom">
          <thead class="top-0 position-sticky">
            <tr>
              <th class="text-nowrap">Producto</th>
              <th class="text-nowrap text-center" width="130px">Cantidad</th>
              <th class="text-nowrap text-center" width="80px">Descuento</th>
              <th class="text-nowrap text-center" width="140px">Sub Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products">
              <td>
                <strong>{{ product.name }}</strong>
              </td>
              <td>
                <div
                  class="row row-cols-3 items-center d-flex align-items-center"
                >
                  <div class="col p-0">
                    <button
                      type="button"
                      class="btn btn-sm btn-icon btn-success"
                      @click="increaseQuantity(product)"
                    >
                      <i class="bx bx-plus"></i>
                    </button>
                  </div>
                  <div class="col p-0 text-center mt-3">
                    <h4 class="">{{ product.quantitySell }}</h4>
                  </div>
                  <div class="col p-0">
                    <button
                      type="button"
                      class="btn btn-sm btn-icon btn-danger"
                      @click="decreaseQuantity(product)"
                    >
                      <i class="bx bx-minus"></i>
                    </button>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <input
                    class="form-control"
                    type="number"
                    min="0"
                    step="0.01"
                    @input="enterDiscount(product)"
                    v-model="product.discount"
                  />
                </div>
              </td>
              <td>
                <div
                  class="d-flex justify-content-around align-items-center gap-2 w-100"
                >
                  <strong>{{ subTotal(product).toFixed(2) }}</strong>
                  <button
                    type="button"
                    class="btn btn-sm btn-icon btn-danger"
                    @click="deleteProductToForm(product.id)"
                  >
                    <i class="bx bx-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-header text-end py-1">Total: S/ {{ total }}</h4>
            <h4 class="card-header text-end py-1">
              IGV: S/ {{ (Number(total) * 0.18).toFixed(2) }}
            </h4>
          </div>
          <div class="col-12 d-flex justify-content-end">
            <button
              class="btn btn-success me-3"
              :disabled="products.length === 0"
            >
              <a
                class="dropdown-item"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#formShop"
                ><i class="bx bx-edit-alt me-1"></i>Generar Venta</a
              >
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <FormShop :total="Number(total)" />
</template>

<style scoped>
.heigth-list {
  min-height: 50rem;
  max-height: 50rem;
  overflow-y: auto;
  overflow-x: hidden;
}

.heigth-content-products {
  min-height: 34rem;
  max-height: 34rem;
  overflow-y: auto;
  overflow-x: hidden;
}

@media screen and (max-width: 768px) {
  .heigth-list {
    min-height: 20rem;
    max-height: 20rem;
  }
}
</style>
