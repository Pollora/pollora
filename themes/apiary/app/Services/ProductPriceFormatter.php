<?php

declare(strict_types=1);

namespace Theme\Apiary\Services;

use Illuminate\Support\Facades\DB;

/**
 * Formats WooCommerce product prices as HTML markup.
 *
 * Supports two operating modes transparently:
 *
 * - **Full mode**: WooCommerce is loaded — reads formatting settings via WC functions
 *   (`get_woocommerce_currency_symbol()`, `wc_get_price_decimals()`, etc.).
 * - **Lightweight mode**: plugins are not loaded — reads the same settings directly
 *   from the `wp_options` table via Eloquent, with sensible defaults.
 *
 * Produces the same `<span class="woocommerce-Price-amount">` markup as WooCommerce
 * so existing CSS styles apply without changes.
 *
 * @see \Theme\Apiary\Services\ProductSearchService
 */
class ProductPriceFormatter
{
    /** @var string Currency symbol HTML entity (e.g. "&euro;"). */
    protected string $currency;

    /** @var int Number of decimal places (e.g. 2). */
    protected int $decimals;

    /** @var string Thousands separator (e.g. " " or ","). */
    protected string $thousandSep;

    /** @var string Decimal separator (e.g. "," or "."). */
    protected string $decimalSep;

    /** @var string sprintf-compatible format with %1$s (symbol) and %2$s (amount). */
    protected string $priceFormat;

    public function __construct()
    {
        if (function_exists('get_woocommerce_currency_symbol')) {
            $this->currency = get_woocommerce_currency_symbol();
            $this->decimals = wc_get_price_decimals();
            $this->thousandSep = wc_get_price_thousand_separator();
            $this->decimalSep = wc_get_price_decimal_separator();
            $this->priceFormat = get_woocommerce_price_format();
        } else {
            $this->initFromDatabase();
        }
    }

    /**
     * Format a product price as WooCommerce-compatible HTML.
     *
     * Handles three cases:
     * - Empty price: returns an empty string.
     * - Sale price: wraps the regular price in `<del>` and the sale price in `<ins>`.
     * - Normal price: returns a single formatted amount.
     *
     * @param  string|null  $price         Current/effective price (`_price` meta).
     * @param  string|null  $regularPrice  Regular price before discount (`_regular_price` meta).
     * @param  string|null  $salePrice     Discounted price (`_sale_price` meta), or null if not on sale.
     * @return string HTML price markup, or empty string if no price.
     */
    public function format(?string $price, ?string $regularPrice, ?string $salePrice): string
    {
        if ($price === null || $price === '') {
            return '';
        }

        $isOnSale = $salePrice !== null
            && $salePrice !== ''
            && $regularPrice !== null
            && $regularPrice !== $salePrice;

        if ($isOnSale) {
            return '<del aria-hidden="true">' . $this->formatAmount($regularPrice) . '</del> '
                . '<ins>' . $this->formatAmount($salePrice) . '</ins>';
        }

        return $this->formatAmount($price);
    }

    /**
     * Format a single numeric amount into an HTML price span.
     *
     * @param  string  $amount  Raw numeric value (e.g. "42.00").
     * @return string `<span class="woocommerce-Price-amount amount">…</span>`
     */
    protected function formatAmount(string $amount): string
    {
        $formatted = number_format(
            (float) $amount,
            $this->decimals,
            $this->decimalSep,
            $this->thousandSep,
        );

        return sprintf(
            '<span class="woocommerce-Price-amount amount">%s</span>',
            sprintf(
                $this->priceFormat,
                '<span class="woocommerce-Price-currencySymbol">' . $this->currency . '</span>',
                $formatted,
            ),
        );
    }

    /**
     * Populate formatting properties from `wp_options` when WooCommerce is not loaded.
     *
     * Reads the same option keys that WooCommerce uses, so the output is identical
     * regardless of whether the plugin is active for the current request.
     */
    private function initFromDatabase(): void
    {
        $currencyCode = $this->getOption('woocommerce_currency', 'EUR');
        $this->currency = self::CURRENCY_SYMBOLS[$currencyCode] ?? $currencyCode;
        $this->decimals = (int) $this->getOption('woocommerce_price_num_decimals', '2');
        $this->thousandSep = $this->getOption('woocommerce_price_thousand_sep', ' ');
        $this->decimalSep = $this->getOption('woocommerce_price_decimal_sep', ',');

        $position = $this->getOption('woocommerce_currency_pos', 'right_space');
        $this->priceFormat = match ($position) {
            'left'        => '%1$s%2$s',
            'right'       => '%2$s%1$s',
            'left_space'  => '%1$s&nbsp;%2$s',
            default       => '%2$s&nbsp;%1$s',
        };
    }

    /**
     * Read a single WordPress option value from the database.
     *
     * @param  string  $name     Option name (e.g. "woocommerce_currency").
     * @param  string  $default  Fallback value if the option does not exist.
     */
    private function getOption(string $name, string $default = ''): string
    {
        return (string) (DB::table('options')
            ->where('option_name', $name)
            ->value('option_value') ?? $default);
    }

    /**
     * ISO 4217 currency code → HTML entity mapping.
     *
     * Covers the most common currencies. Unknown codes are displayed as-is.
     *
     * @var array<string, string>
     */
    private const CURRENCY_SYMBOLS = [
        'EUR' => '&euro;', 'USD' => '&#36;', 'GBP' => '&pound;',
        'JPY' => '&yen;', 'CHF' => 'CHF', 'CAD' => 'C&#36;',
        'AUD' => 'A&#36;', 'CNY' => '&yen;', 'SEK' => 'kr',
        'NOK' => 'kr', 'DKK' => 'kr', 'PLN' => 'z&#322;',
        'CZK' => 'K&#269;', 'HUF' => 'Ft', 'RON' => 'lei',
        'BGN' => '&#1083;&#1074;.', 'HRK' => 'kn',
    ];
}
