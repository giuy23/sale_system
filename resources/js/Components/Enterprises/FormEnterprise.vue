<script lang="ts" setup>
import { Enterprise } from "@/types";
import { reactive, watch } from "vue";
import { useEnterprise } from "@/composables/useEnterprise";

const props = defineProps<{
  enterprise: Enterprise;
}>();

const emits = defineEmits<{
  updated: [Enterprise];
}>();

const { loading, updateEnterprise } = useEnterprise();
const initalEnterprise: Enterprise = {
  id: 0,
  name: "",
  name_comercial: "",
  description: "",
  cell_phone: 0,
  address: "",
  RUC: 0,
};
let enterpriseForm = reactive({ ...initalEnterprise });

watch(
  () => props.enterprise,
  (value) => {
    Object.assign(enterpriseForm, value);
  },
  { immediate: true }
);

const handleUpdateEnterprise = async () => {
  const { success, data } = await updateEnterprise({ ...enterpriseForm });
  if (success) {
    emits("updated", data!);
  }
};
</script>

<template>
  <div class="mb-4">
    <h5 class="card-header">Editar Empresa  </h5>
    <form @submit.prevent="handleUpdateEnterprise">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6 col-12">
            <label for="name" class="form-label">Nombre *</label>
            <input
              type="text"
              id="name"
              class="form-control"
              placeholder="Escriba su nombre"
              v-model="enterpriseForm.name"
              required
            />
          </div>
          <div class="col-md-6 col-12">
            <label for="name_comercial" class="form-label"
              >Nombre Comercial *</label
            >
            <input
              type="text"
              id="name_comercial"
              class="form-control"
              placeholder="Escriba el nombre comercial"
              v-model="enterpriseForm.name_comercial"
              required
            />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 col-12">
            <label for="description" class="form-label">Descripción *</label>
            <input
              type="text"
              id="description"
              class="form-control"
              placeholder="Escriba la descripción"
              v-model="enterpriseForm.description"
              required
            />
          </div>
          <div class="col-md-6 col-12">
            <label for="cell_phone" class="form-label">N° Celular *</label>
            <input
              type="number"
              id="cell_phone"
              class="form-control"
              v-model="enterpriseForm.cell_phone"
              placeholder="Escriba el N° de Celular"
              required
            />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 col-12">
            <label for="address" class="form-label">Dirección *</label>
            <input
              type="text"
              id="address"
              class="form-control"
              placeholder="Escriba su teléfono"
              v-model="enterpriseForm.address"
              required
            />
          </div>
          <div class="col-md-6 col-12">
            <label for="ruc" class="form-label">RUC </label>
            <input
              type="number"
              id="ruc"
              min="0"
              class="form-control"
              placeholder="Escriba su RUC"
              v-model="enterpriseForm.RUC"
            />
          </div>
        </div>

        <div class="row mb-3 justify-content-end">
          <div class="col-2">
            <button type="submit" :disabled="loading" class="btn btn-primary">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped></style>
