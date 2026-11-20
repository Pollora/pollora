/**
 * Checkout — sticky CTA bar spacer + coupon AJAX
 *
 * When the actions bar becomes position:fixed on mobile, it leaves the flow.
 * We append a spacer div to the body whose height matches the bar, preventing
 * content from being hidden behind it.
 *
 * Handles both:
 * - Gutenberg WooCommerce checkout block (.wc-block-checkout__actions)
 * - Classic checkout (.classic-checkout-sticky-cta)
 */

function syncSpacer(actions, spacer) {
    const isFixed = getComputedStyle(actions).position === 'fixed';
    spacer.style.height = isFixed ? `${actions.offsetHeight}px` : '0';
}

function observe(actions) {
    const spacer = document.createElement('div');
    document.body.appendChild(spacer);

    const sync = () => syncSpacer(actions, spacer);
    new ResizeObserver(sync).observe(actions);
    window.addEventListener('resize', sync);
    sync();
}

/* ── Gutenberg Blocks checkout ── */
const BLOCK_SELECTOR = '[data-block-name="woocommerce/checkout"]';
const ACTIONS_SELECTOR = '.wc-block-checkout__actions';

if (document.querySelector(BLOCK_SELECTOR)) {
    const actions = document.querySelector(ACTIONS_SELECTOR);
    if (actions) {
        observe(actions);
    } else {
        const mo = new MutationObserver(() => {
            const actions = document.querySelector(ACTIONS_SELECTOR);
            if (actions) {
                mo.disconnect();
                observe(actions);
            }
        });
        mo.observe(document.querySelector(BLOCK_SELECTOR), { childList: true, subtree: true });
    }
}

/* ── Classic checkout ── */
const CLASSIC_SELECTOR = '.classic-checkout-sticky-cta';
const classicActions = document.querySelector(CLASSIC_SELECTOR);
if (classicActions) {
    observe(classicActions);
}

/* ── Notice restyling is now handled globally by wc-notices.js ── */

if (document.querySelector('form.woocommerce-checkout')) {

    /* ── Classic checkout: coupon AJAX ──
     * The default WC coupon form was removed from woocommerce_before_checkout_form
     * and replaced by a custom panel inside review-order.blade.php (no <form> — it's
     * already inside the checkout <form>). We handle click + Enter manually. */
    jQuery(($) => {
        function applyCoupon($panel) {
            const $input = $panel.find('[data-coupon-code]');
            const code = $input.val().trim();
            if (!code) {
                $input.focus();
                return;
            }

            const $button = $panel.find('[data-apply-coupon]');
            $button.prop('disabled', true).addClass('opacity-50');

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                data: { security: wc_checkout_params.apply_coupon_nonce, coupon_code: code },
                success(result) {
                    // Remove previous notices
                    $('.apiary-notice').remove();
                    if (result) {
                        $('form.woocommerce-checkout').before(result);
                    }
                    $input.val('');
                    $(document.body).trigger('update_checkout', { update_shipping_method: false });
                },
                complete() {
                    $button.prop('disabled', false).removeClass('opacity-50');
                },
            });
        }

        // Click "Apply" button
        $(document.body).on('click', '[data-apply-coupon]', function () {
            applyCoupon($(this).closest('.js-coupon-panel'));
        });

        // Enter key inside coupon input (prevent checkout form submission)
        $(document.body).on('keydown', '[data-coupon-code]', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon($(this).closest('.js-coupon-panel'));
            }
        });
    });
}
