<script setup lang="ts">
import Layout from "@/Pages/views/Partials/Layout.vue";
import EditUser from "@/Components/Users/EditUser.vue";
import ChangePassword from "@/Components/Users/ChangePassword.vue";
import ChangeImage from "@/Components/Users/ChangeImage.vue";
import { UserType } from "@/types";
import { useUser } from "@/composables/useUser";
import { ref } from "vue";
import { toastSuccess } from "@/Components/utils/toast";
import { router } from "@inertiajs/vue3";

const { roleText } = useUser();
const user = ref<UserType>();

const props = defineProps<{
  auth: { user: UserType; image: string };
}>();

user.value = props.auth.user;

const updatedUser = () => {
  toastSuccess("Perfil actualizado con éxito.");
  router.reload({ only: ["auth"] });
};

const updatedPassword = () => {
  toastSuccess("Contraseña actualiza con éxito.");
};
</script>

<template>
  <Layout>
    <h4 class="fw-bold py-1 py-md-3">
      <span class="text-muted fw-light">Usuario /</span> Cuenta
    </h4>
    <div class="row">
      <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <div class="card mb-4">
          <div class="card-body">
            <ChangeImage :image-url="$page.props.auth.image" />
            <h5 class="pb-2 border-bottom mb-2">Detalles</h5>
            <div class="info-container">
              <ul class="list-unstyled">
                <li class="mb-3">
                  <span class="fw-medium me-2">Nombres:</span>
                  <span>{{ $page.props.auth.user.name }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Apellidos:</span>
                  <span>{{ $page.props.auth.user.sur_name }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Email:</span>
                  <span>{{ $page.props.auth.user.email }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Estado:</span>
                  <span class="badge bg-label-success">Activo</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Rol:</span>
                  <span>{{ roleText(user?.role_id!) }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">DNI:</span>
                  <span>{{ $page.props.auth.user.document_number }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Teléfono:</span>
                  <span>{{ $page.props.auth.user.cell_phone }}</span>
                </li>
              </ul>
              <!-- <div class="d-flex justify-content-center pt-3">
                <a
                  href="javascript:;"
                  class="btn btn-primary me-3"
                  data-bs-target="#editUser"
                  data-bs-toggle="modal"
                  >Edit</a
                >
                <a href="javascript:;" class="btn btn-label-danger suspend-user"
                  >Suspended</a
                >
              </div> -->
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <div class="nav-align-top mb-4">
          <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
            <li class="nav-item">
              <button
                type="button"
                class="nav-link active"
                role="tab"
                data-bs-toggle="tab"
                data-bs-target="#navs-pills-justified-home"
                aria-controls="navs-pills-justified-home"
                aria-selected="true"
              >
                <i class="bx bx-user me-1"></i> Perfil
              </button>
            </li>
            <li class="nav-item">
              <button
                type="button"
                class="nav-link"
                role="tab"
                data-bs-toggle="tab"
                data-bs-target="#navs-pills-justified-profile"
                aria-controls="navs-pills-justified-profile"
                aria-selected="false"
              >
                <i class="bx bx-lock-alt me-1"></i> Contraseña
              </button>
            </li>
          </ul>
          <div class="tab-content">
            <div
              class="tab-pane fade active show"
              id="navs-pills-justified-home"
              role="tabpanel"
            >
              <EditUser :user="user!" @updated="updatedUser" />
            </div>
            <div
              class="tab-pane fade"
              id="navs-pills-justified-profile"
              role="tabpanel"
            >
              <ChangePassword @updated="updatedPassword" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
  <!-- <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout> -->
</template>
