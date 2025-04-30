<?php

namespace Theme\Providers\Store;

use AmphiBee\WpgbExtended\Facades\Template;
use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;

class ArchiveProduct extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerArchiveTemplate();
    }

    public function registerArchiveTemplate()
    {
        $classes = config('woocommerce.archive-product.product_grid.class', 'grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3');
        Template::make('product-list')
            ->setSourceType('post')
            ->setClasses([$classes])
            ->setIsMainQuery(true)
            ->setWrapperTag('div')
            ->setRenderCallback([$this, 'renderCallBack'])
            ->setNoResultsCallback([$this, 'noResultCallback']);
    }

    public function renderCallback($post)
    {
        echo view('woocommerce.archive.product');
    }

    public function noResultCallback()
    {
        Action::run('woocommerce_no_products_found');
    }
}
