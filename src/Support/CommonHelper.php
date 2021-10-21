<?php

namespace Vnnit\Core\Support;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Nwidart\Menus\Facades\Menu;

class CommonHelper
{
    public function getSectionCode()
    {
        if (\is_null(Request::route())) return '';
        $routeName = explode('.', Request::route()->getName());
        return trim(head($routeName));
    }

    public function callApi($method, $url, $params = [])
    {
        $fullUrl = config('laka.api_address')."{$url}";
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            // 'Content-Type' => 'application/x-www-form-urlencoded',
            'token' => config('laka.api_token')
        ])->{$method}($fullUrl, $params);

        if ($response->ok())
            return $response->collect();

        return $response->body();
    }

    public function getViewName($name)
    {
        $prefix = config('vnnit-core.prefix');
        return "{$prefix}::components.{$name}";
    }

    public function renderMenus($dataTree, $menuName, $menuStyle = null, $dropdown = true)
    {
        Menu::create($menuName, function($menu) use($dataTree, $menuStyle, $dropdown) {
            if (!is_null($menuStyle))
                $menu->style($menuStyle);
            $this->renderElementMenu($menu, $dataTree, 'nav-link', $dropdown);
        });
        return Menu::get($menuName);
    }

    private function renderElementMenu(&$menu, $dataTree, $class = 'nav-link', $dropdown = true)
    {
        $dropdownClass = $dropdown ? 'dropdown-item' : $class;
        $dataTree->each(function($item) use(&$menu, $class, $dropdownClass, $dropdown) {
            if (data_get($item, 'visiable', true)) {
                $childrens = data_get($item, 'children');
                if ($childrens && $childrens->count() > 0) {
                    $menu->dropdown(data_get($item, 'menu_title'), function ($subMenu) use ($childrens, $dropdownClass, $dropdown) {
                        $this->renderElementMenu($subMenu, $childrens, $dropdownClass, $dropdown);
                    });
                } else {
                    $menu->url(data_get($item, 'menu_link'), data_get($item, 'menu_title'),
                        [
                            'class' => $class,
                            'icon' => data_get($item, 'menu_icon'),
                            'active' => data_get($item, 'actived', false)
                        ]);
                }
            }
        });
    }
}
