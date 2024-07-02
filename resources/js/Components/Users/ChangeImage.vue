<script lang="ts" setup>
import { ref } from "vue";
import { useUser } from "@/composables/useUser";
import { toastInfo, toastSuccess } from "../utils/toast";
import { router } from "@inertiajs/vue3";

const image = ref<string>();
const fileImage = ref();

const { roleText, updateImageUser } = useUser();
const props = defineProps<{
  imageUrl: string;
}>();

image.value = props.imageUrl;

const emits = defineEmits<{
  updated: [string];
}>();

const handleUpdateImageUser = async () => {
  if (fileImage.value === undefined) return toastInfo("Introduzca una imagen");
  const { success } = await updateImageUser(fileImage.value);
  if (success) {
    router.reload({ only: ["auth"] });
    toastSuccess("Imagen actualizada con éxito");
  }
};

const handleFileChange = (e: Event) => {
  const element = e.target as HTMLInputElement;
  if (element.files) {
    fileImage.value = element?.files[0];
    if (!fileImage.value.type.includes("image/")) {
      fileImage.value = null;
      return toastInfo("Introduzca una imagen");
    }

    if (fileImage.value) {
      const reader = new FileReader();
      reader.onload = () => {
        image.value = reader.result as string;
      };
      reader.readAsDataURL(fileImage.value);
    }
  }
};
</script>

<template>
  <!-- {{ $page.props.auth.image = 'xd' }} -->
  <div class="user-avatar-section">
    <div class="d-flex align-items-center flex-column">
      <img
        v-if="image?.startsWith('data:image')"
        class="img-fluid rounded my-4"
        :src="image"
        height="110"
        width="110"
        alt="User avatar"
      />
      <img
        v-else
        class="img-fluid rounded my-4"
        :src="`storage/images/users/${$page.props.auth.image}`"
        height="110"
        width="110"
        alt="User avatar"
      />
      <div class="user-info text-center">
        <h4 class="mb-2">
          {{ $page.props.auth.user.name }},
          {{ $page.props.auth.user.sur_name }}
        </h4>
        <span class="badge bg-label-secondary">{{
          roleText($page.props.auth.user.role_id!)
        }}</span>
      </div>
    </div>
  </div>
  <h5 class="pb-2 border-bottom mb-2 mt-2">Cambiar Imagen</h5>
  <form
    @submit.prevent="handleUpdateImageUser"
    class="d-flex justify-content-around flex-wrap my-4 py-3"
  >
    <label for="file-upload" class="btn btn-outline-primary">
      <input
        type="file"
        name="file"
        id="file-upload"
        style="display: none"
        accept="image/*"
        @input="handleFileChange($event)"
      />
      <span class="tf-icons bx bx-pie-chart-alt"></span>&nbsp; Subir
    </label>
    <button type="submit" class="btn btn-outline-primary">
      <span class="tf-icons bx bx-pie-chart-alt"></span>&nbsp; Guardar
    </button>
  </form>
</template>

<style scoped></style>
