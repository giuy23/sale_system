<script lang="ts" setup>
import { ref, computed } from "vue";
import { useUser } from "@/composables/useUser";

const { updatePassword, loading } = useUser();

const emits = defineEmits<{
  updated: [void];
}>();

const form = {
  current_password: "",
  password: "",
  password_confirmation: "",
};

const inputValue = ref([true, true, true]);
const hanleUpdatePassword = async () => {
  const { success, data } = await updatePassword({ ...form });
  if (success) {
    emits("updated");
  }
};

const typeInput = computed(() => (value: boolean) => {
  let type = "password";
  let icon = "bx bx-hide";
  if (!value) {
    type = "text";
    icon = "bx bx-show";
  }
  return { type, icon };
});
</script>

<template>
  <div class="mb-4">
    <h5 class="card-header">Cambiar Contraseña</h5>
    <div class="card-body">
      <form
        id="formChangePassword"
        @submit.prevent="hanleUpdatePassword"
        class="fv-plugins-bootstrap5 fv-plugins-framework"
      >
        <div class="alert alert-warning" role="alert">
          <h6 class="alert-heading mb-1">
            Asegúrese de que se cumplan estos requisitos:
          </h6>
          <span>• Mínimo 8 caracteres</span>
        </div>
        <div class="row">
          <div
            class="mb-3 col-12 col-sm-6 form-password-toggle fv-plugins-icon-container"
          >
            <label class="form-label" for="newPassword">Nueva Contraseña</label>
            <div class="input-group input-group-merge has-validation">
              <input
                class="form-control"
                :type="typeInput(inputValue[0]).type"
                id="newPassword"
                placeholder="············"
                v-model="form.password"
                required
              />
              <span
                @click="inputValue[0] = !inputValue[0]"
                class="input-group-text cursor-pointer custom-cursor-on-hover"
                ><i :class="typeInput(inputValue[0]).icon"></i
              ></span>
            </div>
            <div
              class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
            ></div>
          </div>
          <div
            class="mb-3 col-12 col-sm-6 form-password-toggle fv-plugins-icon-container"
          >
            <label class="form-label" for="password_confirmation"
              >Confirme Nueva Contraseña</label
            >
            <div class="input-group input-group-merge has-validation">
              <input
                class="form-control"
                :type="typeInput(inputValue[1]).type"
                id="password_confirmation"
                placeholder="············"
                v-model="form.password_confirmation"
                required
              />
              <span
                @click="inputValue[1] = !inputValue[1]"
                class="input-group-text cursor-pointer custom-cursor-on-hover"
                ><i :class="typeInput(inputValue[1]).icon"></i
              ></span>
            </div>
            <div
              class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
            ></div>
          </div>

          <div
            class="mb-3 col-12 col-sm-6 form-password-toggle fv-plugins-icon-container"
          >
            <label class="form-label" for="current_password"
              >Contraseña Actual</label
            >
            <div class="input-group input-group-merge has-validation">
              <input
                class="form-control"
                :type="typeInput(inputValue[2]).type"
                name="current_password"
                id="current_password"
                placeholder="············"
                v-model="form.current_password"
                required
              />
              <span
                @click="inputValue[2] = !inputValue[2]"
                class="input-group-text cursor-pointer custom-cursor-on-hover"
                ><i :class="typeInput(inputValue[2]).icon"></i
              ></span>
            </div>
            <div
              class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"
            ></div>
          </div>
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="btn btn-primary me-2"
            >
              Cambiar Contraseña
            </button>
          </div>
        </div>
        <input type="hidden" />
      </form>
    </div>
  </div>
</template>

<style scoped></style>
