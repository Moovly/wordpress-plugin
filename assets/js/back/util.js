import Toastify from "toastify-js";

const successToast = (text, background) =>
  Toastify({
    text,
    duration: 2000,
    close: true,
    gravity: "bottom",
    position: "center",
    style: { background },
  }).showToast();

export default {
  toastSuccess: (text) => successToast(text, "#3c8f49"),
  toastError: (text) => successToast(text, "#d42242"),
};
