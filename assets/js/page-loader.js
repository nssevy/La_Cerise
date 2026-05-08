(() => {
  const THRESHOLD = 300;
  const start = Date.now();
  let shown = false;
  let current = 0;
  let target = 0;
  let overlay, counter, interval, raf;

  function createOverlay() {
    overlay = document.createElement("div");
    overlay.id = "page-loader";
    overlay.style.cssText = `
      position: fixed;
      inset: 0;
      background: white;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity 0.4s ease;
    `;

    counter = document.createElement("span");
    counter.style.cssText = `
      font-size: 1.5rem;
      font-weight: 700;
      font-family: inherit;
      color: #0a0a0a;
      line-height: 1;
    `;
    counter.textContent = "0";

    overlay.appendChild(counter);
    document.documentElement.appendChild(overlay);
    shown = true;
  }

  function tick() {
    if (current < target) {
      current = Math.min(current + 1, target);
      if (counter) counter.textContent = current;
    }
    if (current < 100) {
      raf = requestAnimationFrame(tick);
    }
  }

  function updateProgress() {
    const resources = performance.getEntriesByType("resource");
    const total = resources.length;
    if (total === 0) return;

    const loaded = resources.filter((r) => r.responseEnd > 0).length;
    target = Math.min(Math.floor((loaded / total) * 95), 95);

    if (!shown && Date.now() - start >= THRESHOLD) {
      createOverlay();
      requestAnimationFrame(tick);
    }
  }

  interval = setInterval(updateProgress, 100);

  window.addEventListener("load", () => {
    clearInterval(interval);
    cancelAnimationFrame(raf);

    if (!shown) return;

    target = 100;

    function finish() {
      if (current < 100) {
        current = Math.min(current + 1, 100);
        counter.textContent = current;
        requestAnimationFrame(finish);
      } else {
        overlay.style.opacity = "0";
        setTimeout(() => overlay.remove(), 400);
      }
    }
    requestAnimationFrame(finish);
  });
})();
