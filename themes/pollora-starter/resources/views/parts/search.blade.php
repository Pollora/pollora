{{--
    The search form.

    Included by the templates where a visitor has a reason to search: the
    archive fallback when it found nothing, and the 404 page.
--}}
<form role="search" method="get" action="{{ home_url('/') }}" class="mx-auto flex w-full max-w-md items-center gap-2">
    <label for="search-field" class="sr-only">Search</label>

    <input
        id="search-field"
        type="search"
        name="s"
        value="{{ get_search_query() }}"
        placeholder="Search&hellip;"
        class="min-w-0 flex-1 rounded-xl border border-foreground/15 bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/30"
    >

    <button
        type="submit"
        class="wp-element-button inline-flex shrink-0 items-center gap-2 rounded-xl bg-foreground px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        Search
    </button>
</form>
