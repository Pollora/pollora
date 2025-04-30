<?php

namespace Theme\Providers;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\ServiceProvider;
use Theme\Core\ViteJs\AssetLoader;
use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

class AdminCustomizerProvider extends ServiceProvider
{
    /**
     * Admin Assets
     *
     * Here we define the loaded assets from our previously defined
     * "dist" directory. Assets sources are located under the root "assets"
     * directory and are then compiled, thanks to Laravel Mix, to the "dist"
     * folder.
     *
     * @see https://laravel-mix.com/
     */
    public function boot(AssetLoader $assetLoader): void
    {
        Action::add('admin_menu', [$this, 'relocateVariationSwatchesMenu'], 99);

        $assetLoader->loadAssets('themosis/backend', get_stylesheet_directory(), '6969', 'backend');
        $assetLoader->loadAssets('themosis/login', get_stylesheet_directory(), '6262', 'login');
        Action::add('login_head', [$this, 'loginLogo'], 99);

        Filter::add('login_headerurl', [$this, 'loginHeaderUrl']);
        Filter::add('login_headertext', [$this, 'loginHeaderText']);

        Action::add('admin_menu', [$this, 'removeMenus'], 99);
        Action::add('admin_menu', [$this, 'relocateVariationSwatchesMenu'], 99);

        if (config('admin.wpgb.woocommerce_only', false)) {
            Action::add('admin_menu', [$this, 'relocateFacets'], 99);
            $this->hideFacetMenu();
        }

        Action::add('admin_menu', [$this, 'removeMenus'], 99);
        Filter::add('acf/settings/show_admin', '__return_false');
        Filter::add('login_display_language_dropdown', '__return_false');
        Filter::add('get_user_option_admin_color', [$this, 'defaultColorScheme'], 5);
        Action::add('admin_color_scheme_picker', 'admin_color_scheme_picker');
        Action::add('admin_head', [$this, 'adminColorScheme']);
        Filter::add('get_user_option_user_color', [$this, 'defaultColorScheme']);
    }

    public function loginLogo(): void
    {
        $customLoginId = get_theme_mod('custom_logo');
        if (! $customLoginId) {
            return;
        }
        $img = wp_get_attachment_image_src($customLoginId);

        $maxHeight = config('admin.login.logo.max_height', 128);
        $ratio = $maxHeight / $img[2];
        $width = $img[1] * $ratio;
        $height = $img[2] * $ratio;

        $inlineStyle = "
            .login h1 a {
                background-image: url({$img[0]});
                width: {$width}px;
                height: {$height}px;
            }
        ";

        echo "<style>\n{$inlineStyle}\n</style>";
    }

    public function hideFacetMenu(): void
    {
        Action::add('admin_head', function () {
            $inlineStyle = '.toplevel_page_wpgb { display: none !important; }';
            echo "<style>\n{$inlineStyle}\n</style>";
        });
    }

    /**
     * Update login page image link URL.
     */
    public function loginHeaderUrl(): string
    {
        return home_url();
    }

    /**
     * Update login page link title.
     */
    public function loginHeaderText(): string
    {
        return get_bloginfo('name');
    }

    /**
     * Relocate Comments in Admin Menu.
     *
     * Relocate Comments parent menu under a CPT.
     */
    public function relocateVariationSwatchesMenu(): void
    {
        remove_menu_page('woo-variation-swatches-settings');

        $pageTitle = esc_html__('Variation Swatches for WooCommerce Settings', 'woo-variation-swatches');
        $menuTitle = esc_html__('Swatches', 'woo-variation-swatches');

        add_submenu_page(
            'edit.php?post_type=product',
            $pageTitle,
            $menuTitle,
            'edit_posts',
            'admin.php?page=woo-variation-swatches-settings' //$menu_slug
        );
    }

    public function adminColorScheme(): void
    {
        global $_wp_admin_css_colors;

        $defaultColorScheme = $this->defaultColorScheme();
        $_wp_admin_css_colors = [$defaultColorScheme => $_wp_admin_css_colors[$defaultColorScheme]];
    }

    /**
     * @throws EntryNotFoundException
     */
    public function defaultColorScheme()
    {
        return config('admin.color_scheme', 'midnight');
    }

    /**
     * Remove some menu
     */
    public function removeMenus(): void
    {
        $menusToRemove = config('admin.disabled_menus', []);

        foreach ($menusToRemove as $menuKey => $menu) {
            if (is_array($menu)) {
                foreach ($menu as $menuToRemove) {
                    remove_submenu_page($menuKey, $menuToRemove);
                }

                continue;
            }
            remove_menu_page($menu);
        }
    }

    /**
     * Relocate WooCommerce Variation Swatches in WooCommerce Menu.
     */
    public function relocateFacets(): void
    {
        $pageTitle = __('Facets', 'wp-grid-builder');
        $menuTitle = __('Facets', 'wp-grid-builder');

        add_submenu_page(
            'edit.php?post_type=product',
            $pageTitle,
            $menuTitle,
            'edit_posts',
            'admin.php?page=wpgb-facets' //$menu_slug
        );
    }
}
