<script lang="ts" setup>
import { Expense } from '@/types';


const props = defineProps<{
  expenses: Expense[];
}>();

const emits = defineEmits<{
  edit: [Expense];
  delete: [number];
}>()

const editExpense = (data: Expense) => {
  emits("edit", data)
}

const deleteExpense = (id:number) => {
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
            <th>Monto</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="expense in expenses">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ expense.amount }}</strong>
            </td>
            <td>{{ expense.description }}</td>
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
                    data-bs-target="#createExpense"
                    @click="editExpense(expense)"
                    ><i class="bx bx-edit-alt me-1"></i> Editar</a
                  >
                  <a class="dropdown-item" @click="deleteExpense(expense.id)"
                    ><i class="bx bx-trash me-1"></i> Eliminar</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!expenses.length">
            <td colspan="3" class="text-center"><h5 class="my-2 lead fw-700">No hay Datos </h5></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>

</style>
