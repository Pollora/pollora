/**
 * Alpine.js component: productSearch
 *
 * Provides debounced product search suggestions with keyboard navigation.
 * The AJAX endpoint is filterable in PHP so third-party engines
 * (Meilisearch, Elasticsearch, Algolia) can replace the default WP_Query search.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('productSearch', () => ({
        query: '',
        results: [],
        loading: false,
        open: false,
        activeIndex: -1,
        _timer: null,
        _abortController: null,
        _cache: new Map(),

        get config() {
            return window.apiarySearch || {};
        },

        get minChars() {
            return this.config.minChars || 3;
        },

        get debounceMs() {
            return this.config.debounce || 300;
        },

        get showResults() {
            return this.open && this.query.length >= this.minChars;
        },

        onInput() {
            clearTimeout(this._timer);
            this.activeIndex = -1;

            if (this.query.length < this.minChars) {
                this.results = [];
                this.open = false;
                return;
            }

            this._timer = setTimeout(() => this.search(), this.debounceMs);
        },

        async search() {
            const q = this.query.trim();
            if (q.length < this.minChars) return;

            // Check cache first
            if (this._cache.has(q)) {
                this.results = this._cache.get(q);
                this.open = this.results.length > 0;
                return;
            }

            // Abort previous in-flight request
            if (this._abortController) {
                this._abortController.abort();
            }
            this._abortController = new AbortController();

            this.loading = true;

            try {
                const url = new URL(this.config.apiUrl);
                url.searchParams.set('q', q);

                const res = await fetch(url.toString(), {
                    signal: this._abortController.signal,
                });
                const json = await res.json();

                // REST API returns data directly (no wrapper)
                const data = Array.isArray(json) ? json : (json.data ?? []);
                this.results = data;
                this._cache.set(q, data);

                // Keep cache size bounded
                if (this._cache.size > 50) {
                    const firstKey = this._cache.keys().next().value;
                    this._cache.delete(firstKey);
                }

                this.open = true;
            } catch (e) {
                if (e.name !== 'AbortError') {
                    console.error('[productSearch]', e);
                }
            } finally {
                this.loading = false;
            }
        },

        onKeydown(event) {
            if (!this.showResults || this.results.length === 0) return;

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    this.activeIndex = (this.activeIndex + 1) % this.results.length;
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.activeIndex = this.activeIndex <= 0
                        ? this.results.length - 1
                        : this.activeIndex - 1;
                    break;
                case 'Enter':
                    if (this.activeIndex >= 0 && this.results[this.activeIndex]) {
                        event.preventDefault();
                        window.location.href = this.results[this.activeIndex].url;
                    }
                    break;
                case 'Escape':
                    this.close();
                    break;
            }
        },

        close() {
            this.open = false;
            this.activeIndex = -1;
        },

        navigate(url) {
            window.location.href = url;
        },
    }));
});
