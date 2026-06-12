const articleActions = document.getElementById("article-actions");
const footer = document.querySelector("footer");

if (articleActions && footer) {
  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          articleActions.classList.add("opacity-0", "pointer-events-none");
        } else {
          articleActions.classList.remove("opacity-0", "pointer-events-none");
        }
      });
    },
    { threshold: 0 }
  );

  observer.observe(footer);
}
