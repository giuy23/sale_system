import { ref } from "vue";
import axios from "axios";
import { Product } from "@/types";

export function useProduct() {
  const loading = ref(false);

  const createProduct = async (payload: Product) => {
    loading.value = true;
    try {
      const { data } = await axios<Product>({
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        method: 'POST',
        url: route('product.store'),
        data: payload
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateProduct = async (payload: Product) => {
    loading.value = true;
    try {
      const { data } = await axios<Product>({
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        method: 'POST',
        url: route('product.update', payload.id),
        data: {...payload, _method: 'PUT'}
      });

      return { success: true, data };
    } catch (error) {
      console.log(error);

      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const deleteProduct = async (id: number) => {
    loading.value = true;
    try {
      await axios<{ success: boolean }>({
        method: "DELETE",
        url: route("product.destroy", id),
      });
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,

    createProduct,
    updateProduct,
    deleteProduct,
  };
}
