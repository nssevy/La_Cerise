// Sidebar lexique (desktop) : n'affiche que les définitions entières
// qui tiennent dans la hauteur disponible (= hauteur de la colonne articles).
(() => {
	const box = document.querySelector('[data-lexique-fit]');
	if (!box) return;

	const fit = () => {
		const max = box.clientHeight;
		const gap = parseFloat(getComputedStyle(box).rowGap) || 0;
		let used = 0;
		[...box.children].forEach((el, i) => {
			el.hidden = false;
			const h = el.offsetHeight + (i ? gap : 0);
			if (used + h > max) el.hidden = true;
			else used += h;
		});
	};

	addEventListener('load', fit);
	// Recalcule quand la hauteur change (images chargées, redimensionnement…)
	new ResizeObserver(fit).observe(box);
})();
