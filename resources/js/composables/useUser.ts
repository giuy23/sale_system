import { UserType, UserUpdate } from "@/types";
import axios from "axios";
import { ref, computed } from "vue";

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

  const updateUser = async (payload: UserType | UserUpdate) => {
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

  const updateImageUser = async (image: File) => {
    try {
      await axios<{ success: boolean }>({
        headers: {
          "Content-Type": "multipart/form-data",
        },
        method: "POST",
        url: route("user.image"),
        data: { image, _method: "PUT" },
      });

      return { success: true };
    } catch (error) {
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const updatePassword = async (payload: any) => {
    loading.value = true;
    try {
      const { data } = await axios<UserType>({
        method: "PUT",
        url: route("user.password"),
        data: { ...payload },
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

  const logoutUser = async () => {
    try {
      await axios({
        method: "POST",
        url: route("logout"),
      });
      return { success: true };
    } catch (error) {
      return { success: false };
    }
  };

  const roleText = computed(() => (role_id: number) => {
    let role = "Administrador";
    if (role_id == 2) {
      role = "Vendedor";
    }
    return role;
  });

  return {
    loading,
    roleText,

    createUser,
    updateUser,
    updatePassword,
    deleteUser,
    changeState,
    logoutUser,
    updateImageUser,
  };
}
