const menuToggle = document.querySelector('[data-menu-toggle]');

if (menuToggle) {
    const menu = document.getElementById(menuToggle.getAttribute('aria-controls'));

    const setMenuOpen = (isOpen) => {
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menu.classList.toggle('max-md:hidden', !isOpen);
    };

    menuToggle.addEventListener('click', () => {
        setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
            setMenuOpen(false);
            menuToggle.focus();
        }
    });
}
