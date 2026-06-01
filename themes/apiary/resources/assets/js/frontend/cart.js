/**
 * Cart interactions:
 * - AJAX add-to-cart on single product pages (all product types)
 * - Cart slide-over OR confirmation modal on archive add-to-cart buttons
 * - Cart badge visibility via MutationObserver
 * - Toast notification for add-to-cart success (when modal is disabled)
 */

const modalEnabled = !!window.apiaryCart?.addToCartConfirmation;

const openCart = () => window.dispatchEvent(new CustomEvent('panel-open', { detail: { panel: 'cart' } }));

const openModal = (productData) => {
    window.dispatchEvent(new CustomEvent('atc-modal-open', { detail: productData }));
};

// ─── Archive pages: AJAX add-to-cart ───
// WooCommerce fires `added_to_cart` (jQuery event) after successful AJAX add.
if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('added_to_cart', (e, fragments, cartHash, $button) => {
        if (modalEnabled) {
            // Extract product data from the archive product card DOM
            const $product = $button?.closest('.product');
            const productData = {};

            if ($product?.length) {
                const $title = $product.find('.woocommerce-loop-product__title');
                const $link = $product.find('a[href]').first();
                const $img = $product.find('img').first();
                const $price = $product.find('.price').first();

                productData.name = $title?.length ? $title.clone().children().remove().end().text().trim() : '';
                productData.image = $img?.attr('src') || '';
                productData.price = $price?.length ? $price.html() : '';
                productData.url = $link?.attr('href') || '';
                productData.id = parseInt($button.data('product_id'), 10) || null;
            }

            openModal(productData);
        } else {
            const $product = $button?.closest('.product');
            const $title = $product?.find('.woocommerce-loop-product__title');
            const productName = $title?.length ? $title.clone().children().remove().end().text().trim() : '';
            const i18n = window.apiaryCart?.i18n || {};
            const message = productName
                ? (i18n.addedToCart || '%s added to cart.').replace('%s', productName)
                : (i18n.productAdded || 'Product added to cart.');
            showToast(message);
            openCart();
        }
    });
}

// ─── Single product: AJAX add-to-cart (all types) ───
const singleForm = document.querySelector('.single-product form.cart:not(.grouped_form)');
const groupedForm = document.querySelector('.single-product .grouped_form');

const showToast = (message, type = 'success') => {
    Alpine.store('toasts')?.createToast(message, type);
};

const refreshFragments = () => {
    if (typeof jQuery !== 'undefined') {
        jQuery(document.body).trigger('wc_fragment_refresh');
    }
};

const setButtonLoading = (btn, loading) => {
    if (loading) {
        btn.disabled = true;
        btn.classList.add('is-loading');
    } else {
        btn.disabled = false;
        btn.classList.remove('is-loading');
    }
};

const ajaxAddToCart = (form, btn) => {
    setButtonLoading(btn, true);

    const formData = new FormData(form);
    if (!formData.has('add-to-cart') && formData.has('product_id')) {
        formData.set('add-to-cart', formData.get('product_id'));
    }

    fetch(form.action || window.location.href, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const notice = doc.querySelector('.woocommerce-message');
        const productName = form.closest('.product')?.querySelector('.product_title')?.textContent || '';

        setButtonLoading(btn, false);
        refreshFragments();

        if (modalEnabled) {
            // Clone product data so Alpine detects the change on subsequent adds
            const productData = { ...(window.apiaryCart?.currentProduct || { name: productName }) };
            openModal(productData);
        } else {
            const i18n = window.apiaryCart?.i18n || {};
            if (notice) {
                showToast(notice.textContent.trim());
            } else {
                const message = productName
                    ? (i18n.addedToCart || '%s added to cart.').replace('%s', productName)
                    : (i18n.productAdded || 'Product added to cart.');
                showToast(message);
            }
            openCart();
        }
    })
    .catch(() => {
        setButtonLoading(btn, false);
        form.submit();
    });
};

