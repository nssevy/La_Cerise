document.addEventListener("DOMContentLoaded", () => {
  // --- Dropzone ---
  const zone = document.getElementById("dropzone");
  const input = document.getElementById("image_principale");
  const preview = document.getElementById("dropzone-preview");

  zone.addEventListener("click", () => input.click());

  zone.addEventListener("dragover", (e) => {
    e.preventDefault();
    zone.classList.add("border-cerise-04");
  });

  zone.addEventListener("dragleave", () => {
    zone.classList.remove("border-cerise-04");
  });

  zone.addEventListener("drop", (e) => {
    e.preventDefault();
    zone.classList.remove("border-cerise-04");
    handleFile(e.dataTransfer.files[0]);
  });

  input.addEventListener("change", () => {
    handleFile(input.files[0]);
  });

  function handleFile(file) {
    if (!file) return;
    if (!["image/jpeg", "image/png"].includes(file.type)) {
      alert("Format non supporté. JPEG ou PNG uniquement.");
      return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.innerHTML = `<img src="${e.target.result}" class="max-h-40 mx-auto rounded">`;
    };
    reader.readAsDataURL(file);
  }

  // --- Form dirty ---
  const form = document.querySelector("form");
  const actionsClean = document.getElementById("form-actions-clean");
  const actionsDirty = document.getElementById("form-actions-dirty");

  function markDirty() {
    actionsClean.classList.add("hidden");
    actionsDirty.classList.remove("hidden");
  }

  form.addEventListener("input", markDirty);

  document.querySelectorAll("[data-contenteditable]").forEach((el) => {
    el.addEventListener("input", markDirty);
  });

  // Le drop d'une image compte aussi comme une modification
  input.addEventListener("change", markDirty);
});
