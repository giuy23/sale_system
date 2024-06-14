import { ProductToSell } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useSearchProduct() {
  const loading = ref(false);
  const products = ref<ProductToSell[]>([]);

  const searchProduct = async (value: string) => {
    loading.value = true;

    try {
      const { data } = await axios<ProductToSell[]>({
        method: "GET",
        url: route("product.search"),
        params: { value },
      });
      products.value = data;
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    products,
    searchProduct,
  };
}
