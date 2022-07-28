import Toastify from "toastify-js";

export default {
  toastSuccess: (text) =>
    Toastify({
      text: "This is a toast",
      duration: 3000,
    }).showToast(),
  toastError: (text) =>
    Toastify({
      text: "This is a toast",
      duration: 3000,
    }).showToast(),
};
