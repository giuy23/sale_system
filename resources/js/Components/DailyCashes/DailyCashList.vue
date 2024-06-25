<script lang="ts" setup>
import { DailyCash } from "@/types";
import { computed } from "vue";
import { useExpense } from "@/composables/useExpense";

const { getExpenses } = useExpense();
const props = defineProps<{
  dailyCashes: DailyCash[];
}>();

const emits = defineEmits<{
  closeCash: [id: number, state: boolean];
  createExpense: [id: number, type: number];
}>();

const changeStateCashRegister = (id: number, state: boolean) => {
  emits("closeCash", id, state);
};

const createExpense = (id: number, type: number) => {
  emits("createExpense", id, type);
};

const redirectViewExpense = (id: number) => {
  getExpenses(id)
}

const state = computed(() => (value: boolean) => {
  let tag;
  value
    ? (tag = "<span class='text-info fw-bolder'>Abierto</span>")
    : (tag = "<span class='text-danger fw-bolder'>Cerrado</span>");
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
            <th>Fecha de Apertura</th>
            <th>Fecha de Cierre</th>
            <th>Monto Inicial</th>
            <th>Monto Final</th>
            <th>Ganacia</th>
            <th>Estado</th>
            <th>Persona</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="dailyCash in dailyCashes">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ dailyCash.created_at }}</strong>
            </td>
            <td>{{ dailyCash.updated_at }}</td>
            <td>{{ dailyCash.start_money }}</td>
            <td>{{ dailyCash.final_money }}</td>
            <td>{{ dailyCash.profit }}</td>
            <td v-html="state(dailyCash.state)"></td>
            <!-- <td>{{ state(dailyCash.state) }}</td> -->
            <td>{{ dailyCash.user_name }}</td>
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
                    v-if="dailyCash.state"
                    @click="createExpense(dailyCash.id, 1)"
                    ><i class="bx bx-edit-alt me-1"></i> Crear Ingreso</a
                  >
                  <a
                    class="dropdown-item"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#createExpense"
                    v-if="dailyCash.state"
                    @click="createExpense(dailyCash.id, 2)"
                    ><i class="bx bx-edit-alt me-1"></i> Crear Egreso</a
                  >
                  <a
                    v-if="dailyCash.state"
                    class="dropdown-item"
                    @click="
                      changeStateCashRegister(dailyCash.id, dailyCash.state)
                    "
                    ><i class="bx bx-trash me-1"></i> Cerrar Caja</a
                  >

                  <a
                    v-if="dailyCash.state"
                    class="dropdown-item"
                    :href="route('expense.index', {id: dailyCash.id})"
                    ><i class="bx bx-trash me-1"></i> Ver Gastos</a
                  >
                  <!-- <a
                    v-if="dailyCash.state"
                    class="dropdown-item"
                    @click="redirectViewExpense(dailyCash.id)"
                    ><i class="bx bx-trash me-1"></i> Ver Gastos</a
                  > -->
                  <a
                    v-else
                    class="dropdown-item"
                    @click="
                      changeStateCashRegister(dailyCash.id, dailyCash.state)
                    "
                    ><i class="bx bx-trash me-1"></i> Abrir Caja</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!dailyCashes.length">
            <td colspan="9" class="text-center">
              <h5 class="my-2 lead fw-700">No hay Datos</h5>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped></style>
