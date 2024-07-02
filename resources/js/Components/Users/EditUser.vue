<script lang="ts" setup>
import { UserType, UserUpdate } from "@/types";
import { reactive, watch } from "vue";
import { useUser } from "@/composables/useUser";

const props = defineProps<{
  user: UserType;
}>();

const emits = defineEmits<{
  updated: [void];
}>();

const { updateUser, loading } = useUser();
const initialUser: UserUpdate = {
  id: 0,
  name: "",
  sur_name: "",
  email: "",
  document_number: 0,
  cell_phone: null,
};
let userForm = reactive({ ...initialUser });

watch(
  () => props.user,
  (value) => {
    userForm.id = value.id;
    userForm.name = value.name;
    userForm.sur_name = value.sur_name;
    userForm.email = value.email;
    userForm.document_number = value.document_number;
    userForm.cell_phone = value.cell_phone;
  },
  { immediate: true }
);

const handleUpdateUser = async () => {
  const { success, data } = await updateUser({ ...userForm });
  if (success) {
    emits("updated");
  }
};
</script>

<template>
  <div class="mb-4">
    <h5 class="card-header">Editar Perfil</h5>
    <form @submit.prevent="handleUpdateUser">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6 col-12">
            <label for="name" class="form-label">Nombre *</label>
            <input
              type="text"
              id="name"
              class="form-control"
              placeholder="Escriba su nombre"
              v-model="userForm.name"
              required
            />
          </div>
          <div class="col-md-6 col-12">
            <label for="sur_name" class="form-label">Apellido </label>
            <input
              type="text"
              id="sur_name"
              class="form-control"
              placeholder="Escriba su apellido"
              v-model="userForm.sur_name"
            />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 col-12">
            <label for="email" class="form-label">Email *</label>
            <input
              type="email"
              id="email"
              class="form-control"
              placeholder="Escriba su email"
              v-model="userForm.email"
              required
            />
          </div>
          <div class="col-md-6 col-12">
            <label for="document_number" class="form-label">DNI *</label>
            <input
              type="number"
              id="document_number"
              class="form-control"
              v-model="userForm.document_number"
              placeholder="Escriba su DNI"
              required
            />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6 col-12">
            <label for="cell_phone" class="form-label">Teléfono </label>
            <input
              type="number"
              id="cell_phone"
              class="form-control"
              placeholder="Escriba su teléfono"
              v-model="userForm.cell_phone"
            />
          </div>
        </div>

        <div class="row mb-3 justify-content-end">
          <div class="col-2">
            <button type="submit" :disabled="loading" class="btn btn-primary">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </form>
    <!-- <div class="table-responsive mb-3">
      <div
        id="DataTables_Table_0_wrapper"
        class="dataTables_wrapper dt-bootstrap5 no-footer"
      >
        <div
          class="d-flex justify-content-between align-items-center flex-column flex-sm-row mx-4 row"
        >
          <div
            class="col-sm-4 col-12 d-flex align-items-center justify-content-sm-start justify-content-center"
          >
            <div class="dataTables_length" id="DataTables_Table_0_length">
              <label
                >Show
                <select
                  name="DataTables_Table_0_length"
                  aria-controls="DataTables_Table_0"
                  class="form-select"
                >
                  <option value="7">7</option>
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="75">75</option>
                  <option value="100">100</option>
                </select></label
              >
            </div>
          </div>
          <div
            class="col-sm-8 col-12 d-flex align-items-center justify-content-sm-end justify-content-center"
          >
            <div id="DataTables_Table_0_filter" class="dataTables_filter">
              <label
                >Search:<input
                  type="search"
                  class="form-control"
                  placeholder="Search Project"
                  aria-controls="DataTables_Table_0"
              /></label>
            </div>
          </div>
        </div>
        <table
          class="table datatable-project border-top dataTable no-footer dtr-column"
          id="DataTables_Table_0"
          aria-describedby="DataTables_Table_0_info"
          style="width: 918px"
        >
          <thead>
            <tr>
              <th
                class="control sorting_disabled dtr-hidden"
                rowspan="1"
                colspan="1"
                style="width: 0px; display: none"
                aria-label=""
              ></th>
              <th
                class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all"
                rowspan="1"
                colspan="1"
                style="width: 18px"
                data-col="1"
                aria-label=""
              >
                <input type="checkbox" class="form-check-input" />
              </th>
              <th
                class="sorting sorting_desc custom-cursor-on-hover"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                rowspan="1"
                colspan="1"
                style="width: 324px"
                aria-label="Project: activate to sort column ascending"
                aria-sort="descending"
              >
                Project
              </th>
              <th
                class="text-nowrap sorting_disabled"
                rowspan="1"
                colspan="1"
                style="width: 137px"
                aria-label="Total Task"
              >
                Total Task
              </th>
              <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                rowspan="1"
                colspan="1"
                style="width: 128px"
                aria-label="Progress: activate to sort column ascending"
              >
                Progress
              </th>
              <th
                class="sorting_disabled"
                rowspan="1"
                colspan="1"
                style="width: 99px"
                aria-label="Hours"
              >
                Hours
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="odd">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium"
                      >Vue Admin template</span
                    ><small class="text-muted">Vuejs Project</small>
                  </div>
                </div>
              </td>
              <td>214/627</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">78%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-success"
                      style="width: 78%"
                      aria-valuenow="78%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>88:19h</td>
            </tr>
            <tr class="even">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium">Online Webinar</span
                    ><small class="text-muted">Official Event</small>
                  </div>
                </div>
              </td>
              <td>12/20</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">69%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-info"
                      style="width: 69%"
                      aria-valuenow="69%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>12:12h</td>
            </tr>
            <tr class="odd">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium">Hoffman Website</span
                    ><small class="text-muted">HTML Project</small>
                  </div>
                </div>
              </td>
              <td>56/183</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">43%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-warning"
                      style="width: 43%"
                      aria-valuenow="43%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>76h</td>
            </tr>
            <tr class="even">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium"
                      >Foodista mobile app</span
                    ><small class="text-muted">iPhone Project</small>
                  </div>
                </div>
              </td>
              <td>12/86</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">49%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-warning"
                      style="width: 49%"
                      aria-valuenow="49%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>45h</td>
            </tr>
            <tr class="odd">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium"
                      >Falcon Logo Design</span
                    ><small class="text-muted">UI/UX Project</small>
                  </div>
                </div>
              </td>
              <td>9/50</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">15%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-danger"
                      style="width: 15%"
                      aria-valuenow="15%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>89h</td>
            </tr>
            <tr class="even">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium"
                      >Dojo React Project</span
                    ><small class="text-muted">React Project</small>
                  </div>
                </div>
              </td>
              <td>234/378</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">73%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-info"
                      style="width: 73%"
                      aria-valuenow="73%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>67:10h</td>
            </tr>
            <tr class="odd">
              <td class="control" tabindex="0" style="display: none"></td>
              <td class="dt-checkboxes-cell">
                <input type="checkbox" class="dt-checkboxes form-check-input" />
              </td>
              <td class="sorting_1">
                <div class="d-flex justify-content-left align-items-center">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        alt="Project Image"
                        class="rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-truncate fw-medium">Dashboard Design</span
                    ><small class="text-muted">Vuejs Project</small>
                  </div>
                </div>
              </td>
              <td>100/190</td>
              <td>
                <div class="d-flex flex-column">
                  <small class="mb-1">90%</small>
                  <div class="progress w-100 me-3" style="height: 6px">
                    <div
                      class="progress-bar bg-success"
                      style="width: 90%"
                      aria-valuenow="90%"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
              </td>
              <td>129:45h</td>
            </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-between mx-4 row">
          <div class="col-sm-12 col-md-6">
            <div
              class="dataTables_info"
              id="DataTables_Table_0_info"
              role="status"
              aria-live="polite"
            >
              Showing 1 to 7 of 11 entries
            </div>
          </div>
          <div class="col-sm-12 col-md-6">
            <div
              class="dataTables_paginate paging_simple_numbers"
              id="DataTables_Table_0_paginate"
            >
              <ul class="pagination">
                <li
                  class="paginate_button page-item previous disabled"
                  id="DataTables_Table_0_previous"
                >
                  <a
                    aria-controls="DataTables_Table_0"
                    aria-disabled="true"
                    role="link"
                    data-dt-idx="previous"
                    tabindex="-1"
                    class="page-link"
                    >Previous</a
                  >
                </li>
                <li class="paginate_button page-item active">
                  <a
                    href="#"
                    aria-controls="DataTables_Table_0"
                    role="link"
                    aria-current="page"
                    data-dt-idx="0"
                    tabindex="0"
                    class="page-link"
                    >1</a
                  >
                </li>
                <li class="paginate_button page-item">
                  <a
                    href="#"
                    aria-controls="DataTables_Table_0"
                    role="link"
                    data-dt-idx="1"
                    tabindex="0"
                    class="page-link"
                    >2</a
                  >
                </li>
                <li
                  class="paginate_button page-item next"
                  id="DataTables_Table_0_next"
                >
                  <a
                    href="#"
                    aria-controls="DataTables_Table_0"
                    role="link"
                    data-dt-idx="next"
                    tabindex="0"
                    class="page-link"
                    >Next</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div style="width: 1%"></div>
      </div>
    </div> -->
  </div>
</template>

<style scoped></style>
