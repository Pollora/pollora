<?php

namespace Theme\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Log1x\Navi\Navi;
use Pollora\Support\Facades\Action;

class MenuServiceProvider extends ServiceProvider
{
    protected $menus;

    public function register()
    {
        Action::add('wp', function () {
            $this->menus = $this->getMenus();
        });
    }

    protected function getMenus()
    {

        $menuKeys = array_keys(Config::get('menus'));

        $menus = [];

        foreach ($menuKeys as $menuKey) {
            $menus[str_replace('-', '_', $menuKey)] = $this->getMenuDatas($menuKey);
        }

        return (object) $menus;
    }

    protected function getMenuDatas($menu)
    {
        $menuItems = (new Navi())->build($menu);
        $menuDatas = $menuItems->get();

        return (object) [
            'datas' => $menuDatas,
            'itemCount' => $menuItems->isEmpty() ? 0 : count($menuItems->toArray()),
            'items' => $menuItems->isEmpty() ? [] : $menuItems->toArray(),
        ];
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            // Pass data to the view.
            $view->with('menus', $this->menus);
        });
    }
}
