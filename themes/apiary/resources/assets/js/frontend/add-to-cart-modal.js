/**
 * Add-to-Cart Confirmation Modal — Alpine.js component
 *
 * Registered via Alpine.data('atcModal') on alpine:init.
 * Listens for 'atc-modal-open' custom event with product detail.
 * Manages recommendations: recently viewed → cross-sells → related → nothing.
 */

import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('atcModal', () => ({
        open: false,
        product: null,
        recommendations: [],
        recsTitle: '',
        loadingRecs: false,

        init() {
            window.addEventListener('atc-modal-open', (e) => {
                this.product = e.detail;
                this.open = true;
                document.body.classList.add('overflow-hidden');
                this.loadRecommendations();
            });
        },

        close() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },

        async loadRecommendations() {
            const i18n = window.apiaryCart?.i18n || {};
            const productId = this.product?.id;

            // 1. Try recently viewed
            const recentlyViewed = window.apiaryRecentlyViewed?.get(productId, 4) || [];
            if (recentlyViewed.length > 0) {
                this.recommendations = recentlyViewed;
                this.recsTitle = i18n.recentlyViewed || 'Recently viewed';
                return;
            }

            // 2. Try cross-sells then upsells
            const crossSellIds = this.product?.crossSellIds || [];
            const upsellIds = this.product?.upsellIds || [];
            const fallbackIds = crossSellIds.length ? crossSellIds : upsellIds;

            if (fallbackIds.length) {
                this.loadingRecs = true;
                this.recsTitle = i18n.youMayAlsoLike || 'You may also like';
                try {
                    this.recommendations = await this.fetchProducts(fallbackIds.slice(0, 4));
                } catch { /* silent */ }
                this.loadingRecs = false;
                return;
            }

            // 3. Try related products via Store API
            if (productId) {
                this.loadingRecs = true;
                this.recsTitle = i18n.youMayAlsoLike || 'You may also like';
                try {
                    const res = await fetch(`/wp-json/wc/store/v1/products?per_page=4&orderby=popularity&exclude=${productId}`);
                    if (res.ok) {
                        const products = await res.json();
                        this.recommendations = products.map(p => ({
                            id: p.id,
                            name: p.name,
                            price: p.prices?.price
                                ? (parseInt(p.prices.price, 10) / (10 ** p.prices.currency_minor_unit)).toLocaleString(document.documentElement.lang || 'en', { style: 'currency', currency: p.prices.currency_code })
                                : '',
                            image: p.images?.[0]?.thumbnail || p.images?.[0]?.src || '',
                            url: p.permalink,
                        }));
                    }
                } catch { /* silent */ }
                this.loadingRecs = false;
            }
        },

        async fetchProducts(ids) {
            const res = await fetch(`/wp-json/wc/store/v1/products?include=${ids.join(',')}&per_page=${ids.length}`);
            if (!res.ok) return [];
            const products = await res.json();
            return products.map(p => ({
                id: p.id,
                name: p.name,
                price: p.prices?.price
                    ? (parseInt(p.prices.price, 10) / (10 ** p.prices.currency_minor_unit)).toLocaleString(document.documentElement.lang || 'en', { style: 'currency', currency: p.prices.currency_code })
                    : '',
                image: p.images?.[0]?.thumbnail || p.images?.[0]?.src || '',
                url: p.permalink,
            }));
        },
    }));
});
