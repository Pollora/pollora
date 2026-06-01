<?php

declare(strict_types=1);

namespace Theme\Apiary\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Weighted product search using Eloquent's query builder.
 *
 * Performs a multi-field search with additive relevance scoring to surface
 * the most pertinent results first. The scoring weights are:
 *
 * | Field                          | Score |
 * |--------------------------------|-------|
 * | SKU exact match                | 100   |
 * | Title starts with query        | 50    |
 * | Title contains query           | 20    |
 * | SKU contains query             | 15    |
 * | Category / tag / attribute     | 10    |
 * | Excerpt (short description)    | 5     |
 *
 * Scores are additive — a product matching on both title and category
 * will rank higher than one matching category alone.
 *
 * Works in both full WordPress mode and lightweight API mode (no plugins).
 * Third-party search engines (Meilisearch, Elasticsearch, Algolia) can
 * entirely replace this implementation via the `apiary/search/products` filter.
 *
 * @see \Theme\Apiary\Http\Controllers\Api\ProductSearchController
 * @see config('theme.woocommerce.search') for min_chars, debounce, max_results
 */
class ProductSearchService
{
    /**
     * Search published products by relevance.
     *
     * @param  string  $query       The user's search input (already trimmed and validated by the controller).
     * @param  int     $maxResults  Maximum number of products to return.
     * @return array<int, array{id: int, title: string, url: string, image: string, price: string}>
     */
    public function search(string $query, int $maxResults = 6): array
    {
        // Allow third-party engines to override the entire search.
        // Return an array to short-circuit; return null to use the default query.
        if (function_exists('apply_filters')) {
            $override = apply_filters('apiary/search/products', null, $query, $maxResults);
            if (is_array($override)) {
                return $override;
            }
        }

        $rows = $this->queryProducts($query, $maxResults);

        return $rows->isEmpty() ? [] : $this->formatResults($rows);
    }

