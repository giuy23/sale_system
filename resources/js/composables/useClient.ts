import { Client } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useClient() {
  const loading = ref(false);

  const createClient = async (payload: Client) => {
    loading.value = true;
    try {
      const { data } = await axios<Client>({
        method: "POST",
        url: route("client.store"),
        data: payload,
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateClient = async (payload: Client) => {
    loading.value = true;
    try {
      const { data } = await axios<Client>({
        method: "PUT",
        url: route("client.update", payload.id),
        data: { ...payload },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };
  const deleteClient = async (id: number) => {
    loading.value = true;
    try {
      await axios<{ success: boolean }>({
        method: "DELETE",
        url: route("client.destroy", id),
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

    createClient,
    updateClient,
    deleteClient,
  };
}
