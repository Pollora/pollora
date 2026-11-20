document.addEventListener('click', (e) => {
    const link = e.target.closest('.woocommerce-review-link');
    if (!link) return;

    const anchor = document.querySelector(link.hash);
    if (anchor) {
        e.preventDefault();
        anchor.scrollIntoView({ behavior: 'smooth' });
        anchor.click();
    }
});
