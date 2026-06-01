/**
 * Sticky add-to-cart bar
 *
 * Visibility: IntersectionObserver on the original add-to-cart button
 * Simple products: triggers the original form submit
 * Complex products (variable/grouped): clones form into sticky panel,
 *   syncs inputs back to original, submits via original form
 */

const stickyBar = document.getElementById('sticky-add-to-cart');
if (stickyBar) {
    const originalButton = document.querySelector('.single_add_to_cart_button');
    const originalForm = document.querySelector('.single-product form.cart');
    const formSlot = document.getElementById('sticky-bar-form-slot');
    const simpleAddBtn = stickyBar.querySelector('.sticky-bar-add');

    // ─── Spacer to prevent footer overlap ───
    const spacer = document.createElement('div');
    spacer.className = 'h-16';
    document.body.appendChild(spacer);

    // ─── Visibility via IntersectionObserver ───
    if (originalButton) {
        const stickyObserver = new IntersectionObserver(
            ([entry]) => {
                window.dispatchEvent(new CustomEvent(
                    entry.isIntersecting ? 'sticky-bar-hide' : 'sticky-bar-show'
                ));
            },
            { threshold: 0 }
        );
        stickyObserver.observe(originalButton);

        // Cleanup on navigation
        document.addEventListener('turbo:before-render', () => stickyObserver.disconnect(), { once: true });
    }

    // ─── Loading state helpers ───
    const setLoading = (btn, loading) => btn?.classList.toggle('is-loading', loading);

    const clearStickyLoading = () => {
        setLoading(simpleAddBtn, false);
        // Also clear clone submit buttons
        stickyBar.querySelectorAll('.single_add_to_cart_button.is-loading')
            .forEach(b => setLoading(b, false));
    };

    // Clear loading on add-to-cart completion (modal or cart slide-over)
    window.addEventListener('atc-modal-open', clearStickyLoading);
    window.addEventListener('panel-open', (e) => {
        if (e.detail?.panel === 'cart') clearStickyLoading();
    });
    if (typeof jQuery !== 'undefined') {
        jQuery(document.body).on('added_to_cart', clearStickyLoading);
    }

    // ─── Simple product: click triggers original form ───
    if (simpleAddBtn && originalForm) {
        simpleAddBtn.addEventListener('click', () => {
            setLoading(simpleAddBtn, true);
            originalForm.querySelector('button[type="submit"]')?.click();
        });
    }

    // ─── Complex products: clone form into sticky panel ───
    if (formSlot && originalForm) {
        // Clone the form content into a wrapper div (not a <form> to avoid nested forms)
        const cloneWrapper = document.createElement('div');
        cloneWrapper.className = 'sticky-bar-clone';
        Array.from(originalForm.childNodes).forEach(node => {
            cloneWrapper.appendChild(node.cloneNode(true));
        });
        formSlot.appendChild(cloneWrapper);

        // Remove hidden inputs from clone (nonces, add-to-cart, product_id)
        // — they live in the original form and will be submitted from there
        cloneWrapper.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());

        // ─── Sync: clone → original (generic by name attribute) ───
        const syncToOriginal = (cloneEl) => {
            const name = cloneEl.name;
            if (!name) return;

            const originalEl = originalForm.querySelector(`[name="${CSS.escape(name)}"]`);
            if (!originalEl) return;

            originalEl.value = cloneEl.value;
            // Trigger change so WC variation JS picks it up
            originalEl.dispatchEvent(new Event('change', { bubbles: true }));
        };

        cloneWrapper.addEventListener('change', (e) => syncToOriginal(e.target));
        cloneWrapper.addEventListener('input', (e) => {
            if (e.target.matches('input[type="number"], .qty')) {
                syncToOriginal(e.target);
            }
        });

        // ─── Submit: clone button → original form ───
        cloneWrapper.addEventListener('click', (e) => {
            const submitBtn = e.target.closest('button[type="submit"], .single_add_to_cart_button');
            if (submitBtn) {
                e.preventDefault();
                e.stopPropagation();
                setLoading(submitBtn, true);
                // Sync all current values before submit
                cloneWrapper.querySelectorAll('select, input:not([type="hidden"])').forEach(syncToOriginal);
                originalForm.querySelector('button[type="submit"]')?.click();
            }
        });

        // ─── Collapse panel after successful add-to-cart ───
        // cart.js dispatches panel-open for cart after AJAX add-to-cart
        // We also listen for WC's native added_to_cart jQuery event
        const collapsePanel = () => {
            const alpineData = Alpine.$data(stickyBar);
            if (alpineData) alpineData.expanded = false;
        };
        window.addEventListener('panel-open', (e) => {
            if (e.detail?.panel === 'cart') collapsePanel();
        });
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('added_to_cart', collapsePanel);
        }

        // ─── Sync back: original → clone (for WC variation updates) ───
        // When WC updates variation data (price, availability), reflect in clone
        if (typeof jQuery !== 'undefined') {
            const $originalForm = jQuery(originalForm);

            // Variable products: WC fires these jQuery events
            $originalForm.on('found_variation', (e, variation) => {
                // Update price display in clone — copy from original (server-rendered, safe)
                const clonePrice = cloneWrapper.querySelector('.woocommerce-variation-price');
                const origPrice = originalForm.querySelector('.woocommerce-variation-price');
                if (clonePrice && origPrice) {
                    clonePrice.replaceChildren(...Array.from(origPrice.cloneNode(true).childNodes));
                }

                // Enable/disable clone submit button to match original
                const origBtn = originalForm.querySelector('.single_add_to_cart_button');
                const cloneBtn = cloneWrapper.querySelector('.single_add_to_cart_button');
                if (origBtn && cloneBtn) {
                    cloneBtn.disabled = origBtn.disabled;
                    cloneBtn.classList.toggle('disabled', origBtn.classList.contains('disabled'));
                }
            });

            $originalForm.on('reset_data', () => {
                const clonePrice = cloneWrapper.querySelector('.woocommerce-variation-price');
                if (clonePrice) clonePrice.textContent = '';

                const cloneBtn = cloneWrapper.querySelector('.single_add_to_cart_button');
                if (cloneBtn) {
                    cloneBtn.disabled = true;
                    cloneBtn.classList.add('disabled');
                }
            });
        }
    }
}
