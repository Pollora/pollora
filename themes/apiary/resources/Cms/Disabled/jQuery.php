<?php

namespace Theme\Cms\Disabled;

use Pollora\Support\Facades\Action;

class jQuery
{
    public function __construct()
    {
        if (! config('disable.jquery')) {
            return;
        }

        $this->removejQuery();
        Action::add('wp_default_scripts', [$this, 'removejQueryMigrate']);
    }

    protected function removejQuery()
    {
        if ($GLOBALS['pagenow'] !== 'wp-login.php' && ! is_admin() && ! is_user_logged_in()) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', false);
        }
    }

    public function removejQueryMigrate()
    {
        if (! \is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            if ($script->deps) {
                $script->deps = array_diff($script->deps, ['jquery-migrate']);
            }
        }
    }
}
