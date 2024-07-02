import { Enterprise } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useEnterprise() {
  const loading = ref(false);

  const updateEnterprise = async (payload: Enterprise) => {
    try {
      const { data } = await axios<Enterprise>({
        method: "PUT",
        url: route("enterprise.update", payload.id),
        data: { ...payload },
      });

      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateImageEnterprise = async (payload: {
    enterprise_id: number;
    image: File;
  }) => {
    try {
      await axios<{success: boolean}>({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("enterprise.image"),
        data: { ...payload, _method: "PUT" },
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
    updateEnterprise,
    updateImageEnterprise,
  };
}
