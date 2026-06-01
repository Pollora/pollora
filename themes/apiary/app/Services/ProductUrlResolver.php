<?php

declare(strict_types=1);

namespace Theme\Apiary\Services;

use Illuminate\Support\Facades\DB;

/**
 * Resolves product permalink and thumbnail URLs for the search API.
 *
 * Operates in two modes depending on WordPress plugin availability:
 *
 * - **Full mode** (WooCommerce loaded): delegates to `get_permalink()`,
 *   `wp_get_attachment_metadata()`, and `wc_placeholder_img_src()`.
 * - **Lightweight mode** (no plugins): builds URLs from the post slug
 *   and parses serialized attachment metadata directly.
 *
 * Thumbnail resolution follows a fallback chain:
 * `woocommerce_thumbnail` → `medium` → original file → attachment GUID.
 *
 * URL values are memoized per instance to avoid redundant DB queries
 * when formatting multiple products in a single request.
 *
 * @see \Theme\Apiary\Services\ProductSearchService
 */
class ProductUrlResolver
{
    /** @var string|null Cached home URL (e.g. "https://example.com"). */
    private ?string $homeUrl = null;

    /** @var string|null Cached upload base URL (e.g. "https://example.com/content/uploads"). */
    private ?string $uploadBaseUrl = null;

    /**
     * Resolve the canonical URL for a product.
     *
     * In lightweight mode (WooCommerce not loaded), builds the URL from the
     * product slug using the standard `/product/{slug}` permalink structure.
     *
     * @param  object  $row  Database row with at minimum `ID` and `post_name` properties.
     * @return string Absolute product URL.
     */
    public function productUrl(object $row): string
    {
        // In lightweight mode, rewrite rules aren't loaded so get_permalink()
        // returns ugly ?p=ID URLs. We detect full mode by checking for WooCommerce.
        if (function_exists('wc_get_page_id')) {
            return (string) get_permalink((int) $row->ID);
        }

        return rtrim($this->getHomeUrl(), '/') . '/product/' . $row->post_name;
    }

    /**
     * Resolve the thumbnail URL for a product.
     *
     * Attempts to find the `woocommerce_thumbnail` image size from attachment
     * metadata, falling back to `medium`, the original file, or the attachment
     * GUID in that order.
     *
     * @param  object  $row  Database row with `attachment_metadata`, `attached_file`,
     *                       `image_guid`, and `ID` properties.
     * @return string Absolute thumbnail URL, or empty string if no image.
     */
    public function thumbnailUrl(object $row): string
    {
        if (! $row->attachment_metadata) {
            return $this->placeholder($row);
        }

        // Full mode: delegate to WordPress attachment API
        if (function_exists('wp_get_attachment_metadata') && $row->image_guid) {
            $imageId = (int) DB::table('postmeta')
                ->where('post_id', $row->ID)
                ->where('meta_key', '_thumbnail_id')
                ->value('meta_value');

            if ($imageId) {
                $meta = wp_get_attachment_metadata($imageId);
                $uploadDir = wp_get_upload_dir();

                if (! empty($meta['sizes']['woocommerce_thumbnail']['file'])) {
                    return $uploadDir['baseurl'] . '/' . dirname($meta['file']) . '/' . $meta['sizes']['woocommerce_thumbnail']['file'];
                }
            }

            return $row->image_guid;
        }

        // Lightweight mode: parse the serialized `_wp_attachment_metadata` directly
        $meta = @unserialize($row->attachment_metadata);

        if (! empty($meta['sizes']['woocommerce_thumbnail']['file']) && ! empty($meta['file'])) {
            return $this->getUploadBaseUrl() . '/' . dirname($meta['file']) . '/' . $meta['sizes']['woocommerce_thumbnail']['file'];
        }

        if (! empty($meta['sizes']['medium']['file']) && ! empty($meta['file'])) {
            return $this->getUploadBaseUrl() . '/' . dirname($meta['file']) . '/' . $meta['sizes']['medium']['file'];
        }

        if ($row->attached_file) {
            return $this->getUploadBaseUrl() . '/' . $row->attached_file;
        }

        return $row->image_guid ?: '';
    }

    /**
     * Return a placeholder image URL when no thumbnail is set.
     *
     * @param  object  $row  Database row (used as fallback via `image_guid`).
     * @return string Placeholder URL, or empty string.
     */
    private function placeholder(object $row): string
    {
        if (function_exists('wc_placeholder_img_src')) {
            return wc_placeholder_img_src('woocommerce_thumbnail');
        }

        return $row->image_guid ?: '';
    }

    /**
     * Get the site's home URL, memoized for the request lifetime.
     *
     * Uses Laravel's `url()` helper which respects the `APP_URL` / `WP_HOME`
     * constants — unlike the raw `home` option in `wp_options` which may
     * include the `/cms/` subdirectory in a Pollora installation.
     */
    private function getHomeUrl(): string
    {
        return $this->homeUrl ??= rtrim(url('/'), '/');
    }

    /**
     * Get the base URL for uploaded media files.
     *
     * In a standard Pollora installation, uploads live under `/content/uploads/`.
     */
    private function getUploadBaseUrl(): string
    {
        return $this->uploadBaseUrl ??= rtrim($this->getHomeUrl(), '/') . '/content/uploads';
    }
}
