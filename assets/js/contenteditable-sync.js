// assets/js/contenteditable-sync.js
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-contenteditable]").forEach((display) => {
    const field = display.dataset.contenteditable;
    const input = document.getElementById(field);
    display.addEventListener("input", () => {
      input.value = display.innerText.trim();
    });
  });
});
