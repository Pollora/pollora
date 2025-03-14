<?php

namespace Theme\Cms;

/**
 * Class WooCommerceChecker
 *
 * Checks if WooCommerce is installed and activated before theme activation
 *
 * @package Theme\Cms
 */
class WooCommerceChecker
{
    /**
     * The text domain for translations
     *
     * @var string
     */
    private string $textDomain;

    /**
     * Constructor
     *
     * @param string $textDomain The theme text domain
     */
    public function __construct(string $textDomain = 'theme-textdomain')
    {
        $this->textDomain = $textDomain;
        $this->init();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    public function init(): void
    {
        add_action('after_setup_theme', [$this, 'checkWooCommerce']);
    }

    /**
     * Checks if WooCommerce is activated before activating the theme
     *
     * @return void
     */
    public function checkWooCommerce(): void
    {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            // Switch back to default theme
            switch_theme(WP_DEFAULT_THEME);

            // Add error notice
            add_action('admin_notices', [$this, 'displayErrorNotice']);

            // Stop activation process
            unset($_GET['activated']);
        }
    }

    /**
     * Displays an error notice in the admin dashboard
     *
     * @return void
     * @throws \Exception
     */
    public function displayErrorNotice(): void
    {
        echo '<div class="error"><p>';
        echo sprintf(
            __('This theme requires the <a href="%s">WooCommerce</a> plugin to be installed and activated. Please install and activate WooCommerce before activating this theme.', $this->textDomain),
            esc_url(admin_url('plugin-install.php?tab=search&s=woocommerce'))
        );
        echo '</p></div>';
    }
}