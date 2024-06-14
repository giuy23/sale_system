<script lang="ts" setup>
import { UserType } from '@/types';

const props =defineProps<{
  users: UserType[];
}>()

const emits = defineEmits<{
  edit: [UserType];
  delete: [number]
}>()

const editUser = (data: UserType) => {
  emits("edit", data)
}

const deleteUser = (id: number) => {
  emits("delete", id);
}

</script>

<template>
  <div class="card">
    <h5 class="card-header">Striped rows</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>DNI</th>
            <th>N° Celular</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Imagen</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="user in users">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ user.sur_name }}, {{ user.name }} </strong>
            </td>
            <td>{{ user.email }}</td>
            <td>{{ user.document_number }}</td>
            <td>{{ user.cell_phone }}</td>
            <td>{{ user.role }}</td>
            <td>{{ user.state }}</td>
            <td>
              <figure>
                <img
                  :src="`storage/images/users/${user.image}`"
                  alt="imagen del usuario" style="width: 100px;"
                />
              </figure>
            </td>
            <td>
              <div class="dropdown">
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
                    data-bs-target="#backDropModal"
                    @click="editUser(user)"
                    ><i class="bx bx-edit-alt me-1"></i> Editar</a
                  >
                  <a class="dropdown-item" @click="deleteUser(user.id)"
                    ><i class="bx bx-trash me-1"></i> Eliminar</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!users.length">
            <td colspan="8" class="text-center">
              <h5 class="my-2 lead fw-700">No hay Datos</h5>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>

</style>
