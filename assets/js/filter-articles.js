document.addEventListener('DOMContentLoaded', () => {
    const btns        = document.querySelectorAll('[data-filter]');
    const cards       = document.querySelectorAll('[data-rubrique]');
    const titre       = document.getElementById('filtre-titre');
    const grille      = document.getElementById('grille-articles');
    const msgVide     = document.getElementById('message-vide');

    const classesActif   = ['text-cerise-01', 'font-medium'];
    const classesInactif = ['border-transparent', 'text-cerise-05'];

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;

            btns.forEach(b => {
                b.classList.remove(...classesActif);
                b.classList.add(...classesInactif);
            });
            btn.classList.add(...classesActif);
            btn.classList.remove(...classesInactif);

            titre.textContent = filter === 'all' ? 'Tous' : filter;

            let visibles = 0;
            cards.forEach(card => {
                const visible = filter === 'all' || card.dataset.rubrique === filter;
                card.style.display = visible ? '' : 'none';
                if (visible) visibles++;
            });

            if (visibles === 0) {
                grille.classList.add('hidden');
                msgVide.classList.remove('hidden');
                msgVide.classList.add('flex');
            } else {
                grille.classList.remove('hidden');
                msgVide.classList.add('hidden');
                msgVide.classList.remove('flex');
            }
        });
    });
});
