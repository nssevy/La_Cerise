const menuBtn = document.getElementById("menu-btn");
const mobileMenu = document.getElementById("mobile-menu");
const iconOpen = document.getElementById("icon-open");
const iconClose = document.getElementById("icon-close");

menuBtn.addEventListener("click", function () {
  const isOpen = !mobileMenu.classList.contains("-translate-y-full");

  mobileMenu.classList.toggle("-translate-y-full");
  mobileMenu.classList.toggle("translate-y-0");
  menuBtn.setAttribute("aria-expanded", String(!isOpen));

  if (isOpen) {
    iconOpen.style.display = "inline-flex";
    iconClose.style.display = "none";
  } else {
    iconOpen.style.display = "none";
    iconClose.style.display = "inline-flex";
  }
});
