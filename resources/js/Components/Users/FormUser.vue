<script lang="ts" setup>
import { UserType } from "@/types/index";
import { reactive, ref, watch } from "vue";
import { useUser } from "@/composables/useUser";

const { createUser, updateUser } = useUser();
const props = defineProps<{
  user: UserType | null;
  reset: Boolean;
}>();

const emits = defineEmits<{
  created: [UserType];
  updated: [UserType];
  reset: [void];
}>();

watch(
  () => props.user,
  (value) => {
    userForm = {...value!}
    // Object.assign(userForm, value);
  }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const initialUser: UserType = {
  id: 0,
  name: "",
  sur_name: "",
  email: "",
  document_number: 0,
  cell_phone: null,
  password: "",
  role_id: 2,
};

const modalRef = ref(false);
const image = ref();
const fileInput = ref<HTMLInputElement | null>(null);
let userForm = reactive({ ...initialUser });

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();
  Object.assign(userForm, { ...initialUser });
  image.value = null;
  fileInput.value!.value = "";
  emits("reset");
};

const handleSaveUser = () => {
  userForm.id ? handleEditUser() : handleCreateUser();
};

const handleCreateUser = async () => {
  const { success, data } = await createUser({
    ...userForm,
    image: image.value,
  });
  if (success) {
    emits("created", data!);
    closeModal();
  }
};
const handleEditUser = async () => {
  const { success, data } = await updateUser({
    ...userForm,
    image: image.value,
  });
  if (success) {
    emits("updated", data!);
    closeModal();
  }
};

const handleInputImage = (e: Event) => {
  const file = (e.target as HTMLInputElement).files![0];
  image.value = file;
};
</script>

<template>
  <div
    ref="modalRef"
    class="modal fade"
    id="backDropModal"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <form
        class="modal-content"
        @submit.prevent="handleSaveUser"
        enctype="multipart/form-data"
      >
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Usuario</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeModal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-md-6 mb-3">
              <label for="name" class="form-label">Nombre*</label>
              <input
                type="text"
                id="name"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="userForm.name"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="sur_name" class="form-label">Apellidos*</label>
              <input
                type="text"
                id="sur_name"
                class="form-control"
                placeholder="Escriba el apellido"
                v-model="userForm.sur_name"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="email" class="form-label">Email*</label>
              <input
                type="email"
                id="email"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="userForm.email"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="password" class="form-label">Contraseña*</label>
              <input
                type="password"
                id="password"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="userForm.password"
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="DNI" class="form-label">DNI*</label>
              <input
                type="number"
                id="DNI"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="userForm.document_number"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="cell_phone" class="form-label">N° Celular</label>
              <input
                type="number"
                id="cell_phone"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="userForm.cell_phone"
              />
            </div>

          </div>
          <div class="row g-2">
            <div class="col-12 mb-3">
              <label for="password" class="form-label">Rol*</label>
              <select class="form-select" name="role_id" id="role_id" v-model="userForm.role_id">
                <option :value="1">Administrador</option>
                <option :value="2">Vendedor</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label for="image" class="form-label">Imagen</label>
              <input
                ref="fileInput"
                type="file"
                accept="image/*"
                id="image"
                class="form-control"
                @input="handleInputImage"
              />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            @click="closeModal"
          >
            Cerrar
          </button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
