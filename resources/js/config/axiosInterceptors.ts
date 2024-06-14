import {
  errorsValidationToast,
  errorBackEndToast,
  errorAuthorizationToast,
} from "@/Components/utils/toast";

window.axios.interceptors.response.use(
  (response) => {
    console.log(response);
    console.log("xd");
    return response;
  },
  (error) => {
    console.log(error);

    const errors = error.response.data.errors;
    const status = error.response.status;

    if (errors) {
      errorsValidationToast(errors);
    } else if (status === 404) {
      const message = error.response.data.message;
      errorBackEndToast("Algo falló, intentelo más tarde");
    } else if (status === 401 || status === 403) {
      errorAuthorizationToast("No tienes permiso para esto");
    } else if (status >= 500) {
      errorBackEndToast("Hubo un error inténtelo nuevamente");
    }
    return Promise.reject(error);
  }
);
