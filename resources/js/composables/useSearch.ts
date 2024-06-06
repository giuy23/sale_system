import { ref } from "vue";
import axios from "axios";
import { GetDataWithParams } from "@/types";

export function useSearch() {
  const loading = ref(false);

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

  return {
    loading,

    getSearchData,
  };
}
