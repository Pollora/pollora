/**
 * WooCommerce notice restyling via Alpine.js toast store.
 *
 * Registers an `Alpine.store('toasts')` that manages a reactive list of
 * toast notifications. WooCommerce notices (.woocommerce-error, .woocommerce-message,
 * .woocommerce-info) are intercepted and converted into store entries.
 *
 * The toast container is rendered in the layout via a Blade partial that
 * reads from `$store.toasts.list` with Alpine transitions.
 *
 * Also fixes AJAX-rendered Blade toasts (.apiary-toast) that have broken SVGs
 * due to wp_kses stripping attributes in AJAX context.
 */

const NOTICE_SELECTORS = '.woocommerce-error, .woocommerce-message, .woocommerce-info';

// ── Alpine store ──

document.addEventListener('alpine:init', () => {
    Alpine.store('toasts', {
        counter: 0,
        list: [],

        /**
         * Create a toast notification.
         *
         * @param {string} message  Text content of the toast.
         * @param {'success'|'error'|'info'} type  Toast type (controls icon and color).
         * @param {number} duration  Auto-dismiss delay in ms (0 = no auto-dismiss).
         */
        createToast(message, type = 'info', duration = 8000) {
            const id = this.counter++;
            this.list.push({ id, message, type, visible: true });

            if (duration > 0) {
                setTimeout(() => this.dismiss(id), duration);
            }
        },

        /**
         * Dismiss a toast by ID (triggers leave transition).
         *
         * @param {number} id  Toast ID.
         */
        dismiss(id) {
            const toast = this.list.find(t => t.id === id);
            if (toast) toast.visible = false;

            // Clean up after transition
            setTimeout(() => {
                this.list = this.list.filter(t => t.id !== id);
            }, 600);
        },
    });
});

// ── Notice interception ──

/**
 * Determine notice type from a WC notice element's class list.
 *
 * @param {HTMLElement} el
 * @returns {'error'|'success'|'info'}
 */
function getNoticeType(el) {
    if (el.classList.contains('woocommerce-error')) return 'error';
    if (el.classList.contains('woocommerce-info')) return 'info';
    return 'success';
}

/**
 * Extract readable text from a WC notice, handling <li>-based and flat markup.
 *
 * @param {HTMLElement} el
 * @returns {string} Concatenated text content.
 */
function extractText(el) {
    const items = el.querySelectorAll('li');
    if (items.length > 0) {
        return Array.from(items).map(li => li.textContent.trim()).filter(Boolean).join('. ');
    }
    return el.textContent.trim();
}

/**
 * Convert a WC notice element into a toast via the Alpine store.
 * Error notices are kept inline (not toasted) for visibility.
 *
 * @param {HTMLElement} el  A `.woocommerce-error`, `.woocommerce-message`, or `.woocommerce-info` element.
 */
function interceptNotice(el) {
    const text = extractText(el);
    if (!text) { el.remove(); return; }

    const type = getNoticeType(el);

    if (type === 'error') {
        // Error notices stay inline — restyle in place for immediate visibility
        const alert = document.createElement('div');
        alert.className = 'apiary-notice flex items-start gap-3 rounded-lg bg-error-light border border-error/20 p-4 my-5 text-sm text-error';
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
            <svg class="w-5 h-5 shrink-0 text-error mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>${text}</div>`;
        el.replaceWith(alert);
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    // Success / info → toast via Alpine store
    el.remove();
    window.Alpine?.store('toasts')?.createToast(text, type);
}

/**
 * Remove AJAX-rendered Blade toasts (.apiary-toast) and re-create them
 * via the Alpine store so they render with proper SVGs and transitions.
 *
 * @param {HTMLElement} toast
 */
function convertLegacyToast(toast) {
    // Skip toasts managed by the Alpine store (they live inside the container)
    if (toast.closest('[x-data*="toasts"]')) return;

    const text = toast.querySelector('.flex-1')?.textContent.trim()
        || toast.textContent.trim();
    if (!text) { toast.remove(); return; }

    const isError = toast.querySelector('.text-error');
    const isInfo = toast.getAttribute('role') === 'status';
    const type = isError ? 'error' : (isInfo ? 'info' : 'success');

    toast.remove();
    window.Alpine?.store('toasts')?.createToast(text, type);
}

/**
 * Scan the document for unstyled WC notices and legacy toasts.
 */
function scanNotices() {
    // Raw WC notices → intercept
    document.querySelectorAll(NOTICE_SELECTORS).forEach(el => {
        if (!el.classList.contains('apiary-notice')) {
            interceptNotice(el);
        }
    });

    // Legacy Blade toasts rendered via AJAX → convert to store
    document.querySelectorAll('.apiary-toast').forEach(toast => {
        if (!toast.closest('[x-data*="toasts"]')) {
            convertLegacyToast(toast);
        }
    });
}

// ── Event hooks (no MutationObserver — purely event-driven) ──

if (typeof jQuery !== 'undefined') {
    // After any WC AJAX call
    jQuery(document).ajaxComplete(function (_e, _xhr, settings) {
        if (!settings.url?.includes('wc-ajax=')) return;
        requestAnimationFrame(scanNotices);
    });

    // WC-specific jQuery events
    jQuery(document.body).on(
        'updated_wc_div updated_cart_totals checkout_error applied_coupon removed_coupon',
        function () { requestAnimationFrame(scanNotices); }
    );
}

// Initial page load — scan after Alpine is ready so the store exists.
// alpine:initialized fires after Alpine.start() completes.
// Fallback: if Alpine is already initialized (shouldn't happen with our import order), scan immediately.
if (window.Alpine?._x_dataStack) {
    scanNotices();
} else {
    document.addEventListener('alpine:initialized', () => scanNotices());
}
