import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import './components';
import './recently-viewed';
import './add-to-cart-modal';
import './cart';
import './reviews';
import './sticky-bar';
import './checkout';
import './product-search';
import './wc-notices';

Alpine.plugin(collapse);
window.Alpine = Alpine;

Alpine.start();

// Sanitize HTML via DOMParser — only allows rendered text nodes and safe markup
const sanitizeHTML = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    doc.body.querySelectorAll('script, style, iframe, object, embed, link').forEach(el => el.remove());
    return doc.body.innerHTML;
};

// Variable product: mirror variation price into add-to-cart button
// Uses jQuery event bridge — WooCommerce fires jQuery-only custom events for variations
jQuery(($) => {
    $('.variations_form').on('found_variation', function (e, variation) {
        // Mirror variation price into add-to-cart button (if price-in-button elements exist)
        const sep = this.querySelector('.variation-price-separator');
        const target = this.querySelector('.variation-price-in-btn');
        if (sep && target) {
            sep.classList.remove('hidden');
            target.innerHTML = sanitizeHTML(variation.price_html);
        }

        // Update currentProduct with variation-specific data for the add-to-cart modal
        if (window.apiaryCart?.currentProduct) {
            window.apiaryCart.currentProduct.price = variation.price_html;
            if (variation.image?.full_src) {
                window.apiaryCart.currentProduct.image = variation.image.full_src;
            }
            // Build human-readable variation attributes from form selects (e.g. "Red, Large")
            // variation.attributes has empty strings for "Any …" attributes,
            // so we read the actual user selection from the form instead.
            const labels = [];
            this.querySelectorAll('.variations select').forEach(sel => {
                if (sel.value) {
                    labels.push(sel.options[sel.selectedIndex]?.text || sel.value);
                }
            });
            if (labels.length) {
                window.apiaryCart.currentProduct.variation = labels.join(', ');
            }
        }
    }).on('reset_data', function () {
        const sep = this.querySelector('.variation-price-separator');
        const target = this.querySelector('.variation-price-in-btn');
        if (sep && target) {
            sep.classList.add('hidden');
            target.textContent = '';
        }

        // Restore parent product data from the original localized object
        if (window.apiaryCart?.currentProduct && window.apiaryCart._parentProduct) {
            window.apiaryCart.currentProduct.price = window.apiaryCart._parentProduct.price;
            window.apiaryCart.currentProduct.image = window.apiaryCart._parentProduct.image;
            delete window.apiaryCart.currentProduct.variation;
        }
    });

    // Store a copy of the original parent product data for reset
    if (window.apiaryCart?.currentProduct) {
        window.apiaryCart._parentProduct = {
            price: window.apiaryCart.currentProduct.price,
            image: window.apiaryCart.currentProduct.image,
        };
    }
});

// Grouped product: calculate total and show in button
document.querySelectorAll('.grouped_form').forEach(form => {
    const sep = form.querySelector('.grouped-price-separator');
    const target = form.querySelector('.grouped-price-total');
    if (!sep || !target) return;

    // Detect currency from existing price on the page
    const samplePrice = form.querySelector('.woocommerce-Price-amount bdi');
    const currency = samplePrice?.querySelector('.woocommerce-Price-currencySymbol')?.textContent || '';
    const locale = document.documentElement.lang || 'en';

    const formatPrice = (amount) => {
        try {
            return new Intl.NumberFormat(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount) + (currency ? '\u00a0' + currency : '');
        } catch { return amount.toFixed(2) + ' ' + currency; }
    };

    const update = () => {
        let total = 0;
        form.querySelectorAll('tr[data-price]').forEach(row => {
            const price = parseFloat(row.dataset.price) || 0;
            const qty = parseFloat(row.querySelector('.qty')?.value) || 0;
            total += price * qty;
        });
        if (total > 0) {
            sep.classList.remove('hidden');
            target.textContent = formatPrice(total);
        } else {
            sep.classList.add('hidden');
            target.textContent = '';
        }
    };

    form.addEventListener('change', (e) => { if (e.target.matches('.qty')) update(); });
    form.addEventListener('click', (e) => {
        if (e.target.closest('.qty-minus, .qty-plus')) setTimeout(update, 50);
    });
});

// Quantity +/- buttons
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.qty-minus, .qty-plus');
    if (!btn) return;

    const input = btn.closest('.quantity').querySelector('.qty');
    if (!input) return;

    const step = parseFloat(input.step) || 1;
    const min = parseFloat(input.min) || 0;
    const max = parseFloat(input.max) || Infinity;
    let value = parseFloat(input.value) || min;

    if (btn.classList.contains('qty-minus')) {
        value = Math.max(min, value - step);
    } else {
        value = Math.min(max, value + step);
    }

    input.value = value;
    input.dispatchEvent(new Event('change', { bubbles: true }));
});

// Toast notifications are now managed by Alpine.store('toasts') — see wc-notices.js
