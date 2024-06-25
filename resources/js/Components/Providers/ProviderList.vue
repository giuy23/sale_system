<script lang="ts" setup>
import { Provider } from "@/types";

const props = defineProps<{
  providers: Provider[];
}>();

const emits = defineEmits<{
  edit: [Provider];
  delete: [number];
}>();

const editProvider = (data: Provider) => {
  emits("edit", data);
};
const deleteProvider = (id: number) => {
  emits("delete", id);
};
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
            <th>Nombre de Compañia</th>
            <th>Número de Celular</th>
            <th>Imagen</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="provider in providers">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ provider.name }}</strong>
            </td>
            <td>{{ provider.document_number }}</td>
            <td>{{ provider.name_company }}</td>
            <td>{{ provider.cellphone }}</td>
            <td>
              <figure>
                <img
                  :src="`storage/images/providers/${provider.image}`"
                  alt="imagen del proveedor"
                  style="width: 100px"
                />
              </figure>
            </td>
            <td>
              <div v-if="provider.id !== 1" class="dropdown">
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
                    @click="editProvider(provider)"
                    ><i class="bx bx-edit-alt me-1"></i> Editar</a
                  >
                  <a class="dropdown-item" @click="deleteProvider(provider.id)"
                    ><i class="bx bx-trash me-1"></i> Eliminar</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!providers.length">
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
