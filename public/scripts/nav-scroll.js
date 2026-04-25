const body = document.body;
let lastScrollY = window.scrollY;

window.addEventListener('scroll', () => {
    const currentScrollY = window.scrollY;
    const scrollingDown = currentScrollY > lastScrollY;

    if (currentScrollY < 24) {
        body.classList.remove('nav-hidden');
    } else if (scrollingDown) {
        body.classList.add('nav-hidden');
    } else {
        body.classList.remove('nav-hidden');
    }

    lastScrollY = currentScrollY;
}, { passive: true });
