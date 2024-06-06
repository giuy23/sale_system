import { DailyCash, DailyCashCreate } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useDailyCash() {
  const loading = ref(false);

  const createDailyCash = async (payload: DailyCashCreate) => {
    loading.value = true;
    try {
      const { data } = await axios<DailyCash>({
        method: "POST",
        url: route("dailyCash.create"),
        data: payload,
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const changeStateDailyCash = async(id: number, state: boolean) => {
    loading.value = true;
    try {
      const { data } = await axios<DailyCash>({
        method: 'PUT',
        url: route('dailyCash.status', id),
        data: {state}
      })
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  }

  return {
    loading,

    createDailyCash,
    changeStateDailyCash,
  };
}
