<script lang="ts" setup>
import { GetDataWithParams, Provider } from "@/types";
import { ref, watch } from "vue";
import Layout from "./Partials/Layout.vue";
import FormProvider from "@/Components/Providers/FormProvider.vue";
import ProviderList from "@/Components/Providers/ProviderList.vue";
import Pagination from "@/Components/common/Pagination.vue";
import SearchData from "@/Components/common/SearchData.vue";
import { useProvider } from "@/composables/useProvider";
import { toastSuccess, confirmDelete } from "@/Components/utils/toast";

const modalIsOpen = ref(false);
const providers = ref<Provider[]>();
const provider = ref<Provider | null>(null);
const links = ref<Object>();

const { deleteProvider } = useProvider();
const props = defineProps<{
  providers: GetDataWithParams;
}>();

watch(
  props.providers,
  (value) => {
    providers.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const openModal = () => (modalIsOpen.value = true);
const handleResetModal = () => (modalIsOpen.value = false);

const showSearchedData = (data: GetDataWithParams) => {
  providers.value = data.data;
  links.value = data.meta.links;
};

const handleCreated = async (data: Provider) => {
  toastSuccess("Proveedor(a) creado con éxito");
  toastSuccess("Proveedor(a) creado con éxito");
  providers.value!.push(data);
};
const handleUpdated = async (data: Provider) => {
  const findIndex = providers.value!.findIndex(({ id }) => id === data.id);
  console.log(findIndex);

  if (findIndex !== -1) {
    toastSuccess("Proveedor(a) actualizado con éxito");
    providers.value![findIndex] = data;
  }
};

const editProvider = async (data: Provider) => {
  provider.value = data;
};

const handleDeleteProvider = async (id: number) => {
  const isConfirmed = await confirmDelete(
    "Al borrar a un proveedor los productos que le pertenezcan a este pasarán a estar inactivos. "
  );

  if (isConfirmed) {
    const { success } = await deleteProvider(id);
    if (success) {
      toastSuccess("Proveedor(a) eliminada con éxito");
      const index = providers.value!.findIndex((el) => el.id === id);
      providers.value!.splice(index, 1);
    }
  }

};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Proveedores
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
          Crear Proveedor
        </button>
      </div>
    </div>

    <SearchData
      table="provider"
      :search-by="['name']"
      @data-searched="showSearchedData"
    />

    <FormProvider
      :provider="provider"
      :reset="modalIsOpen"
      @created="handleCreated"
      @updated="handleUpdated"
      @reset="handleResetModal"
    />

    <ProviderList
      :providers="providers!"
      @edit="editProvider"
      @delete="handleDeleteProvider"
    />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
