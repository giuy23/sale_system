import { Category } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useCategory() {
  const loading = ref(false);

  const createCategory = async (payload: Category) => {
    loading.value = true;

    try {
      const { data } = await axios<Category>({
        method: "POST",
        url: route("category.create"),
        data: payload,
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateCategory = async (payload: Category) => {
    loading.value = true;

    try {
      const { data } = await axios<Category>({
        method: "PUT",
        url: route("category.update", payload.id),
        data: {...payload},
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const deleteCategory = async (id: number) => {
    loading.value = true;

    try {
      await axios<{ success: boolean }>({
        method: "DELETE",
        url: route("category.delete", id),
      });
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,

    createCategory,
    updateCategory,
    deleteCategory,
  };
}
