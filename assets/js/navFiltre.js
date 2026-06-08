const nav = document.getElementById("nav-filtres");
const fadeLeft = document.getElementById("fade-left");
const fadeRight = document.getElementById("fade-right");

nav.addEventListener("scroll", () => {
  const scrollLeft = nav.scrollLeft;
  const maxScroll = nav.scrollWidth - nav.clientWidth;

  fadeLeft.style.opacity = scrollLeft > 0 ? "1" : "0";
  fadeRight.style.opacity = scrollLeft < maxScroll ? "1" : "0";
});
