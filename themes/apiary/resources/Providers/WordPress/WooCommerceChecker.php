<?php

namespace Theme\Providers\WordPress;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;

class WooCommerceChecker extends ServiceProvider
{
    public function register()
    {
        Action::add('init', [$this, 'checkWooCommerce']);
    }

    public function checkWooCommerce(): void
    {
        new \Theme\Cms\WooCommerceChecker();
    }
}
