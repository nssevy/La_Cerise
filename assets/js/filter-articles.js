document.addEventListener('DOMContentLoaded', () => {
    const btns        = document.querySelectorAll('[data-filter]');
    const cards       = document.querySelectorAll('[data-rubrique]');
    const titre       = document.getElementById('filtre-titre');
    const grille      = document.getElementById('grille-articles');
    const msgVide     = document.getElementById('message-vide');
    const count       = document.getElementById('filtre-count');

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

            titre.textContent = btn.dataset.nom ?? 'Tous';

            let visibles = 0;
            cards.forEach(card => {
                const visible = filter === 'all' || card.dataset.rubrique === filter;
                card.style.display = visible ? '' : 'none';
                if (visible) visibles++;
            });

            count.textContent = visibles + ' article' + (visibles > 1 ? 's' : '');

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

    // Activation automatique du filtre depuis l'URL (?rubrique=...)
    const rubriqueUrl = new URLSearchParams(window.location.search).get('rubrique');
    if (rubriqueUrl) {
        const btnCible = [...btns].find(b => b.dataset.filter === rubriqueUrl);
        if (btnCible) btnCible.click();
    }

    // --- Tri ---
    const sortBtn      = document.getElementById('sort-btn');
    const sortDropdown = document.getElementById('sort-dropdown');
    const sortArrow    = document.getElementById('sort-arrow');
    const sortRadios   = document.querySelectorAll('input[name="sort"]');

    function positionnerDropdown() {
        const isMobile = window.innerWidth < 768;
        if (isMobile) {
            const rect = sortBtn.getBoundingClientRect();
            sortDropdown.style.position = 'fixed';
            sortDropdown.style.top      = rect.bottom + 'px';
            sortDropdown.style.left     = '0';
            sortDropdown.style.right    = '0';
            sortDropdown.style.width    = 'auto';
        } else {
            sortDropdown.style.position = '';
            sortDropdown.style.top      = '';
            sortDropdown.style.left     = '';
            sortDropdown.style.right    = '';
            sortDropdown.style.width    = '';
        }
    }

    function ouvrirDropdown() {
        positionnerDropdown();
        sortDropdown.classList.remove('hidden', 'is-closing');
        sortDropdown.classList.add('is-opening');
    }

    function fermerDropdown() {
        if (sortDropdown.classList.contains('hidden')) return;
        sortDropdown.classList.remove('is-opening');
        sortDropdown.classList.add('is-closing');
        sortDropdown.addEventListener('animationend', () => {
            sortDropdown.classList.remove('is-closing');
            sortDropdown.classList.add('hidden');
        }, { once: true });
    }

    sortBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sortDropdown.classList.contains('hidden') ? ouvrirDropdown() : fermerDropdown();
    });

    document.addEventListener('click', () => fermerDropdown());
    window.addEventListener('scroll', () => fermerDropdown(), { passive: true });

    function appliquerTri(ordre) {
        const items = [...grille.querySelectorAll('[data-date]')];
        items.sort((a, b) => {
            const da = new Date(a.dataset.date);
            const db = new Date(b.dataset.date);
            return ordre === 'asc' ? da - db : db - da;
        });
        items.forEach(item => grille.appendChild(item));
    }

    sortRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            appliquerTri(radio.value);
            sortDropdown.classList.add('hidden');
        });
    });
});
