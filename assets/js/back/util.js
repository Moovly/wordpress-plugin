import Toastify from "toastify-js";

const successToast = (text, background, type) =>
  Toastify({
    text,
    duration: 2000,
    close: true,
    gravity: "bottom",
    className: `toast-${type}`,
    position: "center",
    style: { background },
  }).showToast();

export default {
  toastSuccess: (text) => successToast(text, "#3c8f49", "success"),
  toastError: (text) => successToast(text, "#d42242", "error"),
};
