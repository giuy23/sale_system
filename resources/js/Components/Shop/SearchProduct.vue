<script lang="ts" setup>
import { useSearchProduct } from "@/composables/useSearchProduct";
import { ProductToSell } from "@/types";
import { ref } from "vue";
import { shop } from "@/composables/shop";

const { products, loading, searchProduct } = useSearchProduct();
const { products: productToEval } = shop();
const emits = defineEmits<{
  productToBuy: [ProductToSell];
}>();

const noProducts = ref(true);
const productToSearch = ref();

const handleSearchProduct = async (e: Event) => {
  e.preventDefault();
  productToSearch.value.trim();

  const success = await searchProduct(productToSearch.value);
  if (success) {
    const lengthProduct = products.value.length;
    if (lengthProduct === 1) {
      goToFacture(products.value[0]);
    } else {
      noProducts.value = false;
    }
  }
};

const goToFacture = (product: ProductToSell) => {
  const findIndex = productToEval.value.findIndex(
    ({ id }) => product.id === id
  );
  if (findIndex === -1) {
    emits("productToBuy", product!);
    productToSearch.value = "";
    return;
  }
  const productEval = productToEval.value[findIndex];
  if (!(productEval.quantitySell === product.quantity)) {
    emits("productToBuy", product!);
    productToSearch.value = "";
  }
};

const productsViewInList = () => {};
</script>

<template>
  <div class="col">
    <div class="col-12">
      <div class="row mx-0 mb-3">
        <div class="card col-12 d-flex flex-wrap flex-row p-2 gap-2">
          <span class="fw-bold block mr-12 align-content-center">Buscar</span>
          <div class="d-flex align-items-center">
            <i class="bx bx-search fs-4 lh-0"></i>
            <input
              @keyup.enter="handleSearchProduct"
              type="text"
              class="form-control border-0 shadow-none ms-2"
              placeholder="Buscar por Nombre..."
              aria-label="Buscar por Nombre..."
              v-model="productToSearch"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="row mx-0 mb-3">
        <div class="card col-12 d-flex flex-wrap flex-row p-2 gap-2 vh-75">
          <template v-if="noProducts">
            <div></div>
          </template>
          <template v-else>
            <div class="row row-cols-1 row-cols-md-4 g-4 mb-5">
              <div
                v-for="product in products"
                class="col"
                @click="goToFacture(product)"
              >
                <div class="card h-100">
                  <img
                    class="card-img-top"
                    :src="`storage/images/products/${product.image}`"
                    alt="Card image cap"
                  />
                  <div class="p-2">
                    <!-- <h5 class="card-title">Card title</h5> -->
                    <p class="card-text">
                      {{ product.name }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
