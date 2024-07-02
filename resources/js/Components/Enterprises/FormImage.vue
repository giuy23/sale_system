<script lang="ts" setup>
import { ref } from "vue";
import { useEnterprise } from "@/composables/useEnterprise";
import { toastInfo, toastSuccess } from "../utils/toast";

const image = ref<string>();
const fileImage = ref();

const { updateImageEnterprise, loading } = useEnterprise();
const props = defineProps<{
  id: number;
  imageUrl: string;
}>();

image.value = props.imageUrl;

const emits = defineEmits<{
  updated: [string];
}>();

const handleUpdateImageEnterprise = async () => {
  if (fileImage.value === undefined) return toastInfo("Introduzca una imagen");
  const { success } = await updateImageEnterprise({
    enterprise_id: props.id,
    image: fileImage.value,
  });
  if (success) {
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
  <div class="user-avatar-section">
    <div class="d-flex align-items-center flex-column">
      <img
        v-if="image!.startsWith('data:image')"
        class="img-fluid rounded my-4"
        :src="image"
        height="110"
        width="110"
        alt="Imagen de la empresa"
      />
      <img
        v-else
        class="img-fluid rounded my-4"
        :src="`storage/images/enterprises/${image}`"
        height="110"
        width="110"
        alt="Imagen de la empresa"
      />
    </div>
  </div>
  <h5 class="pb-2 border-bottom mb-2 mt-2">Cambiar Imagen</h5>
  <form
    class="d-flex justify-content-around flex-wrap my-4 py-3"
    @submit.prevent="handleUpdateImageEnterprise"
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

    <button type="submit" :disabled="loading" class="btn btn-outline-primary">
      <span class="tf-icons bx bx-pie-chart-alt"></span>&nbsp; Guardar
    </button>
  </form>
</template>

<style scoped></style>
