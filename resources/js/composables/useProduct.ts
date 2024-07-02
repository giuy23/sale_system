import { ref } from "vue";
import axios from "axios";
import { Product, BestProductSold, ProductRestock } from "@/types";

export function useProduct() {
  const loading = ref(false);
  const productsRestock = ref<ProductRestock[]>([]);

  const createProduct = async (payload: Product) => {
    loading.value = true;
    try {
      const { data } = await axios<Product>({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("product.store"),
        data: payload,
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const changeState = async (id: number, state: boolean) => {
    loading.value = true;
    try {
      const { data } = await axios<Product>({
        method: "PUT",
        url: route("product.changeState", id),
        data: { state },
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
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("product.update", payload.id),
        data: { ...payload, _method: "PUT" },
      });

      return { success: true, data };
    } catch (error) {
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

  const getBestSellingProducts = async (date: string) => {
    loading.value = true;
    try {
      const { data } = await axios<BestProductSold[]>({
        method: "POST",
        url: route("product.bestSelling"),
        data: {
          date,
        },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const getProductsToRestock = async () => {
    loading.value = true;
    try {
      const { data } = await axios<ProductRestock[]>({
        method: "GET",
        url: route("product.restock"),
      });
      productsRestock.value = data;
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    productsRestock,

    createProduct,
    updateProduct,
    deleteProduct,
    changeState,
    getBestSellingProducts,
    getProductsToRestock,
  };
}
