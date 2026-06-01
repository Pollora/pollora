# Classic Checkout → Gutenberg Alignment

> Started: 2026-05-29
> Status: In progress

## Done

### Bug fixes
- [x] Shipping section not showing → missing shipping zones (created France zone with flat rate + free shipping)
- [x] `form-shipping.blade.php` — stray `{` in `@foreach` syntax
- [x] Empty `.woocommerce-shipping-fields` div causing double border when no shipping needed → conditional rendering

### Layout alignment
- [x] Payment moved from right sidebar to main column (unhook `woocommerce_checkout_payment` from `woocommerce_checkout_order_review`, call `woocommerce_checkout_payment()` directly in `form-checkout.blade.php`)
- [x] Columns changed from 50/50 to 60/40 (`lg:grid-cols-5`, main=`col-span-3`, sidebar=`col-span-2`)
- [x] "← Return to Cart" link added next to "Place order" button (same arrow SVG as Gutenberg)
- [x] Order notes moved from `form-shipping.blade.php` to `payment.blade.php` (between payment and terms, like Gutenberg)
- [x] Order notes behind checkbox toggle "Add a note to your order" (Alpine.js x-data/x-show)
- [x] Order notes label set to `sr-only` via `woocommerce_form_field_args` filter

### Sidebar (Order Summary)
- [x] Renamed "Your order" → "Order summary" (`text-lg font-bold`)
- [x] Background changed from `bg-white border-outline rounded-lg` to `bg-surface border border-outline rounded-xl`
- [x] Sticky sidebar on desktop (`lg:sticky lg:top-24`)
- [x] Product items compacted: `size-14` images, badge quantity on image, price right-aligned
- [x] Short description displayed under unit price (`text-[11px] text-muted line-clamp-2`)
- [x] Variation attributes displayed as `Color: Blue / Size: Large` format (`text-[11px] text-subtle`)
- [x] Quantity removed from unit price line (badge on image is sufficient)
- [x] Sale badge "Save X €" on promo products (`bg-accent rounded-full text-[11px] font-semibold`)
- [x] Struck-through regular price for sale items
- [x] Edge-to-edge borders on coupon/subtotal/total sections (`-mx-5 sm:-mx-6`)
- [x] Shipping line added in totals (method name + price)
- [x] Coupon: collapsible "Add coupons ▾" panel with floating label input + "Apply" button (`bg-primary`)
- [x] Subtotal/fees labels in `text-muted`
- [x] Total separated in its own `<dl>` with border-t

### Payment section
- [x] Heading: "Payment options" (`text-xl font-bold`)
- [x] `<ul>` container: `border border-outline rounded-xl overflow-hidden`
- [x] Each `<li>`: `has-[:checked]` dynamic highlight ring (follows radio selection via CSS)
- [x] Label: full-width clickable, `gap-3`, `hover:bg-surface/50`
- [x] Description: `px-4 pb-4 leading-relaxed`
- [x] Button: `rounded-xl font-semibold`

### Contact information + Address card (Gutenberg pattern)
- [x] Email extracted from billing loop → standalone "Contact information" section at top
- [x] Pre-filled address: compact card with name + formatted address + Edit button (Alpine.js toggle)
- [x] Click Edit → full form fields revealed via `x-show="editing"` (fields stay in DOM)
- [x] Guest/empty: form shown directly (`editing: true` initial state)
- [x] Validation safety: `x-init` listens for `checkout_error` jQuery event → forces `editing = true`

### Section headings
- [x] Contact information: `text-lg lg:text-xl font-bold`
- [x] Billing: `text-lg lg:text-xl font-bold`
- [x] Payment: `text-lg lg:text-xl font-bold`
- [x] Separators removed between shipping/additional/payment sections

### Terms & Privacy
- [x] Terms checkbox label: `text-xs` (was `text-sm`)
- [x] `.woocommerce-privacy-policy-text`: `mt-0! pt-4! text-xs! text-subtle!` (forced over WC styles)

### Mobile responsive
- [x] Stack columns on mobile: `space-y-8 lg:space-y-0`, tighter `mb-6 lg:mb-10` on sections
- [x] Sticky "Place order" bar on mobile: `.classic-checkout-sticky-cta` fixed bottom bar with trust signal, icon-only "Return to cart", safe-area-inset padding
- [x] Body spacer via `checkout.js` (same ResizeObserver pattern as Gutenberg)
- [x] Tighter section spacing on mobile: `text-lg lg:text-xl`, `mb-3 lg:mb-4`/`mb-4 lg:mb-6` on headings
- [x] Order summary collapsible on mobile: Alpine.js toggle with total price visible, separate desktop sidebar remains

### Visual polish
- [x] Shipping methods: refactored from card-style grid to list-style radio `<ul>` matching payment methods pattern (`border-outline rounded-xl`, `has-[:checked]:shadow-[inset_...]`)
- [x] Form field focus states: verified — uses `focus:border-ring focus:ring-2 focus:ring-ring/20` matching Gutenberg's `color-mix(in srgb, var(--color-ring) 20%, transparent)`
- [x] Select2 dropdowns: full design-token override (outline, ring, surface-alt, primary colors)
- [x] `woocommerce_form_field`: filter applies `input_class` to all types (input/select/textarea) — verified adequate

### Functional
- [x] Coupon form AJAX: custom jQuery handler in `checkout.js` calls `apply_coupon` endpoint + triggers `update_checkout`
- [x] Shipping method change: native `<input class="shipping_method">` radios + existing AJAX fragment filter in `checkout.php`
- [x] Login form: untouched by layout changes, rendered by `woocommerce_before_checkout_form` hook

## Remaining (to do next)

### Browser testing
- [ ] Test mobile sticky bar on real device (iPhone/Android)
- [ ] Test order summary collapsible open/close + total update after AJAX
- [ ] Test coupon apply/remove flow end-to-end
- [ ] Test shipping method change updates sidebar totals
- [ ] Cross-browser: Safari, Firefox, Chrome

## Key files modified
- `themes/apiary/resources/views/woocommerce/checkout/form-checkout.blade.php` — main layout, mobile collapsible order summary
- `themes/apiary/resources/views/woocommerce/checkout/form-billing.blade.php` — heading style, responsive sizing
- `themes/apiary/resources/views/woocommerce/checkout/form-shipping.blade.php` — shipping checkbox + order notes removed
- `themes/apiary/resources/views/woocommerce/checkout/payment.blade.php` — payment in main col + order notes + return to cart + sticky mobile CTA
- `themes/apiary/resources/views/woocommerce/checkout/payment-method.blade.php` — radio card style with has-[:checked]
- `themes/apiary/resources/views/woocommerce/checkout/review-order.blade.php` — sidebar items, coupon, totals, sale badges
- `themes/apiary/resources/views/woocommerce/checkout/terms.blade.php` — terms text-xs + privacy text forced styles
- `themes/apiary/resources/views/woocommerce/cart/cart-shipping.blade.php` — list-style radio (was card-style grid)
- `themes/apiary/app/inc/woocommerce/checkout.php` — unhook payment from sidebar, remove coupon from hook, sr-only order notes label
- `themes/apiary/resources/assets/js/frontend/checkout.js` — sticky bar spacer (blocks + classic) + coupon AJAX handler
- `themes/apiary/resources/assets/css/frontend/components/select2.css` — design token overrides
