<script lang="ts" setup>
import { useUser } from "@/composables/useUser";

const { logoutUser, roleText } = useUser();

const handleLogout = async () => {
  const { success } = await logoutUser();
  if (success) {
    window.location.href = "/";
  }
};
</script>

<template>
  <nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
  >
    <div
      class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none"
    >
      <a class="nav-item nav-link px-0 me-xl-4">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>

    <div
      class="navbar-nav-right d-flex align-items-center"
      id="navbar-collapse"
    >
      <!-- Search -->
      <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
          <i class="bx bx-search fs-4 lh-0"></i>
          <input
            type="text"
            class="form-control border-0 shadow-none"
            placeholder="Search..."
            aria-label="Search..."
          />
        </div>
      </div>
      <!-- /Search -->

      <ul class="navbar-nav flex-row align-items-center ms-auto">
        <!-- Place this tag where you want the button to render. -->
        <li class="nav-item lh-1 me-3">
          <span class="github-button"
            >{{ roleText($page.props.auth.user.role_id) }}
          </span>
        </li>

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a
            class="nav-link dropdown-toggle hide-arrow"
            data-bs-toggle="dropdown"
          >
            <div class="avatar avatar-online">
              <img
                :src="`storage/images/users/${$page.props.auth.image}`"
                class="w-px-40 rounded-circle"
              />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img
                        :src="`storage/images/users/${$page.props.auth.image}`"
                        class="w-px-40 h-auto rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-semibold d-block"
                      >{{ $page.props.auth.user.name }},
                      {{
                        $page.props.auth.user.sur_name
                          ? $page.props.auth.user.sur_name
                          : ""
                      }}</span
                    >
                    <small class="text-muted">{{
                      roleText($page.props.auth.user.role_id)
                    }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <a class="dropdown-item" :href="route('profile.edit')">
                <i class="bx bx-user me-2"></i>
                <span class="align-middle">My Profile</span>
              </a>
            </li>
            <!-- <li>
              <a class="dropdown-item" href="#">
                <i class="bx bx-cog me-2"></i>
                <span class="align-middle">Settings</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="#">
                <span class="d-flex align-items-center align-middle">
                  <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                  <span class="flex-grow-1 align-middle">Billing</span>
                  <span
                    class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20"
                    >4</span
                  >
                </span>
              </a>
            </li> -->
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <a class="dropdown-item" @click="handleLogout">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Salir</span>
              </a>
            </li>
          </ul>
        </li>
        <!--/ User -->
      </ul>
    </div>
  </nav>

  <!-- / Navbar -->
</template>

<style scoped></style>
