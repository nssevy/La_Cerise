// Table des matières — met en évidence le lien correspondant à la section visible
const tocLinks = document.querySelectorAll('nav[aria-label="Table des matières"] a');

if (tocLinks.length > 0) {
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                tocLinks.forEach(function (a) { a.classList.remove('toc-active'); });
                const lien = document.querySelector('nav[aria-label="Table des matières"] a[href="#' + entry.target.id + '"]');
                if (lien) lien.classList.add('toc-active');
            }
        });
    }, { rootMargin: '0px 0px -70% 0px' });

    document.querySelectorAll('.contenu h2').forEach(function (h2) {
        observer.observe(h2);
    });
}
