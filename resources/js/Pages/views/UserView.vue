<script lang="ts" setup>
import { GetDataWithParams, UserType } from "@/types";
import { watch, ref } from "vue";
import Layout from "./Partials/Layout.vue";
import Pagination from "@/Components/common/Pagination.vue";
import UserList from "@/Components/Users/UserList.vue";
import { useUser } from "@/composables/useUser";
import FormUser from "@/Components/Users/FormUser.vue";
import SearchData from "@/Components/common/SearchData.vue";
import { confirmDelete, toastSuccess } from "@/Components/utils/toast";

const users = ref<UserType[]>();
const user = ref<UserType | null>(null);
const links = ref<Object>();
const modalIsOpen = ref(false);

const { deleteUser } = useUser();
const props = defineProps<{
  users: GetDataWithParams;
}>();

watch(
  props.users,
  (value) => {
    users.value = value.data;
    links.value = value.meta.links;
  },
  { immediate: true }
);

const openModal = () => (modalIsOpen.value = true);
const handleResetModal = () => (modalIsOpen.value = false);

const showSearchedData = (data: GetDataWithParams) => {
  users.value = data.data;
  links.value = data.meta.links;
};

const handleCreated = (data: UserType) => {
  toastSuccess("Usuario creado con éxito");
  users.value!.push(data);
};

const handleUpdated = (data: UserType) => {
  toastSuccess("Usuario actualizado con éxito");
  const finIndex = users.value?.findIndex(({ id }) => id === data.id);
  if (finIndex !== -1) {
    users.value![finIndex!] = data;
  }
};
const editUser = (data: UserType) => {
  user.value = data;
};

const handleDeleteUser = async (id: number) => {
  const isConfirmed = confirmDelete();
  if (isConfirmed === true) {
    const { success } = await deleteUser(id);

    if (success === true) {
      toastSuccess("Usuario eliminado con éxito");
      const finIndex = users.value?.findIndex((el) => el.id === id);
      if (finIndex !== -1) {
        users.value?.splice(finIndex!, 1);
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
      table="user"
      :search-by="['name']"
      @data-searched="showSearchedData"
    />

    <FormUser
      :user="user"
      :reset="modalIsOpen"
      @created="handleCreated"
      @updated="handleUpdated"
      @reset="handleResetModal"
    />

    <UserList :users="users!" @edit="editUser" @delete="handleDeleteUser" />

    <Pagination :links="links" />
  </Layout>
</template>

<style scoped></style>
