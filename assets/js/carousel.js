// Carousel horizontal infini — page d'accueil
// Technique ×3 : on duplique tout le tableau 3 fois.
// Structure : [copie1][milieu][copie2]
// On navigue dans le milieu. Quand on déborde dans une copie,
// on snappe instantanément vers l'équivalent du milieu.
(function () {
    const track = document.getElementById('carouselTrack');
    if (!track) return;

    const realCards = Array.from(track.querySelectorAll('.carousel-card'));
    const total = realCards.length; // nombre de vraies cartes

    // --- Duplication ×3 ---
    // copie1 insérée avant, copie2 insérée après
    const copy1 = realCards.map(function (c) { return c.cloneNode(true); });
    const copy2 = realCards.map(function (c) { return c.cloneNode(true); });

    copy2.forEach(function (c) { track.appendChild(c); });
    copy1.slice().reverse().forEach(function (c) { track.insertBefore(c, track.firstChild); });

    // Ordre final : [c1_0…c1_n | real_0…real_n | c2_0…c2_n]
    // Indices     :  0    …  total-1 | total … 2*total-1 | 2*total … 3*total-1
    const allCards = Array.from(track.querySelectorAll('.carousel-card'));

    // On démarre sur la première carte du set milieu
    let current     = total;
    let isAnimating = false;

    // Dimensions desktop (≥ 1024px) — maquette Figma
    const DESKTOP = { cardWidth: 800, gap: 60 };

    function isMobile() {
        return window.innerWidth < 1024;
    }

    function getOffset(index) {
        if (isMobile()) {
            const gap        = 10;
            const leftMargin = 20;
            const cardWidth  = window.innerWidth - 2 * leftMargin;
            return leftMargin - index * (cardWidth + gap);
        }
        const initialOffset = (window.innerWidth - DESKTOP.cardWidth) / 2;
        return initialOffset - index * (DESKTOP.cardWidth + DESKTOP.gap);
    }

    function setActiveCard(index) {
        allCards.forEach(function (card, i) {
            const body    = card.querySelector('.carousel-card__body');
            const isActive = i === index;

            card.classList.toggle('is-active', isActive);

            if (isActive) {
                gsap.fromTo(body,
                    { opacity: 0, y: 12 },
                    { opacity: 1, y: 0, duration: 0.4, delay: 0.3, ease: 'power2.out' }
                );
            } else {
                gsap.set(body, { opacity: 0, y: 0 });
            }
        });
    }

    function goTo(index) {
        if (isAnimating) return;
        isAnimating = true;
        current = index;

        // On n'active pas les cartes des copies pour éviter le double fade-in
        const isCopy = current < total || current >= 2 * total;
        if (!isCopy) {
            setActiveCard(current);
        }

        gsap.to(track, {
            x: getOffset(current),
            duration: 0.8,
            ease: 'expo.out',
            onComplete: function () {
                // Débordement vers la copie1 → snap vers l'équivalent milieu
                if (current < total) {
                    current += total;
                    gsap.set(track, { x: getOffset(current) });
                    setActiveCard(current);
                }
                // Débordement vers la copie2 → snap vers l'équivalent milieu
                else if (current >= 2 * total) {
                    current -= total;
                    gsap.set(track, { x: getOffset(current) });
                    setActiveCard(current);
                }

                isAnimating = false;
            }
        });
    }

    document.querySelector('[data-action="carousel-prev"]').addEventListener('click', function () {
        goTo(current - 1);
    });

    document.querySelector('[data-action="carousel-next"]').addEventListener('click', function () {
        goTo(current + 1);
    });

    // =====================================================
    // GRAB / SWIPE SCROLL
    // =====================================================

    let dragStartX   = 0;
    let dragCurrentX = 0;
    let isDragging   = false;
    let startTrackX  = 0;
    const DRAG_THRESHOLD = 50;

    function onDragStart(clientX) {
        if (isAnimating) return;
        isDragging   = true;
        dragStartX   = clientX;
        dragCurrentX = clientX;
        startTrackX  = gsap.getProperty(track, 'x');
        gsap.killTweensOf(track);
        track.style.cursor = 'grabbing';
    }

    function onDragMove(clientX) {
        if (!isDragging) return;
        dragCurrentX = clientX;
        gsap.set(track, { x: startTrackX + (dragCurrentX - dragStartX) });
    }

    function onDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        track.style.cursor = '';

        const delta = dragCurrentX - dragStartX;

        if (delta < -DRAG_THRESHOLD) {
            goTo(current + 1);
        } else if (delta > DRAG_THRESHOLD) {
            goTo(current - 1);
        } else {
            gsap.to(track, { x: getOffset(current), duration: 0.4, ease: 'power2.out' });
        }
    }

    // Touch (mobile)
    track.addEventListener('touchstart',  function (e) { onDragStart(e.touches[0].clientX); }, { passive: true });
    track.addEventListener('touchmove',   function (e) { onDragMove(e.touches[0].clientX);  }, { passive: true });
    track.addEventListener('touchend',    function ()  { onDragEnd(); });
    track.addEventListener('touchcancel', function ()  { onDragEnd(); });

    // Souris (desktop)
    track.addEventListener('mousedown', function (e) {
        onDragStart(e.clientX);
        e.preventDefault();
    });
    window.addEventListener('mousemove', function (e) { onDragMove(e.clientX); });
    window.addEventListener('mouseup',   function ()  { onDragEnd(); });

    // Empêche la navigation si l'utilisateur a glissé
    track.addEventListener('click', function (e) {
        if (Math.abs(dragCurrentX - dragStartX) > 5) {
            e.preventDefault();
        }
    });

    // Recalcul au redimensionnement
    window.addEventListener('resize', function () {
        gsap.set(track, { x: getOffset(current) });
    });

    // Initialisation
    gsap.set(track, { x: getOffset(current) });
    setActiveCard(current);
}());
