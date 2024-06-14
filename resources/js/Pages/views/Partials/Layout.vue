<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Sidebar from "./Sidebar.vue";
import Header from "./Header.vue";
import { Toaster } from "vue-sonner";
</script>

<template>
  <Toaster position="top-center" richColors />

  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <div class="layout-page">
        <Header/>

        <Sidebar />
        <!-- / Layout page -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <slot></slot>
          </div>
        </div>
      </div>
    </div>
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
</template>
