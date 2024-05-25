<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  links: Object,
});
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
