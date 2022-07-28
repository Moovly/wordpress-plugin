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
  toastSuccess: (text) => successToast(text, "#4cd964"),
  toastError: (text) => successToast(text, "#ff2d55"),
};
