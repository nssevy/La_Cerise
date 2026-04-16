// Stagger text animation — effet vague en boucle avec pause
// Chaque lettre monte et descend en décalé (wave effect).
// Cible tous les éléments portant [data-stagger-text].
(function () {
    const elements = document.querySelectorAll('[data-stagger-text]');
    if (!elements.length) return;

    elements.forEach(function (el) {
        const text = el.textContent;

        // Découpe chaque lettre dans un <span>
        el.innerHTML = text.split('').map(function (char) {
            return '<span style="display:inline-block">'
                + (char === ' ' ? '&nbsp;' : char)
                + '</span>';
        }).join('');

        const letters = el.querySelectorAll('span');

        // Timeline en boucle infinie avec 1s de pause entre chaque vague
        const tl = gsap.timeline({
            repeat: -1,       // boucle infinie
            repeatDelay: 1    // 1 seconde de pause avant de relancer la vague
        });

        tl.to(letters, {
            y: -8,            // monte de 8px
            duration: 0.6,    // vitesse de montée (plus lent)
            ease: 'sine.inOut',
            yoyo: true,       // redescend après la montée
            repeat: 1,        // 1 aller-retour (montée + descente)
            stagger: {
                each: 0.12    // 120ms de décalage entre chaque lettre
            }
        });
    });
}());
