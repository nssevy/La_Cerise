const COOKIE_NAME = "cerise_consent";
const COOKIE_MAX_AGE_ACCEPTED = 60 * 60 * 24 * 390; // 13 mois, max recommandé CNIL
const COOKIE_MAX_AGE_REFUSED = 60 * 60 * 24 * 182; // 6 mois, redemande périodique raisonnable

function getConsent() {
  const match = document.cookie.match(new RegExp("(?:^|; )" + COOKIE_NAME + "=([^;]*)"));
  return match ? match[1] : null;
}

function setConsent(value) {
  const maxAge = value === "accepted" ? COOKIE_MAX_AGE_ACCEPTED : COOKIE_MAX_AGE_REFUSED;
  document.cookie = COOKIE_NAME + "=" + value + "; max-age=" + maxAge + "; path=/; SameSite=Lax";
}

function loadGtag() {
  if (!window.GA_MEASUREMENT_ID || window.gtagLoaded) return;
  window.gtagLoaded = true;

  const script = document.createElement("script");
  script.async = true;
  script.src = "https://www.googletagmanager.com/gtag/js?id=" + window.GA_MEASUREMENT_ID;
  document.head.appendChild(script);

  window.dataLayer = window.dataLayer || [];
  window.gtag = function () { dataLayer.push(arguments); };
  gtag("js", new Date());
  gtag("config", window.GA_MEASUREMENT_ID);
}

function showBanner() {
  const banner = document.getElementById("cookie-banner");
  if (banner) banner.classList.remove("hidden");
  document.body.classList.add("overflow-hidden");
}

function hideBanner() {
  const banner = document.getElementById("cookie-banner");
  if (banner) banner.classList.add("hidden");
  document.body.classList.remove("overflow-hidden");
}

document.addEventListener("DOMContentLoaded", function () {
  const consent = getConsent();

  if (consent === "accepted") {
    loadGtag();
  } else if (consent === "refused") {
    // rien à charger
  } else {
    showBanner();
  }

  const acceptBtn = document.getElementById("cookie-accept");
  const refuseBtn = document.getElementById("cookie-refuse");

  if (acceptBtn) {
    acceptBtn.addEventListener("click", function () {
      setConsent("accepted");
      loadGtag();
      hideBanner();
    });
  }

  if (refuseBtn) {
    refuseBtn.addEventListener("click", function () {
      setConsent("refused");
      hideBanner();
    });
  }
});

window.cerise_openConsent = function () {
  showBanner();
};
