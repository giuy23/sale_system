import axios from "axios";
import { ref } from "vue";
import { useSearch } from "./useSearch";
import { SearchByDate } from "@/types";

const { searchData, getDate, getDatoToStorage } = useSearch();
export function useSale() {
  const loading = ref(false);
  const totalSalesToday = ref<number>();

  const cancelSale = async (id: number) => {
    loading.value = true;
    try {
      const { data } = await axios<{}>({
        method: "GET",
        url: route("sale.cancelSale", id),
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const payDebtForTheSale = async (payload: { id: number; amount: number }) => {
    loading.value = true;
    try {
      await axios<{}>({
        method: "POST",
        url: route("creditSale.payOneDebt", payload.id),
        data: { amount: payload.amount },
      });

      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const payAllDebts = async (creditSaleIds: Object) => {
    loading.value = true;
    try {
      await axios<{}>({
        method: "POST",
        url: route("creditSale.payAllDebt"),
        data: { creditSaleIds },
      });

      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const getTotalAmountToday = async () => {
    loading.value = true;
    try {
      const { data } = await axios<number>({
        method: "GET",
        url: route("sale.totalSales"),
      });
      totalSalesToday.value = data;
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
        url: `${route("sale.export")}?type=${type}`,
        responseType: "blob",
        params: { ...searchData },
      });

      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      const dateStr = getDate();

      if (type === "excel") {
        link.setAttribute("download", `reporte-de-venta-${dateStr}.xlsx`);
      } else if (type === "pdf") {
        link.setAttribute("download", `reporte-de-venta-${dateStr}.pdf`);
      }

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      return { success: true };
    } catch (error) {
      return { success: false };
    }
  };

  const exportDataReportDailySales = async (
    type: string,
    payload: SearchByDate
  ) => {
    try {
      const response = await axios({
        url: `${route("sale.export")}?type=${type}`,
        responseType: "blob",
        params: { ...payload },
      });

      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;

      if (type === "excel") {
        link.setAttribute(
          "download",
          `reporte-del-dia-${payload.start_date}.xlsx`
        );
      } else if (type === "pdf") {
        link.setAttribute(
          "download",
          `reporte-del-dia-${payload.start_date}.pdf`
        );
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
    totalSalesToday,

    cancelSale,
    payDebtForTheSale,
    payAllDebts,
    getTotalAmountToday,
    exportData,
    exportDataReportDailySales,
    getDatoToStorage,
  };
}
