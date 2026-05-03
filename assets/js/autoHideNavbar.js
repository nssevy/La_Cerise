let lastScroll = 0;
const nav = document.querySelector("#admin-nav");

window.addEventListener("scroll", () => {
  const currentScroll = window.scrollY;

  if (currentScroll > lastScroll && currentScroll > 100) {
    nav.style.transform = "translateY(-100px)";
    nav.style.pointerEvents = "none";
  } else {
    nav.style.transform = "translateY(0)";
    nav.style.pointerEvents = "auto";
  }

  lastScroll = currentScroll;
});
