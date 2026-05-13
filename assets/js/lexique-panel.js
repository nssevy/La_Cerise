document.addEventListener("DOMContentLoaded", () => {
  const panel = document.getElementById("lexique-panel");
  if (!panel) return;

  const termes = document.querySelectorAll(".lexique-terme");
  if (!termes.length) return;

  const vus = new Set();
  const fragment = document.createDocumentFragment();

  const titre = document.createElement("h2");
  titre.textContent = "Termes clés";
  titre.className = "text-xl text-cerise-03";
  fragment.appendChild(titre);

  termes.forEach((terme) => {
    const mot = terme.textContent.trim();
    if (vus.has(mot)) return;
    vus.add(mot);

    const definition = terme.dataset.definition;

    const wrapper = document.createElement("div");
    wrapper.className = "flex flex-col gap-1";

    const label = document.createElement("span");
    label.textContent = mot;
    label.className = "text-small font-bold text-cerise-03";

    const desc = document.createElement("p");
    desc.textContent = definition;
    desc.className = "text-small text-cerise-05";

    wrapper.appendChild(label);
    wrapper.appendChild(desc);
    fragment.appendChild(wrapper);
  });

  panel.appendChild(fragment);
});
