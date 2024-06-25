import { markRaw } from "vue";
import { toast } from "vue-sonner";
import Toast from "./Toast.vue";
import Swal from "sweetalert2";

interface ErrorData {
  [key: string]: string[];
}

export const errorBackEndToast = (msg: string) => {
  toast.error(msg);
};
export const errorAuthorizationToast = (msg: string) => {
  toast.error(msg);
};
export const errorsValidationToast = (data: ErrorData) => {
  console.log(data);

  let message = "";
  for (const field in data) {
    const errorMessages = data[field]
      .map((error) => `<li>${error}</li>`)
      .join("");
    message += `<ul>${errorMessages}</ul>`;
  }
  toast.error(markRaw(Toast), {
    style: {
      background: "#F5B7B1",
    },
    class: "my-toast",
    descriptionClass: "my-toast-description",
    componentProps: {
      message: `Corrija estos campos: ${message}`,
    },
  });
};

export const toastSuccess = (msg: string) => {
  toast.success(msg);
};

export const toastInfo = (msg: string) => {
  toast.info(msg);
};

export const confirmAction = (msg: string): Promise<boolean> => {
  return new Promise((resolve) => {
    Swal.fire({
      title: "¿Estás seguro?",
      text: msg ?? "Estas seguro de realizar esta acción!",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, continuar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      resolve(result.isConfirmed);
    });
  });
};

export const confirmDelete = (msg?: string): Promise<boolean> => {
  return new Promise((resolve) => {
    Swal.fire({
      title: "¿Estás seguro?",
      text: msg ?? "¡No podrás revertir esta acción!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, borrar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      resolve(result.isConfirmed);
    });
  });
};
