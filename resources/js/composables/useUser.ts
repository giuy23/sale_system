import { UserType } from "@/types";
import axios from "axios";
import { ref } from "vue";

export function useUser() {
  const loading = ref(false);

  const createUser = async (payload: UserType) => {
    loading.value = true;
    try {
      const { data } = await axios<UserType>({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("user.store"),
        data: payload,
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const changeState = async (id: number, state: boolean) => {
    loading.value = true;
    try {
      const { data } = await axios<UserType>({
        method: "PUT",
        url: route("user.changeState", id),
        data: { state },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updateUser = async (payload: UserType) => {
    loading.value = true;
    try {
      const { data } = await axios<UserType>({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("user.update", payload.id),
        data: { ...payload, _method: "PUT" },
      });
      return { success: true, data };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const deleteUser = async (id: number) => {
    loading.value = true;
    try {
      await axios<{ success: boolean }>({
        method: "DELETE",
        url: route("user.destroy", id),
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

    createUser,
    updateUser,
    deleteUser,
    changeState,
  };
}
