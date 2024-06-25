<script lang="ts" setup>
import { ClientToSell } from '@/types';


const props = defineProps<{
  saleCredits: ClientToSell[];
}>()

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
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="client in saleCredits">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ client.full_name }}</strong>
            </td>
            <td>{{ client.document_number }}</td>
            <td>
              <div class="dropdown" v-if="client.id !== 1">
                <button
                  type="button"
                  class="btn p-0 dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                >
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item"
                  :href="route('creditSale.getClientDebts', {id: client.id} )"
                    ><i class="bx bx-trash me-1"></i> Ver Deudas</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!saleCredits.length">
            <td colspan="6" class="text-center">
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
