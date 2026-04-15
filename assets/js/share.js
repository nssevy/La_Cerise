// Bouton de partage — copie l'URL courante dans le presse-papier
const shareBtn = document.querySelector('[data-action="share"]');

if (shareBtn) {
    shareBtn.addEventListener('click', function () {
        navigator.clipboard.writeText(window.location.href).then(function () {
            alert('Lien copié !');
        });
    });
}
