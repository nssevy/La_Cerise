const shareBtn = document.querySelector('[data-action="share"]');

if (shareBtn) {
  shareBtn.addEventListener("click", async function () {
    if (navigator.share) {
      try {
        await navigator.share({
          title: document.title,
          url: window.location.href,
        });
      } catch (err) {
        if (err.name !== "AbortError") {
          console.error("Erreur de partage :", err);
        }
      }
    } else {
      // Fallback : copie l'URL dans le presse-papier
      navigator.clipboard.writeText(window.location.href).then(function () {
        alert("Lien copié !");
      });
    }
  });
}
