<script lang="ts" setup>
import { GetDataWithParams, Product } from "@/types";
import Layout from "./Partials/Layout.vue";
import FormProduct from "@/Components/Products/FormProduct.vue";
import ProductList from "@/Components/Products/ProductList.vue";
import Pagination from "@/Components/common/Pagination.vue";
import SearchData from "@/Components/common/SearchData.vue";
import { useProduct } from "@/composables/useProduct";
import { ref, watch } from "vue";
import {
  confirmAction,
  confirmDelete,
  toastSuccess,
} from "@/Components/utils/toast";

const modalIsOpen = ref(false);
const products = ref<Product[]>();
const product = ref<Product | null>(null);
const links = ref();

const { deleteProduct, changeState } = useProduct();
const props = defineProps<{
  products: GetDataWithParams;
}>();

watch(
  props.products,
  (value) => {
    products.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const openModal = () => (modalIsOpen.value = true);
const closeModal = () => {
  modalIsOpen.value = false;
  product.value = null;
};

const showSearchedData = (data: GetDataWithParams) => {
  products.value = data.data;
  links.value = data.meta.links;
};

const handleCreated = (data: Product) => {
  toastSuccess("Producto creado con éxito.");
  products.value!.push(data);
};

const handleUpdated = (data: Product) => {
  const findIndex = products.value!.findIndex(({ id }) => id === data.id);
  if (findIndex !== -1) {
    toastSuccess("Producto actualizado con éxito.");
    products.value![findIndex] = data;
  }
};

const editProduct = async (data: Product) => {
  product.value = data;
};

const handleChangeState = async (id: number, state: boolean) => {
  const isConfirmed = await confirmAction();

  if (isConfirmed === true) {
    const { success, data } = await changeState(id, state);
    if (success) {
      toastSuccess("Producto actualizado con éxito");
      const finIndex = products.value?.findIndex((el) => el.id === id);
      if (finIndex !== -1) {
        products.value![finIndex!] = data!;
      }
    }
  }
};

const handleDeleteProduct = async (id: number) => {
  const isConfirmed = await confirmDelete();
  if (isConfirmed) {
    const { success } = await deleteProduct(id);
    if (success) {
      toastSuccess("Producto eliminado con éxito.");
      const index = products.value!.findIndex((el) => el.id === id);
      products.value!.splice(index, 1);
    }
  }
};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Productos
        </h4>
      </div>
      <div class="col-12 col-md-2">
        <button
          type="button"
          class="btn btn-primary"
          @click="openModal"
          data-bs-toggle="modal"
          data-bs-target="#backDropModal"
        >
          Crear Producto
        </button>
      </div>
    </div>

    <SearchData
      table="product"
      :search-by="['name']"
      @data-searched="showSearchedData"
    />

    <FormProduct
      :product="product"
      :reset="modalIsOpen"
      @created="handleCreated"
      @updated="handleUpdated"
      @reset="closeModal"
    />

    <ProductList
      :products="products!"
      @edit="editProduct"
      @delete="handleDeleteProduct"
      @change-state="handleChangeState"
    />

    <Pagination :links="links!" />
  </Layout>
</template>

<style scoped></style>
