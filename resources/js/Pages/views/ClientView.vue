<script lang="ts" setup>
import { ref } from "vue";
import Layout from "./Partials/Layout.vue";
import SearchData from "@/Components/common/SearchData.vue";
import { Client, GetDataWithParams } from "@/types";
import FormClient from "@/Components/Clients/FormClient.vue";
import ClientList from "@/Components/Clients/ClientList.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { watch } from "vue";
import { useClient } from "@/composables/useClient";
import { confirmDelete, toastSuccess } from "@/Components/utils/toast";

const { deleteClient } = useClient();
const modalIsOpen = ref(false);
const links = ref();
const client = ref<Client | null>(null);
const clients = ref<Client[]>([]);
const props = defineProps<{
  clients: GetDataWithParams;
}>();

watch(
  props.clients,
  (value) => {
    console.log(value);

    clients.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const openModal = () => (modalIsOpen.value = true);
const handleResetModal = () => (modalIsOpen.value = false);

const showSearchedData = (data: GetDataWithParams) => {
  clients.value = data.data;
  links.value = data.meta.links;
};

const handleCreated = (data: Client) => {
  toastSuccess("Cliente creado con éxito");
  clients.value.push(data!);
};

const handleUpdated = (data: Client) => {
  toastSuccess("Cliente actualizado con éxito");
  const findIndex = clients.value.findIndex(({ id }) => id === data.id);
  if (findIndex !== -1) {
    clients.value[findIndex] = data!;
  }
};

const editClient = (data: Client) => {
  client.value = data;
};

const handleDeleteClient = async (id: number) => {
  const isConfirmed = confirmDelete();
  if (isConfirmed === true) {
    const { success } = await deleteClient(id);

    if (success === true) {
      toastSuccess("Cliente eliminado con éxito");
      const findIndex = clients.value.findIndex((el) => el.id === id);
      if (findIndex !== -1) {
        clients.value.splice(findIndex, 1);
      }
    }
  }
};
</script>

<template>
  <Layout>
    <div class="row mb-3">
      <div class="col-12 col-md-10">
        <h4 class="fw-bold py-1 py-md-3">
          <span class="text-muted fw-light"> / </span> Clientes
        </h4>
      </div>
      <div class="col-12 col-md-2">
        <button
          type="button"
          class="btn btn-primary"
          @click="openModal"
          data-bs-toggle="modal"
          data-bs-target="#formClientModal"
        >
          Crear Cliente
        </button>
      </div>
    </div>

    <SearchData
      table="client"
      :search-by="['id']"
      @data-searched="showSearchedData"
    />

    <FormClient
      :client="client!"
      :reset="modalIsOpen"
      @created="handleCreated"
      @updated="handleUpdated"
      @reset="handleResetModal"
    />

    <ClientList
      :clients="clients!"
      @edit="editClient"
      @delete="handleDeleteClient"
    />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
