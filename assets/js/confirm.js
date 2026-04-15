// Confirmation avant suppression d'un article
document.querySelectorAll('[data-action="confirm-delete"]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        if (!confirm('Supprimer cet article ?')) {
            e.preventDefault();
        }
    });
});
