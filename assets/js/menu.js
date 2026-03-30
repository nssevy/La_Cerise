// Toggle menu mobile
const menuBtn   = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const iconOpen  = document.getElementById('icon-open');
const iconClose = document.getElementById('icon-close');

menuBtn.addEventListener('click', function () {
    const isOpen = !mobileMenu.classList.contains('hidden');

    // Toggle menu
    mobileMenu.classList.toggle('hidden');
    mobileMenu.classList.toggle('flex');
    menuBtn.setAttribute('aria-expanded', String(!isOpen));

    // Toggle icônes Lucide
    iconOpen.classList.toggle('hidden');
    iconClose.classList.toggle('hidden');
});
