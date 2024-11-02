import { Expense, SearchByDate } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useExpense() {
  const loading = ref(false);
  const balance = ref<{ entries: number; exits: number }>();

  const getExpenses = async (id: number) => {
    loading.value = true;
    try {
      const { data } = await axios<Expense>({
        method: "GET",
        url: route("expense.index"),
        params: { id },
      });

      return { success: false, data };
    } catch (error) {
      console.log(error);

      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const createExpense = async (payload: Expense) => {
    loading.value = true;
    try {
      const { data } = await axios<Expense>({
        method: "POST",
        url: route("expense.store"),
        data: payload,
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateExpense = async (payload: Expense) => {
    loading.value = true;
    try {
      const { data } = await axios<Expense>({
        method: "PUT",
        url: route("expense.update", payload.id),
        data: { ...payload },
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const getBalanceToday = async () => {
    loading.value = true;
    try {
      const { data } = await axios<{ entries: number; exits: number }>({
        method: "GET",
        url: route("expense.balance"),
      });
      balance.value = data;
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const exportDataReportDailyExpenses = async (
    type: string,
    payload: SearchByDate
  ) => {
    try {
      const response = await axios({
        url: `${route("expense.export")}?type=${type}`,
        responseType: "blob",
        params: { ...payload },
      });

      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;

      if (type === "excel") {
        link.setAttribute(
          "download",
          `movimiento-del-dia-${payload.start_date}.xlsx`
        );
      } else if (type === "pdf") {
        link.setAttribute(
          "download",
          `movimiento-del-dia-${payload.start_date}.pdf`
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
    balance,

    getExpenses,
    createExpense,
    updateExpense,
    getBalanceToday,
    exportDataReportDailyExpenses,
  };
}
