<script lang="ts" setup>
import { Client } from "@/types";
import { computed } from "vue";
import { USER_ROLE } from "../utils/types";

const props = defineProps<{
  clients: Client[];
}>();

const emits = defineEmits<{
  edit: [Client];
  delete: [number];
  changeState: [id: number, state: boolean];
}>();

const editclient = (data: Client) => {
  emits("edit", data);
};

const deleteclient = (id: number) => {
  emits("delete", id);
};

const changeState = (id: number, state: boolean) => {
  emits("changeState", id, state);
};

const state = computed(() => (value: boolean) => {
  let tag;
  value
    ? (tag = "<span class='text-info fw-bolder'>Activo</span>")
    : (tag = "<span class='text-danger fw-bolder'>Inactivo</span>");
  return tag;
});
</script>

<template>
  <div class="card">
    <h5 class="card-header">Striped rows</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Número de Celular</th>
            <th>Estado</th>
            <th v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role">
              Acciones
            </th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="client in clients">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ client.full_name }}</strong>
            </td>
            <td>{{ client.document_number }}</td>
            <td>{{ client.cell_phone }}</td>
            <td v-html="state( client.state! )"></td>
            <td>
              <div
                class="dropdown"
                v-if="
                  $page.props.auth.user.role_id === USER_ROLE.ADMIN.role &&
                  client.id !== 1
                "
              >
                <button
                  type="button"
                  class="btn p-0 dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                >
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a
                    class="dropdown-item"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#formClientModal"
                    @click="editclient(client)"
                    ><i class="bx bx-edit-alt me-1"></i> Editar</a
                  >
                  <a
                    v-if="client.state == false"
                    class="dropdown-item"
                    @click="changeState(client.id, !client.state)"
                    ><i class="bx bx-trash me-1"> </i>Activar
                  </a>
                  <a
                    v-if="client.state"
                    class="dropdown-item"
                    @click="changeState(client.id, !client.state)"
                    ><i class="bx bx-trash me-1"> </i>Desactivar
                  </a>
                  <a class="dropdown-item" @click="deleteclient(client.id)"
                    ><i class="bx bx-trash me-1"></i> Eliminar</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!clients.length">
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
