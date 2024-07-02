import { DailyCash, DailyCashCreate, DailyCashCompare } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useDailyCash() {
  const loading = ref(false);
  const profit = ref<number | null>(null);

  const createDailyCash = async (payload: DailyCashCreate) => {
    loading.value = true;
    try {
      const { data } = await axios<DailyCash>({
        method: "POST",
        url: route("dailyCash.store"),
        data: payload,
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const changeStateDailyCash = async (id: number, state: boolean) => {
    loading.value = true;
    try {
      const { data } = await axios<DailyCash>({
        method: "PUT",
        url: route("dailyCash.status", id),
        data: { state },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const getLastDailyCashes = async () => {
    loading.value = true;
    try {
      const { data } = await axios<DailyCashCompare[]>({
        method: "GET",
        url: route("dailyCash.getLastCashes"),
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const getProfit = async () => {
    loading.value = true;
    try {
      const { data } = await axios<number>({
        method: "GET",
        url: route("dailyCash.profit"),
      });
      profit.value = data;
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    profit,

    createDailyCash,
    changeStateDailyCash,
    getLastDailyCashes,
    getProfit,
  };
}
