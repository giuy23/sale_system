import { Provider } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useProvider() {
  const loading = ref(false);

  const createProvider = async (payload: Provider) => {
    loading.value = true;
    try {
      const { data } = await axios({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("provider.store"),
        data: payload,
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateProvider = async (payload: Provider) => {
    loading.value = true;
    try {
      const { data } = await axios({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("provider.update", payload.id),
        data: { ...payload, _method: "PUT" },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const deleteProvider = async (id: number) => {
    loading.value = true
    try {
      await axios<{ success: boolean }>({
        method: "DELETE",
        url: route("provider.destroy", id),
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

    createProvider,
    updateProvider,
    deleteProvider,
  };
}
