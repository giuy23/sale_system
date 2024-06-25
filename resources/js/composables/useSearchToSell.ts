import { ClientToSell, ProductToSell } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useSearchToSell() {
  const loading = ref(false);
  const clients = ref<ClientToSell[]>([]);
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

  const searchClient = async (value: string | number) => {
    loading.value = true;

    try {
      const { data } = await axios<ClientToSell[]>({
        method: "GET",
        url: route("client.search"),
        params: { value },
      });


      clients.value = data;
      console.log(clients.value);
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
    clients,

    searchProduct,
    searchClient,
  };
}