    /**
     * Execute the weighted search query.
     *
     * Uses a single SQL statement with LEFT JOINs across posts, postmeta (SKU, price,
     * thumbnail), term_relationships/taxonomy/terms (categories, tags, attributes),
     * and attachment metadata. Results are grouped by product ID and ordered by a
     * computed relevance score.
     *
     * @param  string  $query       Raw search term.
     * @param  int     $maxResults  LIMIT clause value.
     * @return Collection<int, object> Raw database rows with product data and relevance score.
     */
    protected function queryProducts(string $query, int $maxResults): Collection
    {
        $prefix = DB::getTablePrefix();
        $like = '%' . $query . '%';
        $prefixLike = $query . '%';

        $relevanceExpr = "
            (CASE WHEN MAX({$prefix}pm_sku.meta_value) = ? THEN 100 ELSE 0 END)
          + (CASE WHEN {$prefix}p.post_title LIKE ? THEN 50 ELSE 0 END)
          + (CASE WHEN {$prefix}p.post_title LIKE ? THEN 20 ELSE 0 END)
          + (CASE WHEN MAX({$prefix}pm_sku.meta_value) LIKE ? THEN 15 ELSE 0 END)
          + (CASE WHEN MAX({$prefix}t.name) IS NOT NULL THEN 10 ELSE 0 END)
          + (CASE WHEN MAX({$prefix}p.post_excerpt) LIKE ? THEN 5 ELSE 0 END)
        ";

        $relevanceBindings = [
            $query,       // SKU exact
            $prefixLike,  // title prefix
            $like,        // title contains
            $like,        // SKU contains
            $like,        // excerpt contains
        ];

        return DB::table('posts as p')
            ->leftJoin('postmeta as pm_sku', function ($join) {
                $join->on('pm_sku.post_id', '=', 'p.ID')
                    ->where('pm_sku.meta_key', '=', '_sku');
            })
            // Visible taxonomies only (exclude internal product_type / product_visibility)
            ->leftJoin('term_relationships as tr', 'tr.object_id', '=', 'p.ID')
            ->leftJoin('term_taxonomy as tt', function ($join) {
                $join->on('tt.term_taxonomy_id', '=', 'tr.term_taxonomy_id')
                    ->whereNotIn('tt.taxonomy', ['product_type', 'product_visibility']);
            })
            ->leftJoin('terms as t', function ($join) use ($like) {
                $join->on('t.term_id', '=', 'tt.term_id')
                    ->where('t.name', 'LIKE', $like);
            })
            ->leftJoin('postmeta as pm_price', function ($join) {
                $join->on('pm_price.post_id', '=', 'p.ID')
                    ->where('pm_price.meta_key', '=', '_price');
            })
            ->leftJoin('postmeta as pm_rprice', function ($join) {
                $join->on('pm_rprice.post_id', '=', 'p.ID')
                    ->where('pm_rprice.meta_key', '=', '_regular_price');
            })
            ->leftJoin('postmeta as pm_sprice', function ($join) {
                $join->on('pm_sprice.post_id', '=', 'p.ID')
                    ->where('pm_sprice.meta_key', '=', '_sale_price');
            })
            ->leftJoin('postmeta as pm_thumb', function ($join) {
                $join->on('pm_thumb.post_id', '=', 'p.ID')
                    ->where('pm_thumb.meta_key', '=', '_thumbnail_id');
            })
            ->leftJoin('posts as att', function ($join) {
                $join->on('att.ID', '=', 'pm_thumb.meta_value')
                    ->where('att.post_type', '=', 'attachment');
            })
            ->leftJoin('postmeta as pm_att_meta', function ($join) {
                $join->on('pm_att_meta.post_id', '=', 'att.ID')
                    ->where('pm_att_meta.meta_key', '=', '_wp_attachment_metadata');
            })
            ->leftJoin('postmeta as pm_att_file', function ($join) {
                $join->on('pm_att_file.post_id', '=', 'att.ID')
                    ->where('pm_att_file.meta_key', '=', '_wp_attached_file');
            })
            ->where('p.post_type', 'product')
            ->where('p.post_status', 'publish')
            ->where(function ($q) use ($like, $query) {
                $q->where('p.post_title', 'LIKE', $like)
                    ->orWhere('pm_sku.meta_value', 'LIKE', $like)
                    ->orWhere('pm_sku.meta_value', '=', $query)
                    ->orWhere('t.name', 'LIKE', $like)
                    ->orWhere('p.post_excerpt', 'LIKE', $like);
            })
            ->selectRaw(
                "{$prefix}p.ID, {$prefix}p.post_title, {$prefix}p.post_name, " .
                "MIN({$prefix}pm_price.meta_value) as price, " .
                "MIN({$prefix}pm_rprice.meta_value) as regular_price, " .
                "MIN({$prefix}pm_sprice.meta_value) as sale_price, " .
                "MIN({$prefix}att.guid) as image_guid, " .
                "MIN({$prefix}pm_att_meta.meta_value) as attachment_metadata, " .
                "MIN({$prefix}pm_att_file.meta_value) as attached_file, " .
                "({$relevanceExpr}) as relevance",
                $relevanceBindings
            )
            ->groupBy('p.ID', 'p.post_title', 'p.post_name')
            ->orderByDesc('relevance')
            ->orderBy('p.post_title')
            ->limit($maxResults)
            ->get();
    }

    /**
     * Transform raw database rows into the API response format.
     *
     * Delegates URL resolution and price formatting to dedicated services
     * that handle both full-WordPress and lightweight-API modes transparently.
     *
     * @param  Collection<int, object>  $rows  Raw rows from {@see queryProducts()}.
     * @return array<int, array{id: int, title: string, url: string, image: string, price: string}>
     */
    protected function formatResults(Collection $rows): array
    {
        $priceFormatter = new ProductPriceFormatter();
        $urlResolver = new ProductUrlResolver();

        return $rows->map(function ($row) use ($priceFormatter, $urlResolver) {
            return [
                'id'    => (int) $row->ID,
                'title' => $row->post_title,
                'url'   => $urlResolver->productUrl($row),
                'image' => $urlResolver->thumbnailUrl($row),
                'price' => $priceFormatter->format($row->price, $row->regular_price, $row->sale_price),
            ];
        })->values()->all();
    }
}
