import { DailyCash, DailyCashCreate, DailyCashCompare } from "@/types";
import axios from "axios";
import { ref } from "vue";
import { useSearch } from "./useSearch";

const { searchData, getDate, getDatoToStorage } = useSearch();

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

  const exportData = async (type: string, storage: string) => {
    await getDatoToStorage(storage);
    try {
      const response = await axios({
        url: `${route("dailyCash.export")}?type=${type}`,
        responseType: "blob",
        params: { ...searchData },
      });

      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      const dateStr = getDate();

      if (type === "excel") {
        link.setAttribute("download", `reporte-de-caja-${dateStr}.xlsx`);
      } else if (type === "pdf") {
        link.setAttribute("download", `reporte-de-caja-${dateStr}.pdf`);
      }

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      return { success: true };
    } catch (error) {
      return { success: false };
    }
  };

  return {
    loading,
    profit,

    createDailyCash,
    changeStateDailyCash,
    getLastDailyCashes,
    getProfit,
    exportData,
  };
}
