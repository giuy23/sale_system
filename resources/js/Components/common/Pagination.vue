<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { reactive } from "vue";
import { SearchByDate } from "@/types";
import { watch } from "vue";
import { useSearch } from "@/composables/useSearch";

const { searchData } = useSearch();
const props = defineProps<{
  links: any[];
  storage?: string;
}>();

const updateLinks = () => {
  props.links.forEach((link) => {
    link.url = getPage(link.url ?? "");
  });
};

const getPage = (url: string) => {
  if (!url) return "";

  const params = new URLSearchParams();
  // Agregar los parámetros de búsqueda con valores definidos en searchData
  Object.keys(searchData).forEach((key) => {
    const value = searchData[key as keyof SearchByDate];
    if (value !== null && value !== undefined && value !== "") {
      params.append(key, value.toString());
    }
  });

  // Añadir los parámetros de búsqueda al URL existente
  const baseUrl = url.split("?")[0];
  const existingParams = new URLSearchParams(url.split("?")[1] || "");

  for (const [key, value] of existingParams.entries()) {
    params.append(key, value);
  }

  return `${baseUrl}?${params.toString()}`;
};

watch(
  () => props.links,
  () => {
    if (props.storage) {
      const savedData = localStorage.getItem(props.storage);
      if (savedData) {
        Object.assign(searchData, JSON.parse(savedData));
        updateLinks();
      }
    }
    updateLinks();
  },
  { immediate: true }
);
</script>

<template>
  <nav aria-label="Navegación" class="mt-4">
    <ul class="pagination justify-content-end">
      <li
        class="page-item prev"
        v-for="link in links"
        :class="{ active: link.active }"
        :key="link.label"
      >
        <Link
          :href="link.url ?? ''"
          v-html="link.label"
          class="page-link"
          preserve-scroll
        />
      </li>
    </ul>
  </nav>
</template>

<style scoped></style>