// Simple + Variable products
if (singleForm) {
    singleForm.addEventListener('submit', (e) => {
        const btn = singleForm.querySelector('button[type="submit"]');
        if (btn?.classList.contains('external')) return;
        e.preventDefault();
        ajaxAddToCart(singleForm, btn);
    });
}

// Grouped products
if (groupedForm) {
    groupedForm.addEventListener('submit', (e) => {
        const btn = groupedForm.querySelector('button[type="submit"]');
        e.preventDefault();
        ajaxAddToCart(groupedForm, btn);
    });
}

// ─── Cart page: coupon AJAX ───
// The coupon panel in cart-totals uses data attributes (no <form>),
// so we handle apply + Enter key manually via WC AJAX endpoints.
if (typeof jQuery !== 'undefined' && document.querySelector('.woocommerce-cart-form')) {
    jQuery(($) => {
        const wcAjaxUrl = wc_cart_params?.wc_ajax_url;
        if (!wcAjaxUrl) return;

        function applyCartCoupon($panel) {
            const $input = $panel.find('[data-coupon-code]');
            const code = $input.val().trim();
            if (!code) { $input.focus(); return; }

            const $button = $panel.find('[data-apply-coupon]');
            $button.prop('disabled', true).addClass('opacity-50');

            $.ajax({
                type: 'POST',
                url: wcAjaxUrl.toString().replace('%%endpoint%%', 'apply_coupon'),
                data: { security: wc_cart_params.apply_coupon_nonce, coupon_code: code },
                success(result) {
                    $('.woocommerce-error, .woocommerce-message, .woocommerce-info, .apiary-notice').remove();
                    if (result) {
                        // Parse the raw WC response to extract text, then insert
                        // a clean notice node so wc-notices.js can restyle it as a toast
                        const $tmp = $('<div>').html(result);
                        const text = $tmp.text().trim();
                        const isError = $tmp.find('.woocommerce-error').length > 0 || result.includes('woocommerce-error');
                        const cls = isError ? 'woocommerce-error' : 'woocommerce-message';
                        if (text) {
                            $('<div>').addClass(cls).text(text).insertBefore('.woocommerce-cart-form');
                        }
                    }
                    $input.val('');
                    $(document.body).trigger('wc_update_cart');
                },
                complete() {
                    $button.prop('disabled', false).removeClass('opacity-50');
                },
            });
        }

        $(document.body).on('click', '.js-cart-coupon-panel [data-apply-coupon]', function () {
            applyCartCoupon($(this).closest('.js-cart-coupon-panel'));
        });

        $(document.body).on('keydown', '.js-cart-coupon-panel [data-coupon-code]', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCartCoupon($(this).closest('.js-cart-coupon-panel'));
            }
        });
    });
}

// ─── Cart page: auto-update on quantity change ───
// Delegated event listener (survives WC's replaceWith on the form).
// Uses WC's quantity_update path via a programmatic form submit.
if (typeof jQuery !== 'undefined' && document.querySelector('.woocommerce-cart-form')) {
    let updateTimer = null;

    jQuery(document).on('change', '.woocommerce-cart-form .qty', function () {
        clearTimeout(updateTimer);
        updateTimer = setTimeout(function () {
            const $form = jQuery('.woocommerce-cart-form');
            // Simulate clicking the update_cart button — this routes through
            // WC's cart_submit → quantity_update which properly handles the AJAX update.
            const $btn = $form.find(':input[name="update_cart"]');
            $btn.prop('disabled', false).attr('clicked', 'true');
            $form.submit();
        }, 600);
    });
}

// ─── Cart badge visibility ───
const badge = document.querySelector('.cart-badge');
const count = document.querySelector('.cart-count');

if (badge && count) {
    const updateBadge = () => {
        const value = parseInt(count.textContent, 10) || 0;
        badge.classList.toggle('flex', value > 0);
        badge.classList.toggle('hidden', value === 0);
    };

    const badgeObserver = new MutationObserver(updateBadge);
    badgeObserver.observe(count, {
        childList: true,
        characterData: true,
        subtree: true,
    });

    // Cleanup on turbo/SPA navigation if applicable
    document.addEventListener('turbo:before-render', () => badgeObserver.disconnect(), { once: true });
}
