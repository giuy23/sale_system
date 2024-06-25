import { SubCategory } from "@/types"
import axios from "axios"
import { ref } from "vue"

export function useSubCategory() {
  const loading = ref(false)

  const createSubCategorie = async(payload: SubCategory) => {
    loading.value = true
    try {
      const { data } =  await axios({
        method: "POST",
        url: route('subCategory.store'),
        data: payload
      })
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  }

  const updateteSubCategorie = async(payload: SubCategory) => {
    loading.value = true
    try {
      const { data } = await axios({
        method: "PUT",
        url: route('subCategory.update', payload.id),
        data: {...payload}
      })
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  }

  const deleteSubCategorie = async(id: number) => {
    loading.value = true
    try {
      await axios<{ success: boolean }>({
        method: "DELETE",
        url: route('subCategory.destroy', id)
      })
      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  }

  return {
    loading,

    createSubCategorie,
    updateteSubCategorie,
    deleteSubCategorie
  }
}
