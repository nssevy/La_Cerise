// Effet machine à écrire sur la citation (Typed.js).
// Le texte est dans le HTML (data-typed-target) ; on le tape quand la
// section entre dans le viewport. Sans JS / sans Typed, le texte reste affiché.
(() => {
	const target = document.querySelector('[data-typed-target]');
	if (!target || typeof Typed === 'undefined') return;

	const text = target.textContent.trim();
	target.textContent = ''; // vidé tout de suite (JS dispo) → pas de flash

	let started = false;
	const start = () => {
		if (started) return;
		started = true;
		new Typed(target, {
			strings: [text],
			typeSpeed: 30,
			startDelay: 150,
			showCursor: true,
			cursorChar: '|',
		});
	};

	const observer = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				start();
				observer.disconnect();
			}
		});
	}, { threshold: 0.4 });

	observer.observe(target);
})();
