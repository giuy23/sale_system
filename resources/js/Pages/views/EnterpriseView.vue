<script setup lang="ts">
import Layout from "@/Pages/views/Partials/Layout.vue";
import ChangeImage from "@/Components/Users/ChangeImage.vue";
import { Enterprise, GetDataWithParams } from "@/types";
import { ref, watch } from "vue";
import { toastSuccess } from "@/Components/utils/toast";
import FormEnterprise from "@/Components/Enterprises/FormEnterprise.vue";
import FormImage from "@/Components/Enterprises/FormImage.vue";

const enterprise = ref<Enterprise>();

const props = defineProps<{
  enterprise: { data: Enterprise };
}>();

watch(
  props.enterprise,
  (value) => {
    enterprise.value = value.data;
  },
  { immediate: true }
);

const updatedEnterprise = (data: Enterprise) => {
  toastSuccess("Empresa actualizada con éxito.");
  enterprise.value = data;
};
</script>

<template>
  <Layout>
    <h4 class="fw-bold py-1 py-md-3">
      <span class="text-muted fw-light">Empresa </span>
    </h4>
    <div class="row">
      <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <div class="card mb-4">
          <div class="card-body">
            <FormImage :id="enterprise?.id!" :image-url="enterprise?.image!" />
            <h5 class="pb-2 border-bottom mb-2">Detalles</h5>
            <div class="info-container">
              <ul class="list-unstyled">
                <li class="mb-3">
                  <span class="fw-medium me-2">Nombre:</span>
                  <span>{{ enterprise?.name }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Nombre comercial:</span>
                  <span>{{ enterprise?.name_comercial }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Descripción:</span>
                  <span>{{ enterprise?.description }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">N° Celular:</span>
                  <span class="badge bg-label-success">{{
                    enterprise?.cell_phone
                  }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">Dirección:</span>
                  <span>{{ enterprise?.address }}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-medium me-2">RUC:</span>
                  <span>{{ enterprise?.RUC }}</span>
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
          <!-- <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
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
          </ul> -->
          <div class="tab-content">
            <div
              class="tab-pane fade active show"
              id="navs-pills-justified-home"
              role="tabpanel"
            >
              <FormEnterprise
                :enterprise="enterprise!"
                @updated="updatedEnterprise"
              />
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
