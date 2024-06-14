<script lang="ts" setup>
import { watch, ref } from "vue";
import { ProductToSell } from "@/types";
import { shop } from "@/composables/shop";
import FormShop from "./FormShop.vue";

const { subTotal, products, total } = shop();

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
</script>

<template>
  <div class="col">
    <div class="card">
      <h5 class="card-header">Ventas</h5>
      <div class="card-body">
        <span class="notificationRequest"
          ><strong>Cantidad de Productos : {{ products.length }}</strong></span
        >
        <div class="error"></div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-borderless border-bottom">
          <thead>
            <tr>
              <th class="text-nowrap">Producto</th>
              <th class="text-nowrap text-center" width="150px">Cantidad</th>
              <th class="text-nowrap text-center" width="100px">Descuento</th>
              <th class="text-nowrap text-center">Sub Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products">
              <td>
                <strong>{{ product.name }}</strong>
              </td>
              <td>
                <div class="row row-cols-3 items-center">
                  <div class="col p-0">
                    <button
                      type="button"
                      class="btn btn-icon btn-primary"
                      @click="increaseQuantity(product)"
                    >
                      <span class="tf-icons bx bx-pie-chart-alt"></span>
                    </button>
                  </div>
                  <div class="col p-0 text-center mt-2">
                    <h4 class="">{{ product.quantitySell }}</h4>
                  </div>
                  <div class="col p-0">
                    <button
                      type="button"
                      class="btn btn-icon btn-primary"
                      @click="decreaseQuantity(product)"
                    >
                      <span class="tf-icons bx bx-pie-chart-alt"></span>
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
                    v-model="product.discount"
                  />
                </div>
              </td>
              <td>
                <strong>{{ subTotal(product) }}</strong>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-header text-end">Total: S/ {{ total }}</h4>
          </div>
          <div class="col-12 d-flex justify-content-end">
            <button class="btn btn-success me-3" :disabled="Number(total) === 0">
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

<style scoped></style>
