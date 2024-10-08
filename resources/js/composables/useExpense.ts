import { Expense } from "@/types";
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

  return {
    loading,
    balance,

    getExpenses,
    createExpense,
    updateExpense,
    getBalanceToday,
  };
}
