import { reactive, ref } from "vue";
import axios from "axios";
import { GetDataWithParams, SearchByDate } from "@/types";

export function useSearch() {
  const loading = ref(false);
  const initialSearch: SearchByDate = {
    start_date: "",
    end_date: "",
    minimum_amount: null,
    maximum_amount: null,
  };

  const searchData = reactive({ ...initialSearch });

  const getDate = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
  };

  const getDatoToStorage = async (storage: string) => {
    const savedData = localStorage.getItem(storage);
    if (savedData) {
      Object.assign(searchData, JSON.parse(savedData));
    }
  };

  const getSearchData = async (
    table: string,
    value?: string | number,
    searchBy: string[] = []
  ) => {
    loading.value = true;

    try {
      const { data } = await axios<GetDataWithParams>({
        url: route(`${table}.index`),
        params: { search: value, searchBy },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const getSearchByDate = async (model: string, payload: SearchByDate) => {
    loading.value = true;
    try {
      const { data } = await axios<GetDataWithParams>({
        url: route(`${model}.index`),
        params: { ...payload },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    initialSearch,
    searchData,

    getDate,
    getSearchData,
    getSearchByDate,
    getDatoToStorage,
  };
}
