<script lang="ts" setup>
import { SubCategory } from '@/types';

const props = defineProps<{
  subCategories: SubCategory[];
}>()

const emits = defineEmits<{
  edit: [SubCategory];
  delete: [number]
}>();

const editSubCategory = (data: SubCategory) => {
  emits("edit", data)
}
const deleteSubCategory = (id: number) => {
  emits("delete", id)
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
            <th>Categoría</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="subCategory in subCategories">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ subCategory.name }}</strong>
            </td>
            <td>{{ subCategory.category!.name }}</td>
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
                    @click="editSubCategory(subCategory)"
                    ><i class="bx bx-edit-alt me-1"></i> Editar</a
                  >
                  <a class="dropdown-item" @click="deleteSubCategory(subCategory.id)"
                    ><i class="bx bx-trash me-1"></i> Eliminar</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!subCategories.length">
            <td colspan="3" class="text-center"><h5 class="my-2 lead fw-700">No hay Datos </h5></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>

</style>
