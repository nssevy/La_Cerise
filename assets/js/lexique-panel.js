document.addEventListener("DOMContentLoaded", () => {
  // Récupère le header (titre fixe) et le body (liste scrollable) du panel
  const header = document.getElementById("lexique-header");
  const body = document.getElementById("lexique-body");
  if (!header || !body) return;

  // Récupère tous les termes du lexique présents dans l'article
  const termes = document.querySelectorAll(".lexique-terme");
  if (!termes.length) return;

  // Crée et injecte le titre "Termes clés" dans le header fixe
  const titre = document.createElement("h2");
  titre.textContent = "Termes clés";
  titre.className = "text-xl text-cerise-03";
  header.appendChild(titre);

  // Set pour éviter les doublons (un même terme peut apparaître plusieurs fois dans l'article)
  const vus = new Set();

  termes.forEach((terme) => {
    // Lit uniquement le texte direct de l'élément, pas celui des éventuels enfants (abbr imbriquées)
    const mot =
      terme.childNodes[0]?.textContent?.trim() || terme.textContent.trim();

    // Ignore les termes déjà traités
    if (vus.has(mot)) return;
    vus.add(mot);

    // La définition est en HTML encodé dans data-definition
    // DOMParser permet de décoder proprement le HTML et d'en extraire le texte brut
    const rawDefinition = terme.dataset.definition;
    const definition = rawDefinition
      ? new DOMParser().parseFromString(rawDefinition, "text/html").body
          .textContent
      : "";

    // Construit le bloc terme + définition
    const wrapper = document.createElement("div");
    wrapper.className = "flex flex-col gap-1";

    const label = document.createElement("span");
    // Force la majuscule sur le premier caractère
    label.textContent = mot.charAt(0).toUpperCase() + mot.slice(1);
    label.className = "text-small font-bold text-cerise-03";

    const desc = document.createElement("p");
    desc.textContent = definition;
    desc.className = "text-small text-cerise-05";

    wrapper.appendChild(label);
    wrapper.appendChild(desc);
    body.appendChild(wrapper);
  });
});
