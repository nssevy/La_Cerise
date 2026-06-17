// Slider hero — navigation par boutons uniquement (pas de drag).
// Boutons prev/next, désactivés en bout de course.
(() => {
	document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
		const track = slider.querySelector('[data-hero-track]');
		const prev = slider.querySelector('[data-hero-prev]');
		const next = slider.querySelector('[data-hero-next]');
		if (!track) return;

		const slides = [...track.children];
		if (slides.length < 2) {
			[prev, next].forEach((b) => b && (b.disabled = true));
			return;
		}

		let index = 0;

		const update = () => {
			if (prev) prev.disabled = index === 0;
			if (next) next.disabled = index === slides.length - 1;
		};

		const go = (i) => {
			index = Math.max(0, Math.min(slides.length - 1, i));
			track.scrollTo({
				left: slides[index].offsetLeft - slides[0].offsetLeft,
				behavior: 'smooth',
			});
			update();
		};

		prev && prev.addEventListener('click', () => go(index - 1));
		next && next.addEventListener('click', () => go(index + 1));

		update();
	});
})();
