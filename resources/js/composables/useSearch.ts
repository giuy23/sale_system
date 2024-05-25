import { ref } from "vue"
import axios from "axios";

export function useSearch() {
  const loading = ref(false)

  const getSearchData = async (table: string, value?: string, ) => {
    loading.value = true;

    try {
      const { data } = await axios({
        url: route(`${table}.index`),
        params: {search: value}
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

    getSearchData,
  }
}
