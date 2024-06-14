<script lang="ts" setup>
import { ref } from "vue";
import Layout from "./Partials/Layout.vue";
import SearchProduct from "@/Components/Shop/SearchProduct.vue";
import ListProductShop from "@/Components/Shop/ListProductShop.vue";
import { ProductToSell } from "@/types";

const product = ref<ProductToSell | null>(null);

const productToBuy = (data: ProductToSell) => {
  product.value = data;
};

const resetValueProduct = () => (product.value = null);
</script>

<template>
  <Layout>
    <div class="row row-cols-1 row-cols-md-2">
      <SearchProduct @product-to-buy="productToBuy" />

      <ListProductShop :product="product!" @reset="resetValueProduct" />
    </div>
  </Layout>
</template>

<style scoped></style>
