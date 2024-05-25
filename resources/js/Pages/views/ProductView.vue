<script lang="ts" setup>
import Layout from './Partials/Layout.vue';


const props = defineProps<{
  products: Array<T>,
}>()

</script>

<template>
  <Layout>
    <div>
    <h1>Products</h1>
    <ul>
      <li v-for="product in products" :key="product.id">
        {{ product.name }} - {{ product.price }}
      </li>
    </ul>
  </div>
  </Layout>
</template>

<style scoped>

</style>
