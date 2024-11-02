<script lang="ts" setup>
import { Product } from "@/types";
import { ref, reactive, watch } from "vue";
import { useSearch } from "@/composables/useSearch";
import db from "just-debounce";
import { SubCategory } from "@/types";
import { Provider } from "@/types";
import { useProduct } from "@/composables/useProduct";

const { createProduct, updateProduct, loading } = useProduct();
const { getSearchData } = useSearch();
const props = defineProps<{
  product: Product | null;
  reset: boolean;
}>();

const emits = defineEmits<{
  created: [Product];
  updated: [Product];
  reset: [void];
}>();

const initialProduct: Product = {
  id: 0,
  name: "",
  description: "",
  purchase_price: 0,
  sale_price: 0,
  bar_code: "",
  quantity: 1,
  minimum_quantity: 1,
  sub_category_id: 0,
  provider_id: 0,

  image: "",
  // images: [],
};
const modalRef = ref(false);
const subCategories = ref<SubCategory[]>();
const providers = ref<Provider[]>();

// interface ImageFile extends File {
//   lastModified: number;
//   lastModifiedDate?: any;
//   name: string;
//   size: number;
//   type: string;
//   webkitRelativePath: string;
// }

// const images = ref<ImageFile[]>([]);
const images = ref([]);
const image = ref<File | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);
let productForm = reactive({ ...initialProduct });

watch(
  () => props.product,
  async (value) => {
    if (value) {
      Object.assign(productForm, value);
      await getSubCategories(value!.sub_category_id);
      await getProviders(value!.provider_id);
    }
  }
);

watch(
  () => props.reset,
  () => {
    closeModal();
  }
);

const getSubCategories = db(async (value: string | number) => {
  const { success, data } = await getSearchData("subCategory", value, [
    "name",
    "id",
  ]);
  if (success) {
    subCategories.value = data!.data;
  }
}, 450);

const getProviders = db(async (value: string | number) => {
  const { success, data } = await getSearchData("provider", value, [
    "name",
    "id",
  ]);
  if (success) {
    providers.value = data!.data;
  }
}, 450);

const closeModal = () => {
  const modalInstance = bootstrap.Modal.getInstance(modalRef.value!);
  modalInstance?.hide();

  Object.assign(productForm, { ...initialProduct });
  image.value = null;
  fileInput.value!.value = "";
  emits("reset");
};

const handleSaveProduct = () => {
  productForm.id ? handleEditProduct() : handleCreateProduct();
};

const handleCreateProduct = async () => {
  const { success, data } = await createProduct({
    ...productForm,
    image: image.value!,
    images: images.value,
  });
  if (success) {
    emits("created", data!);
    closeModal();
  }
};
const handleEditProduct = async () => {
  const { success, data } = await updateProduct({
    ...productForm,
    image: image.value!,
    images: images.value,
  });
  if (success) {
    emits("updated", data!);
    closeModal();
  }
};

const getSubCategoriesVueSelect = (data: string | number) => {
  let search;
  typeof data === "number" ? (search = data) : (search = data.trim());
  if (!search) return;
  getSubCategories(search);
};

const getProvidersVueSelect = (data: string | number) => {
  let search;
  typeof data === "number" ? (search = data) : (search = data.trim());
  if (!search) return;
  getProviders(search);
};

const handleInputOneImage = (e: Event) => {
  const file = (e.target as HTMLInputElement).files![0];
  image.value = file;
};

// const handleInputManyImages = (e: Event) => {
//   const file = (e.target as HTMLInputElement).files!;
//   if (file.length > 3) return console.log("solo 3");

//   for (let i = 0; i < file.length; i++) {
//     images.value[i] = file[i];
//   }
// };
</script>

<template>
  <div
    ref="modalRef"
    class="modal fade"
    id="backDropModal"
    data-bs-backdrop="static"
    tabindex="-1"
  >
    <div class="modal-dialog modal-lg">
      <form
        class="modal-content"
        @submit.prevent="handleSaveProduct"
        enctype="multipart/form-data"
      >
        <div class="modal-header">
          <h5 class="modal-title" id="backDropModalTitle">Proveedores</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeModal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-md-6 mb-3">
              <label for="name" class="form-label">Nombre*</label>
              <input
                type="text"
                id="name"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.name"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="bar_code" class="form-label">Código de barras</label>
              <input
                type="text"
                id="bar_code"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.bar_code"
              />
            </div>
            <div class="col-12 mb-3">
              <label for="description" class="form-label">Descripción</label>
              <input
                type="text"
                id="description"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.description"
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="purchase_price" class="form-label"
                >Precio de Compra*</label
              >
              <input
                type="number"
                id="purchase_price"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.purchase_price"
                min="0"
                step="0.01"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="sale_price" class="form-label"
                >Precio de Venta*</label
              >
              <input
                type="number"
                id="sale_price"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.sale_price"
                min="0"
                step="0.01"
                required
              />
            </div>

            <div class="col-12 col-md-6 mb-3">
              <label for="quantity" class="form-label">Cantidad *</label>
              <input
                type="number"
                id="quantity"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.quantity"
                min="1"
                required
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="minimum_quantity" class="form-label"
                >Cantidad Mínima</label
              >
              <input
                type="number"
                id="minimum_quantity"
                class="form-control"
                placeholder="Escriba el nombre"
                v-model="productForm.minimum_quantity"
                min="1"
              />
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="minimum_quantity" class="form-label"
                >Sub Categoría*</label
              >
              <v-select
                :options="subCategories"
                :getOptionLabel="(data: SubCategory) => data.name"
                v-model="productForm.sub_category_id"
                :reduce="(data: SubCategory) => data.id"
                :clearable="false"
                @search="getSubCategoriesVueSelect"
              >
                <template #search="{ attributes, events }">
                  <input
                    class="vs__search"
                    :required="!productForm.sub_category_id"
                    v-bind="attributes"
                    v-on="events"
                  />
                </template>
                <template #no-options="{ search }">
                  <span>No hay Resultados para {{ search }}</span>
                </template>
              </v-select>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="minimum_quantity" class="form-label"
                >Proveedor*</label
              >
              <v-select
                :options="providers"
                :getOptionLabel="(data: Provider) => data.name"
                v-model="productForm.provider_id"
                :reduce="(data: Provider) => data.id"
                :clearable="false"
                @search="getProvidersVueSelect"
              >
                <template #search="{ attributes, events }">
                  <input
                    class="vs__search"
                    :required="!productForm.provider_id"
                    v-bind="attributes"
                    v-on="events"
                  />
                </template>
                <template #no-options="{ search }">
                  <span>No hay Resultados para {{ search }}</span>
                </template>
              </v-select>
            </div>
          </div>

          <div class="row g-2">
            <div class="col-12 mb-3">
              <label for="image" class="form-label">Imagen Principal</label>
              <input
                type="file"
                accept="image/*"
                id="image"
                class="form-control"
                @input="handleInputOneImage"
                ref="fileInput"
              />
            </div>
          </div>
          <!-- <div class="row g-2">
            <div class="col-12 mb-3">
              <label for="image" class="form-label">Imagen Secundarias</label>
              <input
                type="file"
                accept="image/*"
                id="image"
                class="form-control"
                placeholder="Escriba el nombre"
                @input="handleInputManyImages"
                multiple
                required
              />
            </div>
          </div> -->
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            @click="closeModal"
          >
            Cerrar
          </button>
          <button type="submit" :disabled="loading" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
