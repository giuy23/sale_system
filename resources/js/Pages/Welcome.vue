<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";

defineProps<{
  canLogin?: boolean;
  canRegister?: boolean;
  laravelVersion: string;
  phpVersion: string;
}>();

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
  <div class=" container-bg container-p-y align-content-center">
    <div class="row w-100">
      <div class="col-12">
        <nav v-if="canLogin" class="d-flex justify-content-end gap-4">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="h-5 fw-bold"
          >
            Dashboard
          </Link>

          <template v-else>
            <Link :href="route('login')" class="h-5 fw-bold"> Ingresar </Link>

            <Link
              v-if="canRegister"
              :href="route('register')"
              class="h-5 fw-bold"
            >
              Registrarse
            </Link>
          </template>
        </nav>
      </div>
    </div>
    <div
      class="misc-wrapper mt-5 d-flex flex-wrap align-content-center flex-column"
    >
      <div class="row">
        <div class="col-12 text-center">
          <h2 class="h-5 fw-bold">SISTEMA DE VENTAS</h2>
        </div>

        <div class="col-12">

        </div>
      </div>

      <div class="mt-4">
        <img
          src="https://leadsales.io/wp-content/uploads/2021/07/venta-online.png"
          alt="girl-doing-yoga-light"
          width="700"
          class="img-fluid"
          data-app-dark-img="illustrations/girl-doing-yoga-dark.png"
          data-app-light-img="illustrations/girl-doing-yoga-light.png"
        />
      </div>
    </div>
  </div>
  <!-- <Head title="Welcome" /> -->
</template>

<style scoped>
.container-bg {
  min-height:  100dvh;
    /* Fondo blanco minimalista */
    background-color: #ffffff; /* Color de fondo claro */
    background-image: linear-gradient(to top right, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.8)); /* Degradado más visible */
    background-size: 150% 150%;
    background-repeat: no-repeat;
  }
</style>
