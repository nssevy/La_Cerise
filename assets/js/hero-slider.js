// Slider hero — navigation par boutons uniquement (pas de drag).
// Deux présentations selon le breakpoint, pilotées par les mêmes boutons prev/next :
//  - Mobile : piste scrollable horizontale (data-hero-track), scroll animé ease in/out.
//  - Tablette/desktop : deck empilé (data-hero-deck) ; l'article courant passe devant,
//    l'autre passe derrière décalé à droite (profondeur). Swap simple, sans transition.

(() => {
	const DURATION = 550;

	// Résout cubic-bezier(0.42, 0, 0.58, 1) → y pour un x (temps normalisé) donné.
	const cx = 3 * 0.42, bx = 3 * (0.58 - 0.42) - cx, ax = 1 - cx - bx;
	const cy = 3 * 0, by = 3 * (1 - 0) - cy, ay = 1 - cy - by;
	const sampleX = (t) => ((ax * t + bx) * t + cx) * t;
	const sampleY = (t) => ((ay * t + by) * t + cy) * t;
	const solveX = (x) => {
		let t = x;
		for (let i = 0; i < 6; i++) {
			const d = ((3 * ax * t + 2 * bx) * t + cx) || 1e-6;
			t -= (sampleX(t) - x) / d;
			t = Math.max(0, Math.min(1, t));
		}
		return t;
	};
	const ease = (x) => sampleY(solveX(x));

	// Deck (tablette/desktop) — effet pile de cartes :
	//  - l'avant est devant, pleine hauteur ;
	//  - l'arrière est juste DERRIÈRE l'avant, décalé d'un petit cran à droite (overlap) → seul un
	//    mince filet de son image dépasse ; plus court en hauteur (centré) et estompé.
	const DECK_OFFSET = 30;        // décalage horizontal de la carte arrière (px)
	const DECK_BACK_TOP = '6%';    // décalage vertical → carte centrée
	const DECK_BACK_HEIGHT = '88%';
	const DECK_BACK_OPACITY = '0.5';

	document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
		const track = slider.querySelector('[data-hero-track]');
		const deck = slider.querySelector('[data-hero-deck]');
		const prev = slider.querySelector('[data-hero-prev]');
		const next = slider.querySelector('[data-hero-next]');
		const cycle = slider.querySelector('[data-hero-cycle]');

		const trackSlides = track ? [...track.children] : [];
		const deckCards = deck ? [...deck.querySelectorAll('[data-hero-card]')] : [];
		const count = Math.max(trackSlides.length, deckCards.length);

		if (count < 2) {
			[prev, next].forEach((b) => b && (b.disabled = true));
			if (cycle) cycle.style.display = 'none';
			// Une seule carte : on l'affiche au premier plan.
			deckCards.forEach((c) => { c.style.zIndex = '20'; });
			return;
		}

		let index = 0;
		let raf = null;

		const animateTo = (target) => {
			const start = track.scrollLeft;
			const max = track.scrollWidth - track.clientWidth;
			const end = Math.max(0, Math.min(max, target));
			const delta = end - start;
			if (raf) cancelAnimationFrame(raf);
			const t0 = performance.now();
			const step = (now) => {
				const p = Math.min(1, (now - t0) / DURATION);
				track.scrollLeft = start + delta * ease(p);
				if (p < 1) raf = requestAnimationFrame(step);
			};
			raf = requestAnimationFrame(step);
		};

		// Deck : carte courante devant (pleine hauteur), l'autre posée à droite (écart blanc), plus petite.
		const applyDeck = () => {
			deckCards.forEach((card, i) => {
				const front = i === index;
				card.style.zIndex = front ? '20' : '10';
				card.style.opacity = front ? '1' : DECK_BACK_OPACITY;
				card.style.top = front ? '0' : DECK_BACK_TOP;
				card.style.height = front ? '100%' : DECK_BACK_HEIGHT;
				card.style.left = front ? '0px' : DECK_OFFSET + 'px';
				card.style.pointerEvents = front ? 'auto' : 'none';
			});
		};

		const update = () => {
			if (prev) prev.disabled = index === 0;
			if (next) next.disabled = index === count - 1;
		};

		const go = (i) => {
			index = Math.max(0, Math.min(count - 1, i));
			if (track && trackSlides.length) {
				animateTo(trackSlides[index].offsetLeft - trackSlides[0].offsetLeft);
			}
			if (deck && deckCards.length) applyDeck();
			update();
		};

		prev && prev.addEventListener('click', () => go(index - 1));
		next && next.addEventListener('click', () => go(index + 1));
		// Flèche unique (desktop) : bascule cyclique entre les articles.
		cycle && cycle.addEventListener('click', () => go((index + 1) % count));

		// Le décalage de la carte de derrière dépend de sa largeur → recalcul au resize.
		if (deck && deckCards.length) {
			let resizeRaf = null;
			window.addEventListener('resize', () => {
				if (resizeRaf) cancelAnimationFrame(resizeRaf);
				resizeRaf = requestAnimationFrame(applyDeck);
			});
		}

		applyDeck();
		update();
	});
})();
