// Masque la navigation alphabétique (fixée en bas) dès que le footer est visible.
const lexiqueAlphabet = document.getElementById("lexique-alphabet");
const footer = document.querySelector("footer");

if (lexiqueAlphabet && footer) {
  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          lexiqueAlphabet.classList.add("opacity-0", "pointer-events-none");
        } else {
          lexiqueAlphabet.classList.remove("opacity-0", "pointer-events-none");
        }
      });
    },
    { threshold: 0 }
  );

  observer.observe(footer);
}
