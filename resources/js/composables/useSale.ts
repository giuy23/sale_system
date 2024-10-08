import axios from "axios";
import { ref } from "vue";

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
      console.log(data);

      totalSalesToday.value = data;
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    totalSalesToday,

    cancelSale,
    payDebtForTheSale,
    payAllDebts,
    getTotalAmountToday,
  };
}
