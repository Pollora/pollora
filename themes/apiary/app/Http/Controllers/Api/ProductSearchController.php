<?php

declare(strict_types=1);

namespace Theme\Apiary\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Theme\Apiary\Services\ProductSearchService;

/**
 * Product search suggestions API endpoint.
 *
 * Serves lightweight JSON responses for the autocomplete dropdown
 * in the site header search bar (desktop and mobile).
 *
 * Configuration is read from `config/woocommerce.php` under the `search` key:
 * - `suggestions` (bool): enable/disable the feature
 * - `min_chars` (int): minimum characters before searching (default: 3)
 * - `max_results` (int): maximum products returned (default: 6)
 * - `debounce` (int): client-side debounce in ms (default: 300)
 *
 * @see \Theme\Apiary\Services\ProductSearchService  Weighted search logic
 * @see resources/assets/js/frontend/product-search.js  Alpine.js client component
 *
 * @route GET /api/products/search?q={query}
 */
class ProductSearchController
{
    /**
     * Handle the search request.
     *
     * Returns an empty array if the query is shorter than the configured
     * minimum, or delegates to {@see ProductSearchService} for ranked results.
     *
     * @param  Request               $request  Expects a `q` query parameter.
     * @param  ProductSearchService  $search   Injected via Laravel's service container.
     * @return JsonResponse Array of product objects `{id, title, url, image, price}`.
     */
    public function __invoke(Request $request, ProductSearchService $search): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $config = config('theme.woocommerce.search', []);
        $minChars = (int) ($config['min_chars'] ?? 3);
        $maxResults = (int) ($config['max_results'] ?? 6);

        if (mb_strlen($query) < $minChars) {
            return response()->json([]);
        }

        return response()->json(
            $search->search($query, $maxResults)
        );
    }
}
