<script lang="ts" setup>
import { Sale } from "@/types/index";
import { computed } from "vue";

const props = defineProps<{
  sales: Sale[];
}>();

const emits = defineEmits<{
  cancel: [number];
  export: [string];
}>();

const cancelSale = (id: number) => {
  emits("cancel", id);
};

const state = computed(() => (value: number) => {
  let tag;
  if (value === 1) {
    tag = "<span class='text-info fw-bolder'>Pagado</span>";
  } else if (value === 2) {
    tag = "<span class='text-danger fw-bolder'>Anulado</span>";
  } else if (value === 3) {
    tag = "<span class='text-warning fw-bolder'>Crédito</span>";
  } else {
    tag = "<span class='text-primary fw-bolder'>Crédito Pagado</span>";
  }
  return tag;
});

const handleExport = (type: string) => {
  emits("export", type);
};
</script>

<template>
  <div class="card">
    <div class="row">
      <div class="col-6">
        <h5 class="card-header">Striped rows</h5>
      </div>
      <div class="col-6 d-flex justify-content-end align-items-center px-4">
        <div class="btn-group" id="dropdown-icon-demo">
          <button
            type="button"
            class="btn btn-primary dropdown-toggle"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="bx bx-menu"></i> Exportar
          </button>
          <ul class="dropdown-menu">
            <li>
              <a
                @click="handleExport('excel')"
                class="dropdown-item d-flex align-items-center"
                ><i class="bx bx-chevron-right scaleX-n1-rtl"></i>Excel</a
              >
            </li>
            <!-- <li>
              <hr class="dropdown-divider" />
            </li> -->
            <li>
              <a
                @click="handleExport('pdf')"
                class="dropdown-item d-flex align-items-center"
                ><i class="bx bx-chevron-right scaleX-n1-rtl"></i>PDF</a
              >
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>IGV</th>
            <th>Descuento</th>
            <th>Estado</th>
            <th>Vendedor</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="sale in sales">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ sale.created_at }}</strong>
            </td>
            <td>{{ sale.client_name }}</td>
            <td>S/ {{ sale.total }}</td>
            <td>S/ {{ sale.igv }}</td>
            <td>S/ {{ sale.discount_total }}</td>
            <td v-html="state(sale.state)"></td>
            <td>{{ sale.user_name }}</td>
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
                    v-if="sale.state === 1"
                    class="dropdown-item"
                    type="button"
                    :href="route('saleDetail.index', { id: sale.id })"
                    ><i class="bx bx-edit-alt me-1"></i> Anular Venta</a
                  >
                  <a
                    class="dropdown-item"
                    type="button"
                    target="_blank"
                    :href="route('sale.detailSale', sale.id)"
                    ><i class="bx bx-edit-alt me-1"></i> Ver Detalles</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!sales.length">
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
