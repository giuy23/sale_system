<script lang="ts" setup>
import { Product } from "@/types";
import { computed } from "vue";
import { USER_ROLE } from "../utils/types";

const props = defineProps<{
  products: Product[];
}>();

const emits = defineEmits<{
  edit: [Product];
  delete: [number];
  changeState: [id: number, state: boolean];
}>();

const editProduct = (data: Product) => {
  emits("edit", data!);
};

const deleteProduct = (id: number) => {
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
            <th>Código de Barras</th>
            <th>Precio de compra</th>
            <th>Precio de venta</th>
            <th>Cant.</th>
            <th>Cant. Mínima</th>
            <th>Estado</th>
            <th>Imagen</th>
            <th v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role">Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="product in products">
            <td>
              <i class="fab fa-angular fa-lg text-danger me-3"></i>
              <strong>{{ product.name }}</strong>
            </td>
            <td>{{ product.bar_code }}</td>
            <td>{{ product.purchase_price }}</td>
            <td>{{ product.sale_price }}</td>
            <td>{{ product.quantity }}</td>
            <td>{{ product.minimum_quantity }}</td>
            <td v-html="state(product.state!)"></td>
            <td>
              <figure>
                <img
                  :src="`storage/images/products/${product.image}`"
                  alt="imagen del proveedor"
                  style="width: 100px"
                />
              </figure>
            </td>
            <td>
              <div v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role" class="dropdown">
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
                    @click="editProduct(product)"
                    ><i class="bx bx-edit-alt me-1"></i> Editar</a
                  >
                  <a
                    v-if="product.state == false"
                    class="dropdown-item"
                    @click="changeState(product.id, !product.state)"
                    ><i class="bx bx-trash me-1"> </i>Activar
                  </a>
                  <a
                    v-if="product.state"
                    class="dropdown-item"
                    @click="changeState(product.id, !product.state)"
                    ><i class="bx bx-trash me-1"> </i>Desactivar
                  </a>
                  <a class="dropdown-item" @click="deleteProduct(product.id)"
                    ><i class="bx bx-trash me-1"></i> Eliminar</a
                  >
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!products.length">
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
